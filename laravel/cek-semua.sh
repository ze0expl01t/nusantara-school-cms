#!/bin/bash
# =============================================================
# CEK SEMUA — SMK Muhammadiyah 1 Blora
# =============================================================

set -uo pipefail

# ═══ WARNA ═══
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
CYAN='\033[0;36m'
MAGENTA='\033[0;35m'
BOLD='\033[1m'
NC='\033[0m'
BG_GREEN='\033[42m'
BG_RED='\033[41m'
BG_YELLOW='\033[43m'

# ═══ DETEKSI PROJECT ═══
PROJECT_PATH="/home/smkmuh1blora.sch.id/public_html/nusantara"
cd "$PROJECT_PATH" || exit 1

WEB_USER=$(stat -c '%U' storage 2>/dev/null || echo "www-data")
DOMAIN=$(grep "^APP_URL=" .env | cut -d= -f2- | tr -d '"' | tr -d "'" | sed -E 's|^https?://||;s|/.*$||')
BASE_URL="https://$DOMAIN"

# ═══ CLEAR SCREEN ═══
clear

# ═══ HEADER ═══
echo ""
echo -e "${BOLD}${MAGENTA}╔═══════════════════════════════════════════════════════════════════════╗${NC}"
echo -e "${BOLD}${MAGENTA}║                                                                       ║${NC}"
echo -e "${BOLD}${MAGENTA}║   🏫  SMK MUHAMMADIYAH 1 BLORA — FULL HEALTH CHECK                   ║${NC}"
echo -e "${BOLD}${MAGENTA}║                                                                       ║${NC}"
echo -e "${BOLD}${MAGENTA}╚═══════════════════════════════════════════════════════════════════════╝${NC}"
echo ""
echo -e "  📅 $(date '+%A, %d %B %Y — %H:%M:%S WIB')"
echo -e "  🌐 $BASE_URL"
echo -e "  🖥️  $(hostname) | Uptime: $(uptime -p | sed 's/up //')"
echo ""

# ═══════════════════════════════════════════════════════════════
# 1. WEBSITE STATUS
# ═══════════════════════════════════════════════════════════════
echo -e "${BOLD}${BLUE}┌─────────────────────────────────────────────────────────────────────┐${NC}"
echo -e "${BOLD}${BLUE}│  1. WEBSITE ACCESS                                                  │${NC}"
echo -e "${BOLD}${BLUE}└─────────────────────────────────────────────────────────────────────┘${NC}"

check_url() {
    local label="$1"
    local url="$2"
    local expected="${3:-200}"
    local result
    result=$(curl -o /dev/null -s -w "%{http_code}|%{time_total}" "$url" --max-time 10)
    local code=$(echo "$result" | cut -d'|' -f1)
    local time=$(echo "$result" | cut -d'|' -f2)
    
    if [[ "$code" == "$expected" ]]; then
        printf "  ${GREEN}✅${NC} %-30s HTTP:%s (%.2fs)\n" "$label" "$code" "$time"
    elif [[ "$code" == "302" ]] || [[ "$code" == "301" ]]; then
        printf "  ${YELLOW}⚠️${NC}  %-30s HTTP:%s (%.2fs) redirect\n" "$label" "$code" "$time"
    else
        printf "  ${RED}❌${NC} %-30s HTTP:%s (%.2fs)\n" "$label" "$code" "$time"
    fi
}

check_url "Homepage" "$BASE_URL/" "200"
check_url "Admin Panel" "$BASE_URL/panel-maintenance" "302"
check_url "Horizon (curl → 403 wajar)" "$BASE_URL/horizon" "403"
check_url "Sitemap" "$BASE_URL/sitemap.xml" "200"
check_url "Robots" "$BASE_URL/robots.txt" "200"
check_url "Health (/up)" "$BASE_URL/up" "200"
check_url "Jurusan" "$BASE_URL/jurusan" "200"
check_url "Fasilitas" "$BASE_URL/fasilitas" "200"
check_url "Prestasi" "$BASE_URL/prestasi" "200"
check_url "Ekstrakurikuler" "$BASE_URL/ekstrakurikuler" "200"

echo ""

# ═══════════════════════════════════════════════════════════════
# 2. LARAVEL CONFIG
# ═══════════════════════════════════════════════════════════════
echo -e "${BOLD}${BLUE}┌─────────────────────────────────────────────────────────────────────┐${NC}"
echo -e "${BOLD}${BLUE}│  2. LARAVEL RUNTIME CONFIG                                          │${NC}"
echo -e "${BOLD}${BLUE}└─────────────────────────────────────────────────────────────────────┘${NC}"

CONFIG_OUTPUT=$(sudo -u "$WEB_USER" php artisan tinker --execute='
echo config("app.env") . "|";
echo (config("app.debug") ? "true" : "false") . "|";
echo config("app.locale") . "|";
echo config("session.driver") . "|";
echo config("cache.default") . "|";
echo config("queue.default") . "|";
echo config("database.redis.default.database") . "|";
echo config("horizon.path") . "|";
echo (function_exists("safe_html") ? "yes" : "no");
' 2>/dev/null | tr -d '\r\n')

IFS='|' read -r ENV DEBUG LOCALE SESSION CACHE QUEUE REDIS_DB HORIZON SAFE_HTML <<< "$CONFIG_OUTPUT"

printf "  %-25s %s\n" "Environment:" "$([[ "$ENV" == "production" ]] && echo -e "${GREEN}✅${NC} $ENV" || echo -e "${RED}❌${NC} $ENV")"
printf "  %-25s %s\n" "Debug Mode:" "$([[ "$DEBUG" == "false" ]] && echo -e "${GREEN}✅${NC} disabled" || echo -e "${RED}❌${NC} ENABLED (BAHAYA!)")"
printf "  %-25s %s\n" "Locale:" "$LOCALE"
printf "  %-25s %s\n" "Session Driver:" "$SESSION"
printf "  %-25s %s\n" "Cache Store:" "$CACHE"
printf "  %-25s %s\n" "Queue Connection:" "$QUEUE"
printf "  %-25s %s\n" "Redis DB (default):" "$REDIS_DB"
printf "  %-25s %s\n" "Horizon Path:" "/$HORIZON"
printf "  %-25s %s\n" "safe_html():" "$([[ "$SAFE_HTML" == "yes" ]] && echo -e "${GREEN}✅${NC}" || echo -e "${RED}❌${NC}")"

echo ""

# ═══════════════════════════════════════════════════════════════
# 3. SERVICES
# ═══════════════════════════════════════════════════════════════
echo -e "${BOLD}${BLUE}┌─────────────────────────────────────────────────────────────────────┐${NC}"
echo -e "${BOLD}${BLUE}│  3. SYSTEM SERVICES                                                 │${NC}"
echo -e "${BOLD}${BLUE}└─────────────────────────────────────────────────────────────────────┘${NC}"

for svc in redis-server horizon-web mariadb mysql cron; do
    STATUS=$(systemctl is-active "$svc" 2>/dev/null)
    if [[ "$STATUS" == "active" ]]; then
        printf "  ${GREEN}✅${NC} %-25s ${GREEN}%s${NC}\n" "$svc" "$STATUS"
    elif [[ "$STATUS" == "inactive" ]]; then
        printf "  ${YELLOW}⚪${NC} %-25s %s\n" "$svc" "$STATUS"
    else
        printf "  ${RED}❌${NC} %-25s %s\n" "$svc" "$STATUS"
    fi
done

echo ""

# ═══════════════════════════════════════════════════════════════
# 4. REDIS
# ═══════════════════════════════════════════════════════════════
echo -e "${BOLD}${BLUE}┌─────────────────────────────────────────────────────────────────────┐${NC}"
echo -e "${BOLD}${BLUE}│  4. REDIS STATUS                                                    │${NC}"
echo -e "${BOLD}${BLUE}└─────────────────────────────────────────────────────────────────────┘${NC}"

REDIS_PING=$(redis-cli ping 2>/dev/null)
REDIS_MEM=$(redis-cli info memory 2>/dev/null | grep "used_memory_human" | cut -d: -f2 | tr -d '\r')
REDIS_MAXMEM=$(redis-cli info memory 2>/dev/null | grep "maxmemory_human" | cut -d: -f2 | tr -d '\r')
REDIS_POLICY=$(redis-cli info memory 2>/dev/null | grep "maxmemory_policy" | cut -d: -f2 | tr -d '\r')

printf "  ${GREEN}✅${NC} %-25s %s\n" "Ping:" "$REDIS_PING"
printf "  ${CYAN}ℹ️${NC}  %-25s %s\n" "Used Memory:" "$REDIS_MEM"
printf "  ${CYAN}ℹ️${NC}  %-25s %s\n" "Max Memory:" "$REDIS_MAXMEM"
printf "  ${CYAN}ℹ️${NC}  %-25s %s\n" "Policy:" "$REDIS_POLICY"
echo ""

echo "  📊 Database Usage:"
for i in 0 1 2 3; do
    KEYS=$(redis-cli -n $i dbsize 2>/dev/null)
    LABEL=""
    case $i in
        2) LABEL="(session+queue)";;
        3) LABEL="(cache)";;
    esac
    printf "     DB %d: %-6s keys  %s\n" "$i" "$KEYS" "$LABEL"
done

echo ""

# ═══════════════════════════════════════════════════════════════
# 5. HORIZON
# ═══════════════════════════════════════════════════════════════
echo -e "${BOLD}${BLUE}┌─────────────────────────────────────────────────────────────────────┐${NC}"
echo -e "${BOLD}${BLUE}│  5. HORIZON QUEUE                                                   │${NC}"
echo -e "${BOLD}${BLUE}└─────────────────────────────────────────────────────────────────────┘${NC}"

HORIZON_STATUS=$(systemctl is-active horizon-web 2>/dev/null)
printf "  %-25s %s\n" "Service:" "$([[ "$HORIZON_STATUS" == "active" ]] && echo -e "${GREEN}✅ active${NC}" || echo -e "${RED}❌ inactive${NC}")"

HORIZON_INFO=$(sudo -u "$WEB_USER" php artisan horizon:status 2>/dev/null | grep -oE "running|paused|inactive" | head -1)
printf "  %-25s %s\n" "Worker:" "$HORIZON_INFO"

echo ""

# ═══════════════════════════════════════════════════════════════
# 6. DATABASE
# ═══════════════════════════════════════════════════════════════
echo -e "${BOLD}${BLUE}┌─────────────────────────────────────────────────────────────────────┐${NC}"
echo -e "${BOLD}${BLUE}│  6. DATABASE                                                        │${NC}"
echo -e "${BOLD}${BLUE}└─────────────────────────────────────────────────────────────────────┘${NC}"

DB_NAME=$(grep "^DB_DATABASE=" .env | cut -d= -f2)
DB_SIZE=$(mysql -u root -e "SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) FROM information_schema.TABLES WHERE table_schema = '$DB_NAME';" 2>/dev/null | tail -1)

printf "  ${GREEN}✅${NC} %-25s %s\n" "Database:" "$DB_NAME"
printf "  ${CYAN}ℹ️${NC}  %-25s %s MB\n" "Size:" "$DB_SIZE"

MYSQL_STATUS=$(systemctl is-active mysql 2>/dev/null || systemctl is-active mariadb 2>/dev/null)
printf "  ${GREEN}✅${NC} %-25s %s\n" "MySQL Service:" "$MYSQL_STATUS"

echo ""

# ═══════════════════════════════════════════════════════════════
# 7. STORAGE & BACKUP
# ═══════════════════════════════════════════════════════════════
echo -e "${BOLD}${BLUE}┌─────────────────────────────────────────────────────────────────────┐${NC}"
echo -e "${BOLD}${BLUE}│  7. STORAGE & BACKUP                                                │${NC}"
echo -e "${BOLD}${BLUE}└─────────────────────────────────────────────────────────────────────┘${NC}"

LOGS_SIZE=$(du -sh storage/logs 2>/dev/null | cut -f1)
BACKUP_SIZE=$(du -sh storage/backups 2>/dev/null | cut -f1)
IMGCACHE_SIZE=$(du -sh storage/app/private 2>/dev/null | cut -f1)
UPLOADS_SIZE=$(du -sh storage/app/public/uploads 2>/dev/null | cut -f1)

printf "  %-25s %s\n" "Logs:" "$LOGS_SIZE"
printf "  %-25s %s\n" "Backups:" "$BACKUP_SIZE"
printf "  %-25s %s\n" "Image Cache:" "$IMGCACHE_SIZE"
printf "  %-25s %s\n" "Uploads:" "$UPLOADS_SIZE"

echo ""
echo "  📁 Latest backups:"
ls -t storage/backups/*/ 2>/dev/null | head -3 | while read -r d; do
    echo "     - $(basename "$d")"
done

echo ""

# ═══════════════════════════════════════════════════════════════
# 8. GIT
# ═══════════════════════════════════════════════════════════════
echo -e "${BOLD}${BLUE}┌─────────────────────────────────────────────────────────────────────┐${NC}"
echo -e "${BOLD}${BLUE}│  8. GIT REPOSITORY                                                  │${NC}"
echo -e "${BOLD}${BLUE}└─────────────────────────────────────────────────────────────────────┘${NC}"

if [[ -d ".git" ]]; then
    BRANCH=$(git branch --show-current 2>/dev/null)
    COMMITS=$(git rev-list --count HEAD 2>/dev/null)
    STATUS=$(git status --short 2>/dev/null | wc -l)
    REPO_SIZE=$(du -sh .git 2>/dev/null | cut -f1)
    
    printf "  ${GREEN}✅${NC} %-25s %s\n" "Branch:" "$BRANCH"
    printf "  ${GREEN}✅${NC} %-25s %s\n" "Total Commits:" "$COMMITS"
    printf "  ${CYAN}ℹ️${NC}  %-25s %s\n" "Repo Size:" "$REPO_SIZE"
    
    if [[ "$STATUS" -eq 0 ]]; then
        printf "  ${GREEN}✅${NC} %-25s %s\n" "Working Tree:" "clean"
    else
        printf "  ${YELLOW}⚠️${NC}  %-25s %s file uncommitted\n" "Working Tree:" "$STATUS"
    fi
    
    echo ""
    echo "  📜 Recent commits:"
    git log --oneline -3 2>/dev/null | while read -r line; do
        echo "     $line"
    done
else
    echo -e "  ${RED}❌${NC} Bukan git repository"
fi

echo ""

# ═══════════════════════════════════════════════════════════════
# 9. SECURITY
# ═══════════════════════════════════════════════════════════════
echo -e "${BOLD}${BLUE}┌─────────────────────────────────────────────────────────────────────┐${NC}"
echo -e "${BOLD}${BLUE}│  9. SECURITY                                                        │${NC}"
echo -e "${BOLD}${BLUE}└─────────────────────────────────────────────────────────────────────┘${NC}"

# Check headers
HEADERS=$(curl -sI "$BASE_URL/" --max-time 5 2>/dev/null)
for header in "strict-transport-security" "content-security-policy" "x-frame-options" "x-content-type-options"; do
    if echo "$HEADERS" | grep -qi "$header"; then
        printf "  ${GREEN}✅${NC} %-30s present\n" "$header:"
    else
        printf "  ${RED}❌${NC} %-30s MISSING\n" "$header:"
    fi
done

# PHP exec block test
PHP_BLOCK=$(curl -o /dev/null -s -w "%{http_code}" "$BASE_URL/storage/uploads/test.php" --max-time 5)
printf "  %s %-30s HTTP %s\n" "$([[ "$PHP_BLOCK" == "403" ]] && echo -e "${GREEN}✅${NC}" || echo -e "${YELLOW}⚠️${NC}")" "PHP Block Test:" "$PHP_BLOCK"

echo ""

# ═══════════════════════════════════════════════════════════════
# 10. PERFORMANCE
# ═══════════════════════════════════════════════════════════════
echo -e "${BOLD}${BLUE}┌─────────────────────────────────────────────────────────────────────┐${NC}"
echo -e "${BOLD}${BLUE}│  10. PERFORMANCE                                                    │${NC}"
echo -e "${BOLD}${BLUE}└─────────────────────────────────────────────────────────────────────┘${NC}"

# TTFB test (3x)
TIMES=()
for i in 1 2 3; do
    T=$(curl -o /dev/null -s -w "%{time_total}" "$BASE_URL/" --max-time 10)
    TIMES+=("$T")
done
AVG_TIME=$(printf "%.2f" "$(echo "${TIMES[@]}" | tr ' ' '\n' | awk '{s+=$1} END {print s/NR}')")

printf "  %-25s %ss (avg of 3)\n" "TTFB Homepage:" "$AVG_TIME"

# Asset size
BUILD_SIZE=$(du -sh public/build 2>/dev/null | cut -f1)
printf "  %-25s %s\n" "Build Size:" "$BUILD_SIZE"

# Cache
CONFIG_CACHE=$([[ -f bootstrap/cache/config.php ]] && echo "yes" || echo "no")
ROUTE_CACHE=$([[ -f bootstrap/cache/routes-v7.php ]] && echo "yes" || echo "no")
printf "  %-25s %s\n" "Config Cached:" "$([[ "$CONFIG_CACHE" == "yes" ]] && echo -e "${GREEN}✅${NC}" || echo -e "${RED}❌${NC}")"
printf "  %-25s %s\n" "Routes Cached:" "$([[ "$ROUTE_CACHE" == "yes" ]] && echo -e "${GREEN}✅${NC}" || echo -e "${RED}❌${NC}")"

echo ""

# ═══════════════════════════════════════════════════════════════
# 11. IMAGE OPTIMIZATION
# ═══════════════════════════════════════════════════════════════
echo -e "${BOLD}${BLUE}┌─────────────────────────────────────────────────────────────────────┐${NC}"
echo -e "${BOLD}${BLUE}│  11. IMAGE OPTIMIZATION                                             │${NC}"
echo -e "${BOLD}${BLUE}└─────────────────────────────────────────────────────────────────────┘${NC}"

HTML=$(curl -s "$BASE_URL/" --max-time 10 2>/dev/null)
WEBP_COUNT=$(echo "$HTML" | grep -oE 'format=webp' | wc -l)
LEGACY_COUNT=$(echo "$HTML" | grep -oE '/storage/uploads/' | wc -l)
LAZY_COUNT=$(echo "$HTML" | grep -oE 'loading="lazy"' | wc -l)
EAGER_COUNT=$(echo "$HTML" | grep -oE 'loading="eager"' | wc -l)
FETCHPRIO=$(echo "$HTML" | grep -oE 'fetchpriority="high"' | wc -l)

printf "  ${GREEN}✅${NC} %-25s %s\n" "WebP URLs:" "$WEBP_COUNT"
printf "  ${CYAN}ℹ️${NC}  %-25s %s\n" "Lazy Loading:" "$LAZY_COUNT"
printf "  ${CYAN}ℹ️${NC}  %-25s %s\n" "Eager Loading:" "$EAGER_COUNT"
printf "  ${CYAN}ℹ️${NC}  %-25s %s\n" "fetchpriority=high:" "$FETCHPRIO"

if [[ "$LEGACY_COUNT" -gt 0 ]]; then
    printf "  ${YELLOW}⚠️${NC}  %-25s %s (perlu konversi)\n" "Legacy Images:" "$LEGACY_COUNT"
else
    printf "  ${GREEN}✅${NC} %-25s %s\n" "Legacy Images:" "0"
fi

echo ""

# ═══════════════════════════════════════════════════════════════
# 12. SYSTEM RESOURCES
# ═══════════════════════════════════════════════════════════════
echo -e "${BOLD}${BLUE}┌─────────────────────────────────────────────────────────────────────┐${NC}"
echo -e "${BOLD}${BLUE}│  12. SYSTEM RESOURCES                                               │${NC}"
echo -e "${BOLD}${BLUE}└─────────────────────────────────────────────────────────────────────┘${NC}"

# Disk
DISK_USED=$(df -h / | tail -1 | awk '{print $3}')
DISK_TOTAL=$(df -h / | tail -1 | awk '{print $2}')
DISK_PCT=$(df -h / | tail -1 | awk '{print $5}')
printf "  %-25s %s / %s (%s)\n" "Disk:" "$DISK_USED" "$DISK_TOTAL" "$DISK_PCT"

# Memory
MEM_TOTAL=$(free -h | awk 'NR==2 {print $2}')
MEM_USED=$(free -h | awk 'NR==2 {print $3}')
MEM_FREE=$(free -h | awk 'NR==2 {print $7}')
printf "  %-25s %s / %s (free: %s)\n" "Memory:" "$MEM_USED" "$MEM_TOTAL" "$MEM_FREE"

# Load
LOAD=$(cat /proc/loadavg | awk '{print $1, $2, $3}')
printf "  %-25s %s\n" "Load Average:" "$LOAD"

# CPU cores
CORES=$(nproc)
printf "  %-25s %s cores\n" "CPU:" "$CORES"

echo ""

# ═══════════════════════════════════════════════════════════════
# FINAL STATUS
# ═══════════════════════════════════════════════════════════════
echo ""
echo -e "${BOLD}${MAGENTA}╔═══════════════════════════════════════════════════════════════════════╗${NC}"
echo -e "${BOLD}${MAGENTA}║                          📊 FINAL STATUS                              ║${NC}"
echo -e "${BOLD}${MAGENTA}╚═══════════════════════════════════════════════════════════════════════╝${NC}"
echo ""

# Hitung total
TOTAL_CHECKS=0
PASSED_CHECKS=0

check_item() {
    TOTAL_CHECKS=$((TOTAL_CHECKS + 1))
    if [[ "$1" == "1" ]]; then
        PASSED_CHECKS=$((PASSED_CHECKS + 1))
        printf "  ${GREEN}✅${NC} %-30s\n" "$2"
    else
        printf "  ${RED}❌${NC} %-30s\n" "$2"
    fi
}

check_item "$([[ "$ENV" == "production" ]] && echo 1 || echo 0)" "Environment: production"
check_item "$([[ "$DEBUG" == "false" ]] && echo 1 || echo 0)" "Debug Mode: disabled"
check_item "$([[ "$(systemctl is-active horizon-web 2>/dev/null)" == "active" ]] && echo 1 || echo 0)" "Horizon: running"
check_item "$([[ "$(redis-cli ping 2>/dev/null)" == "PONG" ]] && echo 1 || echo 0)" "Redis: responding"
check_item "$([[ "$(systemctl is-active mysql 2>/dev/null || systemctl is-active mariadb 2>/dev/null)" == "active" ]] && echo 1 || echo 0)" "Database: running"
check_item "$([[ "$CONFIG_CACHE" == "yes" ]] && echo 1 || echo 0)" "Config cached"
check_item "$([[ "$ROUTE_CACHE" == "yes" ]] && echo 1 || echo 0)" "Routes cached"
check_item "$([[ "$SAFE_HTML" == "yes" ]] && echo 1 || echo 0)" "Security helpers: loaded"
check_item "$([[ "$STATUS" -eq 0 ]] && echo 1 || echo 0)" "Git: clean working tree"
check_item "$([[ "$PHP_BLOCK" == "403" ]] && echo 1 || echo 0)" "Uploads: PHP blocked"

echo ""
echo -e "  ${BOLD}Total: ${GREEN}$PASSED_CHECKS${NC}/${BOLD}$TOTAL_CHECKS${NC} checks passed"
echo ""

# Overall status
if [[ "$PASSED_CHECKS" -eq "$TOTAL_CHECKS" ]]; then
    echo -e "${BG_GREEN}${BOLD}                                                                       ${NC}"
    echo -e "${BG_GREEN}${BOLD}   🎉 SEMUA SISTEM NORMAL — WEBSITE HEALTHY & PRODUCTION READY        ${NC}"
    echo -e "${BG_GREEN}${BOLD}                                                                       ${NC}"
elif [[ "$PASSED_CHECKS" -ge $((TOTAL_CHECKS * 8 / 10)) ]]; then
    echo -e "${BG_YELLOW}${BOLD}                                                                       ${NC}"
    echo -e "${BG_YELLOW}${BOLD}   ⚠️  ADA BEBERAPA PERINGATAN — REVIEW DI ATAS                        ${NC}"
    echo -e "${BG_YELLOW}${BOLD}                                                                       ${NC}"
else
    echo -e "${BG_RED}${BOLD}                                                                       ${NC}"
    echo -e "${BG_RED}${BOLD}   ❌ ADA MASALAH SERIUS — PERLU DIPERBAIKI                            ${NC}"
    echo -e "${BG_RED}${BOLD}                                                                       ${NC}"
fi

echo ""
echo -e "  ${CYAN}Report generated: $(date '+%Y-%m-%d %H:%M:%S')${NC}"
echo ""

