#!/bin/bash
# =============================================================
# Laravel + Twill Full Audit — Portable
# Auto-detect domain, project path, user, service
# =============================================================
# Usage:
#   ./audit-project.sh                            # auto-detect
#   ./audit-project.sh /path/to/project           # manual path
#   ./audit-project.sh /path/to/project domain.com # path + domain
# =============================================================

set -uo pipefail

# ═══ WARNA ═══
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
CYAN='\033[0;36m'
BOLD='\033[1m'
NC='\033[0m'

# ═══ ARGUMENTS ═══
PROJECT_PATH="${1:-}"
DOMAIN_ARG="${2:-}"

# ═══ AUTO-DETECT PROJECT PATH ═══
if [[ -z "$PROJECT_PATH" ]]; then
    for p in \
        /home/*/public_html/*/artisan \
        /var/www/*/artisan \
        /var/www/html/artisan \
        /home/*/artisan \
        "$(pwd)/artisan"; do
        if [[ -f "$p" ]]; then
            PROJECT_PATH=$(dirname "$p")
            break
        fi
    done
fi

if [[ -z "$PROJECT_PATH" ]] || [[ ! -f "$PROJECT_PATH/artisan" ]]; then
    echo -e "${RED}❌ Project Laravel tidak ditemukan.${NC}"
    echo "Usage: $0 /path/to/project [domain.com]"
    exit 1
fi

cd "$PROJECT_PATH" || exit 1

# ═══ AUTO-DETECT DOMAIN ═══
if [[ -n "$DOMAIN_ARG" ]]; then
    DOMAIN="$DOMAIN_ARG"
elif [[ -f ".env" ]]; then
    APP_URL=$(grep "^APP_URL=" .env | cut -d= -f2- | tr -d '"' | tr -d "'")
    DOMAIN=$(echo "$APP_URL" | sed -E 's|^https?://||;s|/.*$||')
fi

if [[ -z "${DOMAIN:-}" ]]; then
    DOMAIN=$(hostname -f 2>/dev/null || hostname)
    echo -e "${YELLOW}⚠️  Domain tidak terdeteksi, pakai: $DOMAIN${NC}"
fi

BASE_URL="https://$DOMAIN"

# ═══ AUTO-DETECT USER ═══
WEB_USER=$(stat -c '%U' "$PROJECT_PATH/storage" 2>/dev/null || echo "www-data")
WEB_GROUP=$(stat -c '%G' "$PROJECT_PATH/storage" 2>/dev/null || echo "$WEB_USER")

# ═══ AUTO-DETECT APP PATH (Twill admin) ═══
APP_PATH=$(grep -E "^(TWILL_ADMIN_APP_PATH|ADMIN_APP_PATH)=" .env 2>/dev/null | head -1 | cut -d= -f2- | tr -d '"' | tr -d "'")
APP_PATH="${APP_PATH:-panel-maintenance}"

# ═══ AUTO-DETECT HORIZON PATH ═══
HORIZON_PATH=$(grep "^HORIZON_PATH=" .env 2>/dev/null | cut -d= -f2- | tr -d '"' | tr -d "'")
HORIZON_PATH="${HORIZON_PATH:-horizon}"

# ═══ AUTO-DETECT QUEUE SERVICES ═══
QUEUE_SERVICES=$(systemctl list-units --type=service --all 2>/dev/null | grep -iE "horizon|queue" | awk '{print $1}' | head -3)

# ═══ HEADER ═══
echo ""
echo -e "${BOLD}╔══════════════════════════════════════════════════════════════╗${NC}"
echo -e "${BOLD}║   FULL AUDIT — $(date '+%Y-%m-%d %H:%M')                       ║${NC}"
echo -e "${BOLD}╚══════════════════════════════════════════════════════════════╝${NC}"
echo ""
echo -e "  ${CYAN}Server:${NC}   $(hostname)"
echo -e "  ${CYAN}Project:${NC}  $PROJECT_PATH"
echo -e "  ${CYAN}Domain:${NC}   $BASE_URL"
echo -e "  ${CYAN}User:${NC}     $WEB_USER:$WEB_GROUP"
echo -e "  ${CYAN}Admin:${NC}    /$APP_PATH"
echo -e "  ${CYAN}Horizon:${NC}  /$HORIZON_PATH"
echo ""

# ═══════════════════════════════════════════════════════════════
# 1. SECURITY SCAN
# ═══════════════════════════════════════════════════════════════
echo -e "${BOLD}╔══════════════════════════════════════════════════════════════╗${NC}"
echo -e "${BOLD}║  1. SECURITY SCAN (Checkpoint)                               ║${NC}"
echo -e "${BOLD}╚══════════════════════════════════════════════════════════════╝${NC}"

if php artisan list 2>/dev/null | grep -q "checkpoint:scan"; then
    php artisan checkpoint:scan 2>&1 | tail -40
else
    echo -e "  ${YELLOW}⚠️ Checkpoint tidak terinstall${NC}"
fi

# ═══════════════════════════════════════════════════════════════
# 2. REDIS STATUS
# ═══════════════════════════════════════════════════════════════
echo ""
echo -e "${BOLD}╔══════════════════════════════════════════════════════════════╗${NC}"
echo -e "${BOLD}║  2. REDIS STATUS                                             ║${NC}"
echo -e "${BOLD}╚══════════════════════════════════════════════════════════════╝${NC}"

REDIS_SVC=""
for svc in redis-server redis; do
    if systemctl is-active "$svc" >/dev/null 2>&1; then
        REDIS_SVC="$svc"
        break
    fi
done

if [[ -n "$REDIS_SVC" ]]; then
    echo "  Service:  $REDIS_SVC"
    echo "  Ping:     $(redis-cli ping 2>/dev/null || echo 'FAIL')"
    echo "  Version:  $(redis-cli info server 2>/dev/null | grep redis_version | cut -d: -f2 | tr -d '\r')"
    echo "  Uptime:   $(redis-cli info server 2>/dev/null | grep uptime_in_days | cut -d: -f2 | tr -d '\r') days"
    echo ""
    echo "  Memory:"
    redis-cli info memory 2>/dev/null | grep -E "used_memory_human|maxmemory_human|maxmemory_policy|mem_fragmentation_ratio" | sed 's/^/    /'
    echo ""
    echo "  DB Usage:"
    for i in 0 1 2 3 4 5; do
        echo "    DB $i: $(redis-cli -n $i dbsize 2>/dev/null) keys"
    done
else
    echo -e "  ${YELLOW}⚠️ Redis tidak berjalan / tidak terinstall${NC}"
fi

# ═══════════════════════════════════════════════════════════════
# 3. QUEUE / HORIZON STATUS
# ═══════════════════════════════════════════════════════════════
echo ""
echo -e "${BOLD}╔══════════════════════════════════════════════════════════════╗${NC}"
echo -e "${BOLD}║  3. QUEUE / HORIZON STATUS                                   ║${NC}"
echo -e "${BOLD}╚══════════════════════════════════════════════════════════════╝${NC}"

if [[ -n "$QUEUE_SERVICES" ]]; then
    for svc in $QUEUE_SERVICES; do
        echo "  $(systemctl is-active "$svc" 2>/dev/null) → $svc"
    done
else
    echo -e "  ${YELLOW}⚠️ Tidak ada service queue/horizon terdeteksi${NC}"
fi

if php artisan list 2>/dev/null | grep -q "horizon:status"; then
    php artisan horizon:status 2>/dev/null | sed 's/^/  /'
    echo ""
    echo "  Supervisors:"
    php artisan horizon:supervisors 2>/dev/null | sed 's/^/  /'
fi

# ═══════════════════════════════════════════════════════════════
# 4. LARAVEL CONFIG
# ═══════════════════════════════════════════════════════════════
echo ""
echo -e "${BOLD}╔══════════════════════════════════════════════════════════════╗${NC}"
echo -e "${BOLD}║  4. LARAVEL CONFIG (runtime)                                 ║${NC}"
echo -e "${BOLD}╚══════════════════════════════════════════════════════════════╝${NC}"

sudo -u "$WEB_USER" php artisan tinker --execute="
echo 'ENV:              ' . config('app.env') . PHP_EOL;
echo 'Debug:            ' . (config('app.debug') ? '⚠️  TRUE (BAHAYA!)' : '✅ false') . PHP_EOL;
echo 'URL:              ' . config('app.url') . PHP_EOL;
echo 'Locale:           ' . config('app.locale') . PHP_EOL;
echo 'Session driver:   ' . config('session.driver') . PHP_EOL;
echo 'Cache store:      ' . config('cache.default') . PHP_EOL;
echo 'Queue connection: ' . config('queue.default') . PHP_EOL;
echo 'Redis default DB: ' . config('database.redis.default.database') . PHP_EOL;
echo 'Redis cache DB:   ' . config('database.redis.cache.database') . PHP_EOL;
echo 'Redis prefix:     ' . config('database.redis.options.prefix') . PHP_EOL;
echo 'safe_html exists: ' . (function_exists('safe_html') ? '✅ YES' : '❌ NO') . PHP_EOL;
echo 'safe_svg exists:  ' . (function_exists('safe_svg') ? '✅ YES' : '❌ NO') . PHP_EOL;
echo 'twill_proxy_img:  ' . (function_exists('twill_proxy_img') ? '✅ YES' : '❌ NO') . PHP_EOL;
"

# ═══════════════════════════════════════════════════════════════
# 5. COMPOSER AUDIT
# ═══════════════════════════════════════════════════════════════
echo ""
echo -e "${BOLD}╔══════════════════════════════════════════════════════════════╗${NC}"
echo -e "${BOLD}║  5. COMPOSER AUDIT                                           ║${NC}"
echo -e "${BOLD}╚══════════════════════════════════════════════════════════════╝${NC}"

sudo -u "$WEB_USER" composer audit 2>&1 | tail -10

# ═══════════════════════════════════════════════════════════════
# 6. NPM AUDIT
# ═══════════════════════════════════════════════════════════════
echo ""
echo -e "${BOLD}╔══════════════════════════════════════════════════════════════╗${NC}"
echo -e "${BOLD}║  6. NPM AUDIT                                                ║${NC}"
echo -e "${BOLD}╚══════════════════════════════════════════════════════════════╝${NC}"

if [[ -f "package.json" ]]; then
    sudo -u "$WEB_USER" npm audit --audit-level=high 2>&1 | tail -10
else
    echo "  ⚠️ package.json tidak ada"
fi

# ═══════════════════════════════════════════════════════════════
# 7. HTTP STATUS
# ═══════════════════════════════════════════════════════════════
echo ""
echo -e "${BOLD}╔══════════════════════════════════════════════════════════════╗${NC}"
echo -e "${BOLD}║  7. HTTP STATUS — SEMUA HALAMAN                              ║${NC}"
echo -e "${BOLD}╚══════════════════════════════════════════════════════════════╝${NC}"

# Detect frontend routes from sitemap (kalau ada) atau hardcode fallback
URLS=(
    "/"
    "/$APP_PATH"
    "/$HORIZON_PATH"
    "/sitemap.xml"
    "/robots.txt"
    "/health"
    "/up"
)

# Tambah halaman umum sekolah kalau project ini punya
for guess in "/jurusan" "/fasilitas" "/prestasi" "/ekstrakurikuler" "/berita"; do
    if curl -sI "$BASE_URL$guess" --max-time 3 -o /dev/null -w "%{http_code}" | grep -qE "^[23]"; then
        URLS+=("$guess")
    fi
done

for URL in "${URLS[@]}"; do
    RESULT=$(curl -o /dev/null -s -w "%{http_code}|%{time_total}" "$BASE_URL$URL" --max-time 10)
    HTTP=$(echo "$RESULT" | cut -d'|' -f1)
    TIME=$(echo "$RESULT" | cut -d'|' -f2)
    printf "  %-35s HTTP:%s  %ss\n" "$URL" "$HTTP" "$TIME"
done

# ═══════════════════════════════════════════════════════════════
# 8. GIT STATUS
# ═══════════════════════════════════════════════════════════════
echo ""
echo -e "${BOLD}╔══════════════════════════════════════════════════════════════╗${NC}"
echo -e "${BOLD}║  8. GIT STATUS                                               ║${NC}"
echo -e "${BOLD}╚══════════════════════════════════════════════════════════════╝${NC}"

if [[ -d ".git" ]]; then
    git remote -v 2>/dev/null | head -1 | sed 's/^/  /'
    echo ""
    echo "  Branch:   $(git branch --show-current 2>/dev/null)"
    echo "  Commits:  $(git rev-list --count HEAD 2>/dev/null) total"
    echo "  Recent:"
    git log --oneline -5 2>/dev/null | sed 's/^/    /'
    echo ""
    echo "  Uncommitted: $(git status --short 2>/dev/null | wc -l) file(s)"
    echo "  Repo size:   $(du -sh .git 2>/dev/null | cut -f1)"
    echo "  Tracked:     $(git ls-files 2>/dev/null | wc -l) files"
    echo ""
    echo "  Vendor tracked:  $(git ls-files vendor/ 2>/dev/null | wc -l)"
    echo "  Node tracked:    $(git ls-files node_modules/ 2>/dev/null | wc -l)"
    echo "  .env tracked:    $(git ls-files .env 2>/dev/null | wc -l)"
else
    echo "  ⚠️ Bukan git repository"
fi

# ═══════════════════════════════════════════════════════════════
# 9. SYSTEMD SERVICES
# ═══════════════════════════════════════════════════════════════
echo ""
echo -e "${BOLD}╔══════════════════════════════════════════════════════════════╗${NC}"
echo -e "${BOLD}║  9. SYSTEMD SERVICES                                         ║${NC}"
echo -e "${BOLD}╚══════════════════════════════════════════════════════════════╝${NC}"

# Auto-detect services yang relevan
SERVICES=(redis-server redis horizon-web laravel-queue lshttpd lsws nginx apache2 php8.4-fpm php8.3-fpm php8.2-fpm mariadb mysql cron)
for svc in "${SERVICES[@]}"; do
    if systemctl list-unit-files 2>/dev/null | grep -q "^$svc.service"; then
        STATUS=$(systemctl is-active "$svc" 2>/dev/null)
        case "$STATUS" in
            active)   printf "  ✅ %-18s %s\n" "$svc:" "$STATUS" ;;
            inactive) printf "  ⚪ %-18s %s\n" "$svc:" "$STATUS" ;;
            *)        printf "  ⚠️  %-18s %s\n" "$svc:" "$STATUS" ;;
        esac
    fi
done

# ═══════════════════════════════════════════════════════════════
# 10. DISK & MEMORY
# ═══════════════════════════════════════════════════════════════
echo ""
echo -e "${BOLD}╔══════════════════════════════════════════════════════════════╗${NC}"
echo -e "${BOLD}║  10. DISK & MEMORY                                           ║${NC}"
echo -e "${BOLD}╚══════════════════════════════════════════════════════════════╝${NC}"
df -h / | tail -1 | sed 's/^/  /'
free -h | head -2 | sed 's/^/  /'
echo "  Load: $(cat /proc/loadavg | awk '{print $1, $2, $3}')"

# ═══════════════════════════════════════════════════════════════
# 11. STORAGE & BACKUP
# ═══════════════════════════════════════════════════════════════
echo ""
echo -e "${BOLD}╔══════════════════════════════════════════════════════════════╗${NC}"
echo -e "${BOLD}║  11. STORAGE & BACKUP                                        ║${NC}"
echo -e "${BOLD}╚══════════════════════════════════════════════════════════════╝${NC}"

for dir in "storage/logs" "storage/backups" "storage/app/backups" "storage/app/private"; do
    if [[ -d "$dir" ]]; then
        echo "  $dir: $(du -sh "$dir" 2>/dev/null | cut -f1)"
    fi
done
echo ""
echo "  Recent backups:"
ls -lt storage/backups/ storage/app/backups/ 2>/dev/null | head -5 | sed 's/^/    /'
echo ""

# Error log check (cari file log terbaru, tidak hardcode tanggal)
LATEST_LOG=$(ls -t storage/logs/laravel-*.log 2>/dev/null | head -1)
if [[ -n "$LATEST_LOG" ]]; then
    ERROR_COUNT=$(grep -c "ERROR" "$LATEST_LOG" 2>/dev/null || echo 0)
    echo "  Log terbaru: $LATEST_LOG"
    echo "  Error count: $ERROR_COUNT"
    if [[ "$ERROR_COUNT" -gt 0 ]]; then
        echo "  Last 3 errors:"
        grep "ERROR" "$LATEST_LOG" 2>/dev/null | tail -3 | cut -c1-150 | sed 's/^/    /'
    fi
fi

# ═══════════════════════════════════════════════════════════════
# 12. CUSTOM ADMIN ROUTES
# ═══════════════════════════════════════════════════════════════
echo ""
echo -e "${BOLD}╔══════════════════════════════════════════════════════════════╗${NC}"
echo -e "${BOLD}║  12. CUSTOM ADMIN ROUTES                                     ║${NC}"
echo -e "${BOLD}╚══════════════════════════════════════════════════════════════╝${NC}"
php artisan route:list 2>/dev/null | grep -E "admin/|$APP_PATH" | grep -v "twill\." | head -10 | sed 's/^/  /'

# ═══════════════════════════════════════════════════════════════
# 13. SECURITY HEADERS
# ═══════════════════════════════════════════════════════════════
echo ""
echo -e "${BOLD}╔══════════════════════════════════════════════════════════════╗${NC}"
echo -e "${BOLD}║  13. SECURITY HEADERS                                        ║${NC}"
echo -e "${BOLD}╚══════════════════════════════════════════════════════════════╝${NC}"

HEADERS=$(curl -sI "$BASE_URL/" 2>/dev/null | grep -iE "x-frame-options|x-content-type|strict-transport|content-security|referrer-policy|permissions-policy|x-xss-protection")
if [[ -n "$HEADERS" ]]; then
    echo "$HEADERS" | sed 's/^/  /'
else
    echo -e "  ${YELLOW}⚠️ Security headers tidak ter-set${NC}"
fi

# ═══════════════════════════════════════════════════════════════
# 14. UPLOADS PHP BLOCK TEST
# ═══════════════════════════════════════════════════════════════
echo ""
echo -e "${BOLD}╔══════════════════════════════════════════════════════════════╗${NC}"
echo -e "${BOLD}║  14. UPLOADS PHP BLOCK TEST                                  ║${NC}"
echo -e "${BOLD}╚══════════════════════════════════════════════════════════════╝${NC}"

for UPLOAD_PATH in "/storage/uploads" "/storage/file_library" "/media" "/storage/twill"; do
    CODE=$(curl -o /dev/null -s -w "%{http_code}" "$BASE_URL$UPLOAD_PATH/test-php-exec.php" --max-time 5)
    printf "  %-30s → HTTP %s\n" "$UPLOAD_PATH/test-php-exec.php" "$CODE"
    if [[ "$CODE" == "403" ]]; then
        echo "    ✅ PHP execution blocked"
    elif [[ "$CODE" == "404" ]]; then
        echo "    ⚠️ Test tidak konklusif (file tidak ada)"
    fi
done

# ═══════════════════════════════════════════════════════════════
# 15. CRON JOBS
# ═══════════════════════════════════════════════════════════════
echo ""
echo -e "${BOLD}╔══════════════════════════════════════════════════════════════╗${NC}"
echo -e "${BOLD}║  15. CRON JOBS                                               ║${NC}"
echo -e "${BOLD}╚══════════════════════════════════════════════════════════════╝${NC}"

echo "  Root crontab:"
crontab -l 2>/dev/null | grep -v "^#" | grep -v "^$" | head -5 | sed 's/^/    /'

echo ""
echo "  Web user crontab ($WEB_USER):"
sudo -u "$WEB_USER" crontab -l 2>/dev/null | grep -v "^#" | grep -v "^$" | head -5 | sed 's/^/    /'

# ═══════════════════════════════════════════════════════════════
# 16. SUMMARY
# ═══════════════════════════════════════════════════════════════
echo ""
echo -e "${BOLD}╔══════════════════════════════════════════════════════════════╗${NC}"
echo -e "${BOLD}║  16. SUMMARY                                                 ║${NC}"
echo -e "${BOLD}╚══════════════════════════════════════════════════════════════╝${NC}"
echo "  Tanggal:  $(date)"
echo "  Server:   $(hostname)"
echo "  Domain:   $DOMAIN"
echo "  Project:  $PROJECT_PATH"
echo "  Uptime:   $(uptime -p)"
echo ""
echo -e "${GREEN}Audit selesai.${NC}"
echo ""

