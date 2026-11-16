#!/bin/bash
# =============================================================
# Laravel Permission Fixer — SMKMUH1 Blora
# Detects & fixes file permissions for Laravel + Twill
# =============================================================

set -uo pipefail

# ═══ KONFIGURASI ═══
PROJECT_PATH="${1:-}"
DRY_RUN="${2:-yes}"   # yes = show only, no = apply

WEB_USER="smkmu9229"
WEB_GROUP="smkmu9229"

# ═══ WARNA ═══
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
CYAN='\033[0;36m'
BOLD='\033[1m'
NC='\033[0m'

head() { echo -e "\n${BOLD}${BLUE}▶ $1${NC}"; }
ok()   { echo -e "  ${GREEN}✅${NC} $1"; }
warn() { echo -e "  ${YELLOW}⚠️${NC}  $1"; }
fail() { echo -e "  ${RED}❌${NC} $1"; }
info() { echo -e "  ${CYAN}ℹ️${NC}  $1"; }

# ═══ DETEKSI PROJECT PATH ═══
if [[ -z "$PROJECT_PATH" ]]; then
    for p in /home/*/public_html/*/artisan \
             /var/www/*/artisan \
             /home/*/artisan; do
        if [[ -f "$p" ]]; then
            PROJECT_PATH=$(dirname "$p")
            break
        fi
    done
fi

if [[ -z "$PROJECT_PATH" ]] || [[ ! -f "$PROJECT_PATH/artisan" ]]; then
    fail "Project Laravel tidak ditemukan"
    echo "Usage: $0 /path/to/laravel/project [yes|no]"
    exit 1
fi

# ═══ HEADER ═══
echo ""
echo -e "${BOLD}═══════════════════════════════════════════════════════════${NC}"
echo -e "${BOLD}  LARAVEL PERMISSION FIXER${NC}"
echo -e "${BOLD}═══════════════════════════════════════════════════════════${NC}"
echo -e "  Project:  ${CYAN}$PROJECT_PATH${NC}"
echo -e "  User:     ${CYAN}$WEB_USER:$WEB_GROUP${NC}"
echo -e "  Mode:     ${CYAN}$([[ "$DRY_RUN" = "yes" ]] && echo "DRY-RUN (preview)" || echo "APPLY (fix)")${NC}"
echo ""

# ═══ CHECK USER EXISTS ═══
if ! id "$WEB_USER" &>/dev/null; then
    warn "User $WEB_USER tidak ada. Deteksi otomatis..."
    WEB_USER=$(stat -c '%U' "$PROJECT_PATH/storage")
    WEB_GROUP=$(stat -c '%G' "$PROJECT_PATH/storage")
    info "Pakai: $WEB_USER:$WEB_GROUP"
fi

cd "$PROJECT_PATH"

# ═══════════════════════════════════════════════════════════
# 1. DETEKSI MASALAH OWNERSHIP
# ═══════════════════════════════════════════════════════════
head "1. Deteksi Ownership Issues"

OWNER_ISSUES=$(find "$PROJECT_PATH" -maxdepth 3 \
    -not -path "*/vendor/*" \
    -not -path "*/node_modules/*" \
    -not -path "*/.git/*" \
    -not -user "$WEB_USER" 2>/dev/null | wc -l)

if [[ "$OWNER_ISSUES" -eq 0 ]]; then
    ok "Semua file & folder sudah dimiliki $WEB_USER"
else
    warn "$OWNER_ISSUES item bukan milik $WEB_USER"
    
    echo ""
    info "Contoh 10 file/folder dengan ownership salah:"
    find "$PROJECT_PATH" -maxdepth 3 \
        -not -path "*/vendor/*" \
        -not -path "*/node_modules/*" \
        -not -path "*/.git/*" \
        -not -user "$WEB_USER" 2>/dev/null | head -10 | sed 's|^|    |'
    
    echo ""
    info "Root-owned files (yang bikin masalah):"
    find "$PROJECT_PATH" -maxdepth 3 \
        -not -path "*/vendor/*" \
        -not -path "*/node_modules/*" \
        -user root 2>/dev/null | head -10 | sed 's|^|    |'
fi

# ═══════════════════════════════════════════════════════════
# 2. DETEKSI PERMISSION MASALAH
# ═══════════════════════════════════════════════════════════
head "2. Deteksi Permission Issues"

# Cek folder yang seharusnya writable
WRITABLE_DIRS=(
    "storage"
    "storage/app"
    "storage/app/public"
    "storage/app/private"
    "storage/framework"
    "storage/framework/cache"
    "storage/framework/sessions"
    "storage/framework/views"
    "storage/logs"
    "storage/backups"
    "bootstrap/cache"
)

PERM_ISSUES=0
for dir in "${WRITABLE_DIRS[@]}"; do
    full_path="$PROJECT_PATH/$dir"
    if [[ -d "$full_path" ]]; then
        perm=$(stat -c '%a' "$full_path")
        owner=$(stat -c '%U' "$full_path")
        
        if [[ "$perm" != "775" ]] || [[ "$owner" != "$WEB_USER" ]]; then
            warn "$dir → perm: $perm, owner: $owner (harus: 775, $WEB_USER)"
            PERM_ISSUES=$((PERM_ISSUES + 1))
        fi
    else
        warn "$dir — tidak ada"
    fi
done

# Cek folder yang seharusnya readable
READABLE_DIRS=(
    "app"
    "config"
    "database"
    "public"
    "public/build"
    "resources"
    "routes"
    "vendor"
)

for dir in "${READABLE_DIRS[@]}"; do
    full_path="$PROJECT_PATH/$dir"
    if [[ -d "$full_path" ]]; then
        perm=$(stat -c '%a' "$full_path")
        owner=$(stat -c '%U' "$full_path")
        
        if [[ "$owner" != "$WEB_USER" ]]; then
            warn "$dir → owner: $owner (harus: $WEB_USER)"
            PERM_ISSUES=$((PERM_ISSUES + 1))
        fi
    fi
done

# Cek .env permission
if [[ -f "$PROJECT_PATH/.env" ]]; then
    env_perm=$(stat -c '%a' "$PROJECT_PATH/.env")
    if [[ "$env_perm" != "600" ]] && [[ "$env_perm" != "640" ]]; then
        warn ".env → perm: $env_perm (harus: 600 atau 640)"
        PERM_ISSUES=$((PERM_ISSUES + 1))
    else
        ok ".env → perm: $env_perm (aman)"
    fi
fi

if [[ "$PERM_ISSUES" -eq 0 ]]; then
    ok "Semua permission sudah benar"
fi

# ═══════════════════════════════════════════════════════════
# 3. DETEKSI FILE MILIK ROOT (KRITIS)
# ═══════════════════════════════════════════════════════════
head "3. Deteksi File Milik Root (KRITIS)"

ROOT_FILES=$(find "$PROJECT_PATH" -maxdepth 3 \
    -not -path "*/vendor/*" \
    -not -path "*/node_modules/*" \
    -not -path "*/.git/*" \
    -user root 2>/dev/null | wc -l)

if [[ "$ROOT_FILES" -eq 0 ]]; then
    ok "Tidak ada file milik root"
else
    warn "$ROOT_FILES file/folder milik root — WAJIB difix!"
    echo ""
    info "Daftar lengkap:"
    find "$PROJECT_PATH" -maxdepth 3 \
        -not -path "*/vendor/*" \
        -not -path "*/node_modules/*" \
        -user root 2>/dev/null | head -20 | sed 's|^|    |'
fi

# ═══════════════════════════════════════════════════════════
# 4. DETEKSI SYMLINK STORAGE
# ═══════════════════════════════════════════════════════════
head "4. Deteksi Symlink Storage"

if [[ -L "$PROJECT_PATH/public/storage" ]]; then
    target=$(readlink "$PROJECT_PATH/public/storage")
    ok "Symlink storage → $target"
elif [[ -d "$PROJECT_PATH/public/storage" ]]; then
    warn "public/storage adalah folder (bukan symlink)"
else
    warn "public/storage tidak ada — jalankan: php artisan storage:link"
fi

# ═══════════════════════════════════════════════════════════
# PREVIEW FIX
# ═══════════════════════════════════════════════════════════
echo ""
echo -e "${BOLD}═══════════════════════════════════════════════════════════${NC}"
echo -e "${BOLD}  SUMMARY${NC}"
echo -e "${BOLD}═══════════════════════════════════════════════════════════${NC}"
echo ""
echo "  Ownership issues:  $OWNER_ISSUES"
echo "  Permission issues: $PERM_ISSUES"
echo "  Root-owned files:  $ROOT_FILES"
echo ""

# ═══════════════════════════════════════════════════════════
# EXECUTE FIX
# ═══════════════════════════════════════════════════════════
if [[ "$DRY_RUN" = "yes" ]]; then
    echo -e "${YELLOW}${BOLD}  ⚠️  DRY-RUN — Tidak ada perubahan${NC}"
    echo ""
    echo -e "  Untuk apply fix, jalankan:"
    echo -e "    ${BOLD}sudo bash $0 $PROJECT_PATH no${NC}"
    echo ""
    exit 0
fi

echo -e "${RED}${BOLD}  🔧 APPLYING FIXES...${NC}"
echo ""

# ═══ FIX 1: Ownership ═══
head "Fix 1: Chown semua file ke $WEB_USER:$WEB_GROUP"

sudo chown -R "$WEB_USER:$WEB_GROUP" "$PROJECT_PATH" 2>/dev/null
ok "Chown selesai"

# ═══ FIX 2: Directory permissions ═══
head "Fix 2: Set directory permission 755"

find "$PROJECT_PATH" -type d \
    -not -path "*/vendor/*" \
    -not -path "*/node_modules/*" \
    -not -path "*/.git/*" \
    -exec chmod 755 {} \; 2>/dev/null

# Khusus writable dirs → 775
for dir in "${WRITABLE_DIRS[@]}"; do
    full_path="$PROJECT_PATH/$dir"
    if [[ -d "$full_path" ]]; then
        chmod 775 "$full_path"
    fi
done

# Recursively set writable dirs juga 775
chmod -R 775 "$PROJECT_PATH/storage" 2>/dev/null
chmod -R 775 "$PROJECT_PATH/bootstrap/cache" 2>/dev/null

ok "Directory permission di-set"

# ═══ FIX 3: File permissions ═══
head "Fix 3: Set file permission 644"

find "$PROJECT_PATH" -type f \
    -not -path "*/vendor/*" \
    -not -path "*/node_modules/*" \
    -not -path "*/.git/*" \
    -exec chmod 644 {} \; 2>/dev/null

# Artisan file harus executable
chmod +x "$PROJECT_PATH/artisan" 2>/dev/null

ok "File permission di-set"

# ═══ FIX 4: .env permission ═══
head "Fix 4: Set .env permission 600"

if [[ -f "$PROJECT_PATH/.env" ]]; then
    chmod 600 "$PROJECT_PATH/.env"
    chown "$WEB_USER:$WEB_GROUP" "$PROJECT_PATH/.env"
    ok ".env → 600"
fi

# ═══ FIX 5: Storage symlink ═══
head "Fix 5: Storage symlink"

if [[ ! -L "$PROJECT_PATH/public/storage" ]]; then
    sudo -u "$WEB_USER" php "$PROJECT_PATH/artisan" storage:link 2>/dev/null
    ok "Symlink dibuat"
else
    ok "Symlink sudah ada"
fi

# ═══ FIX 6: Clear cache ═══
head "Fix 6: Clear Laravel cache"

cd "$PROJECT_PATH"
sudo -u "$WEB_USER" php artisan optimize:clear 2>/dev/null
ok "Cache cleared"

# ═══════════════════════════════════════════════════════════
# VERIFIKASI
# ═══════════════════════════════════════════════════════════
echo ""
echo -e "${BOLD}═══════════════════════════════════════════════════════════${NC}"
echo -e "${BOLD}  ✅ FIX SELESAI${NC}"
echo -e "${BOLD}═══════════════════════════════════════════════════════════${NC}"
echo ""

head "Verifikasi Ownership"

STILL_ROOT=$(find "$PROJECT_PATH" -maxdepth 3 \
    -not -path "*/vendor/*" \
    -not -path "*/node_modules/*" \
    -user root 2>/dev/null | wc -l)

if [[ "$STILL_ROOT" -eq 0 ]]; then
    ok "Tidak ada file milik root ✅"
else
    warn "Masih ada $STILL_ROOT file milik root"
fi

head "Verifikasi Permission"

for dir in "${WRITABLE_DIRS[@]}"; do
    full_path="$PROJECT_PATH/$dir"
    if [[ -d "$full_path" ]]; then
        perm=$(stat -c '%a' "$full_path")
        owner=$(stat -c '%U' "$full_path")
        if [[ "$perm" == "775" ]] && [[ "$owner" == "$WEB_USER" ]]; then
            ok "$dir → $perm $owner"
        else
            warn "$dir → $perm $owner"
        fi
    fi
done

echo ""
echo -e "${GREEN}${BOLD}  🎉 Selesai!${NC}"
echo ""
echo "  Test website:  curl -I https://smkmuh1blora.sch.id/"
echo "  Test admin:    curl -I https://smkmuh1blora.sch.id/panel-maintenance"
echo "  Test horizon:  curl -I https://smkmuh1blora.sch.id/horizon"
echo ""

