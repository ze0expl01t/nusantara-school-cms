#!/bin/bash
# ═══════════════════════════════════════════════════════════════════
# AUTO PUSH TO GITHUB — SMK Muhammadiyah 1 Blora
# ═══════════════════════════════════════════════════════════════════
# Script ini melakukan:
#   1. Sanity check (git repo, remote, branch)
#   2. Backup safety
#   3. Cek user & group ownership
#   4. Cek file sensitif (.env, credentials)
#   5. Cleanup (node_modules, .bak, cache)
#   6. Code quality (Pint, dd/dump)
#   7. Security scan (Checkpoint)
#   8. Composer audit
#   9. Commit dengan Conventional Commits
#  10. Push ke GitHub
#
# Usage:
#   ./push-to-github.sh                    # Full: cek → clean → commit → push
#   ./push-to-github.sh --dry-run          # Cek saja, tidak push
#   ./push-to-github.sh --no-push          # Cek + clean, tidak push
#   ./push-to-github.sh --skip-perms       # Skip user/group check
#   ./push-to-github.sh --fix-perms        # Auto-fix ownership/permission
#   ./push-to-github.sh --message "feat: xxx"  # Custom commit message
# ═══════════════════════════════════════════════════════════════════

set -e  # Exit on error

# ─────────────────────────────────────────────
# Config
# ─────────────────────────────────────────────
PROJECT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$PROJECT_DIR"

DRY_RUN=false
SKIP_PUSH=false
SKIP_PERMS=false
FIX_PERMS=false
CUSTOM_MESSAGE=""

# Web server user (auto-detect)
WEB_USER=""
WEB_GROUP=""

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
CYAN='\033[0;36m'
MAGENTA='\033[0;35m'
BOLD='\033[1m'
NC='\033[0m'

# ─────────────────────────────────────────────
# Parse arguments
# ─────────────────────────────────────────────
while [[ $# -gt 0 ]]; do
    case $1 in
        --dry-run)      DRY_RUN=true; shift ;;
        --no-push)      SKIP_PUSH=true; shift ;;
        --skip-perms)   SKIP_PERMS=true; shift ;;
        --fix-perms)    FIX_PERMS=true; shift ;;
        --message)      CUSTOM_MESSAGE="$2"; shift 2 ;;
        --help)
            echo "Usage: $0 [OPTIONS]"
            echo ""
            echo "Options:"
            echo "  --dry-run              Cek saja, tidak ubah / push"
            echo "  --no-push              Cek + clean + commit, tidak push"
            echo "  --skip-perms           Skip user/group permission check"
            echo "  --fix-perms            Auto-fix ownership & permission (perlu sudo)"
            echo "  --message \"msg\"        Custom commit message"
            echo "  --help                 Tampilkan bantuan ini"
            exit 0
            ;;
        *) echo "Unknown option: $1"; exit 1 ;;
    esac
done

# ─────────────────────────────────────────────
# Helper functions
# ─────────────────────────────────────────────
print_header() {
    echo ""
    echo -e "${BOLD}${CYAN}═══════════════════════════════════════════════════════${NC}"
    echo -e "${BOLD}${CYAN}  $1${NC}"
    echo -e "${BOLD}${CYAN}═══════════════════════════════════════════════════════${NC}"
    echo ""
}

print_subheader() {
    echo -e "${BOLD}${MAGENTA}  ── $1${NC}"
}

print_step() {
    echo -e "${BOLD}${BLUE}▶ $1${NC}"
}

print_ok() {
    echo -e "  ${GREEN}✅ $1${NC}"
}

print_warn() {
    echo -e "  ${YELLOW}⚠️  $1${NC}"
}

print_error() {
    echo -e "  ${RED}❌ $1${NC}"
}

print_info() {
    echo -e "  ${CYAN}ℹ️  $1${NC}"
}

confirm() {
    if [ "$DRY_RUN" = true ]; then
        return 1
    fi
    read -p "  $1 [y/N] " response
    case "$response" in
        [yY][eE][sS]|[yY]) return 0 ;;
        *) return 1 ;;
    esac
}

# Detect web server user
detect_web_user() {
    # Coba cari dari process list
    if pgrep -f "lsphp|php-fpm|apache|nginx" > /dev/null 2>&1; then
        WEB_USER=$(ps aux | grep -E "lsphp|php-fpm|apache|nginx" | grep -v grep | awk '{print $1}' | head -1)
    fi

    # Fallback: cek config web server
    if [ -z "$WEB_USER" ] && [ -f "/usr/local/lsws/conf/httpd_config.conf" ]; then
        WEB_USER="nobody"
    fi

    # Fallback umum
    if [ -z "$WEB_USER" ]; then
        for user in www-data nobody nginx apache smkmu9229; do
            if id "$user" > /dev/null 2>&1; then
                WEB_USER="$user"
                break
            fi
        done
    fi

    if [ -n "$WEB_USER" ]; then
        WEB_GROUP=$(id -gn "$WEB_USER" 2>/dev/null || echo "$WEB_USER")
    fi
}

# ─────────────────────────────────────────────
# STEP 1: Sanity check
# ─────────────────────────────────────────────
print_header "STEP 1: Sanity Check"

if [ ! -d ".git" ]; then
    print_error "Bukan git repository!"
    exit 1
fi
print_ok "Git repository OK"

if ! git remote get-url origin > /dev/null 2>&1; then
    print_error "Remote 'origin' belum di-set!"
    print_info "Jalankan: git remote add origin git@github.com:username/repo.git"
    exit 1
fi
REMOTE_URL=$(git remote get-url origin)
print_ok "Remote: $REMOTE_URL"

CURRENT_BRANCH=$(git branch --show-current)
if [ -z "$CURRENT_BRANCH" ]; then
    print_error "Tidak di branch manapun (detached HEAD?)"
    exit 1
fi
print_ok "Branch: $CURRENT_BRANCH"

# Info running user
RUNNING_USER=$(whoami)
RUNNING_UID=$(id -u)
print_info "Script dijalankan sebagai: $RUNNING_USER (uid=$RUNNING_UID)"

# ─────────────────────────────────────────────
# STEP 2: Backup (safety)
# ─────────────────────────────────────────────
print_header "STEP 2: Backup Safety"

if [ "$DRY_RUN" = false ]; then
    BACKUP_DIR="/tmp/github-push-backup-$(date +%Y%m%d-%H%M%S)"
    mkdir -p "$BACKUP_DIR"
    cp .gitignore "$BACKUP_DIR/.gitignore.bak" 2>/dev/null || true
    git status > "$BACKUP_DIR/git-status-before.txt"
    git ls-files > "$BACKUP_DIR/tracked-files-before.txt"
    ls -laR --time-style=+%Y-%m-%d > "$BACKUP_DIR/ls-recursive-before.txt" 2>/dev/null || true
    print_ok "Backup tersimpan di: $BACKUP_DIR"
else
    print_info "Skip (dry-run)"
fi

# ─────────────────────────────────────────────
# STEP 3: USER & GROUP CHECK ✨ BARU
# ─────────────────────────────────────────────
print_header "STEP 3: User & Group Ownership Check"

if [ "$SKIP_PERMS" = true ]; then
    print_info "Skip (--skip-perms)"
else
    # 3.1 — Detect web server user
    print_step "Detect web server user..."
    detect_web_user

    if [ -n "$WEB_USER" ]; then
        print_ok "Web server user: $WEB_USER:$WEB_GROUP"
    else
        print_warn "Tidak bisa detect web server user — skip ownership check"
        SKIP_PERMS=true
    fi

    # 3.2 — Cek current user
    if [ "$SKIP_PERMS" = false ]; then
        print_subheader "Current User"
        print_info "You run as: $RUNNING_USER:$RUNNING_GROUP"

        if [ "$RUNNING_USER" = "root" ]; then
            print_warn "Running sebagai ROOT — file yang dibuat akan milik root"
            print_info "Setelah push, jalankan: sudo chown -R $WEB_USER:$WEB_GROUP $PROJECT_DIR"
        fi
    fi

    # 3.3 — Cek project folder ownership
    if [ "$SKIP_PERMS" = false ]; then
        print_subheader "Project Folder Ownership"

        PROJECT_OWNER=$(stat -c '%U' "$PROJECT_DIR" 2>/dev/null || echo "unknown")
        PROJECT_GROUP=$(stat -c '%G' "$PROJECT_DIR" 2>/dev/null || echo "unknown")

        if [ "$PROJECT_OWNER" = "$WEB_USER" ] || [ "$PROJECT_OWNER" = "$RUNNING_USER" ]; then
            print_ok "Project dir: $PROJECT_OWNER:$PROJECT_GROUP"
        else
            print_warn "Project dir: $PROJECT_OWNER:$PROJECT_GROUP (expected: $WEB_USER)"
        fi
    fi

    # 3.4 — Cek folder yang WAJIB writable
    if [ "$SKIP_PERMS" = false ]; then
        print_subheader "Writable Folders"

        WRITABLE_FOLDERS=(
            "storage"
            "storage/app"
            "storage/app/private"
            "storage/app/public"
            "storage/framework"
            "storage/framework/cache"
            "storage/framework/sessions"
            "storage/framework/views"
            "storage/logs"
            "bootstrap/cache"
        )

        WRITABLE_ISSUES=0
        for folder in "${WRITABLE_FOLDERS[@]}"; do
            if [ -d "$folder" ]; then
                if [ -w "$folder" ]; then
                    # Cek permission minimal
                    PERM=$(stat -c '%a' "$folder" 2>/dev/null)
                    if [[ "$PERM" =~ ^7 ]] || [[ "$PERM" =~ ^6 ]]; then
                        print_ok "$folder (perm: $PERM) writable"
                    else
                        print_warn "$folder (perm: $PERM) — mungkin perlu chmod 775"
                        WRITABLE_ISSUES=$((WRITABLE_ISSUES + 1))
                    fi
                else
                    print_error "$folder NOT writable!"
                    WRITABLE_ISSUES=$((WRITABLE_ISSUES + 1))
                fi
            fi
        done

        if [ "$WRITABLE_ISSUES" -eq 0 ]; then
            print_ok "Semua folder writable OK"
        fi
    fi

    # 3.5 — Cek file yang dimiliki user ANEH (root, atau bukan web_user)
    if [ "$SKIP_PERMS" = false ]; then
        print_subheader "Suspicious File Ownership"

        # Cari file yang dimiliki root (selain .git)
        ROOT_FILES=$(find . -maxdepth 3 -user root -not -path "./.git/*" -not -path "./vendor/*" -not -path "./node_modules/*" 2>/dev/null | head -10)

        if [ -n "$ROOT_FILES" ]; then
            print_warn "File milik root terdeteksi:"
            echo "$ROOT_FILES" | sed 's/^/     /'
            print_info "Fix: sudo chown -R $WEB_USER:$WEB_GROUP $PROJECT_DIR"
        else
            print_ok "Tidak ada file milik root"
        fi

        # Cari file yang dimiliki user berbeda dari web_user (sample)
        if [ -n "$WEB_USER" ]; then
            OTHER_FILES=$(find . -maxdepth 2 -type f -not -user "$WEB_USER" -not -user "$RUNNING_USER" -not -path "./.git/*" -not -path "./vendor/*" -not -path "./node_modules/*" 2>/dev/null | head -10)

            if [ -n "$OTHER_FILES" ]; then
                print_warn "File dengan owner berbeda dari $WEB_USER:"
                echo "$OTHER_FILES" | sed 's/^/     /'
            else
                print_ok "Semua file dimiliki $WEB_USER atau $RUNNING_USER"
            fi
        fi
    fi

    # 3.6 — Cek .git ownership
    if [ "$SKIP_PERMS" = false ]; then
        print_subheader ".git Folder"

        GIT_OWNER=$(stat -c '%U' ".git" 2>/dev/null || echo "unknown")
        GIT_PERM=$(stat -c '%a' ".git" 2>/dev/null || echo "unknown")

        if [ "$GIT_OWNER" = "$RUNNING_USER" ] || [ "$GIT_OWNER" = "$WEB_USER" ]; then
            print_ok ".git owner: $GIT_OWNER (perm: $GIT_PERM)"
        else
            print_warn ".git owner: $GIT_OWNER (expected: $RUNNING_USER)"
        fi
    fi

    # 3.7 — Cek file executable yang mencurigakan
    if [ "$SKIP_PERMS" = false ]; then
        print_subheader "Executable Files (Non-Script)"

        EXEC_FILES=$(find . -maxdepth 2 -type f -perm -111 -not -name "*.sh" -not -name "artisan" -not -name "*.phar" -not -path "./.git/*" -not -path "./vendor/*" -not -path "./node_modules/*" 2>/dev/null | head -5)

        if [ -n "$EXEC_FILES" ]; then
            print_warn "File executable tidak biasa:"
            echo "$EXEC_FILES" | sed 's/^/     /'
        else
            print_ok "Tidak ada executable mencurigakan"
        fi
    fi

    # 3.8 — Auto-fix kalau diminta
    if [ "$FIX_PERMS" = true ] && [ "$DRY_RUN" = false ]; then
        print_subheader "Auto-Fix Permission"

        if [ "$RUNNING_USER" != "root" ]; then
            print_warn "Fix permission butuh sudo — minta password:"
            if sudo -v 2>/dev/null; then
                print_step "Fixing ownership..."
                sudo chown -R "$WEB_USER:$WEB_GROUP" "$PROJECT_DIR" 2>/dev/null || print_warn "Gagal chown"
                print_ok "Ownership fixed → $WEB_USER:$WEB_GROUP"

                print_step "Fixing permission..."
                sudo find "$PROJECT_DIR" -type d -exec chmod 755 {} \; 2>/dev/null || true
                sudo find "$PROJECT_DIR" -type f -exec chmod 644 {} \; 2>/dev/null || true
                sudo chmod -R 775 storage bootstrap/cache 2>/dev/null || true
                sudo chmod +x artisan 2>/dev/null || true
                print_ok "Permission fixed"
            else
                print_error "Sudo ditolak"
            fi
        else
            # Sudah root
            print_step "Fixing ownership (as root)..."
            chown -R "$WEB_USER:$WEB_GROUP" "$PROJECT_DIR" 2>/dev/null || print_warn "Gagal chown"
            find "$PROJECT_DIR" -type d -exec chmod 755 {} \; 2>/dev/null || true
            find "$PROJECT_DIR" -type f -exec chmod 644 {} \; 2>/dev/null || true
            chmod -R 775 storage bootstrap/cache 2>/dev/null || true
            chmod +x artisan 2>/dev/null || true
            print_ok "Permission & ownership fixed"
        fi
    fi
fi

# ─────────────────────────────────────────────
# STEP 4: Cek file sensitif
# ─────────────────────────────────────────────
print_header "STEP 4: Cek File Sensitif"

SENSITIVE_FOUND=false

# Cek .env
if git ls-files | grep -q "^\.env$"; then
    print_error ".env KE-TRACK!"
    SENSITIVE_FOUND=true
else
    print_ok ".env tidak ke-track"
fi

# Cek .env.example
if [ ! -f ".env.example" ]; then
    print_warn ".env.example tidak ada — akan dibuat"
else
    print_ok ".env.example ada"
fi

# Cek node_modules
NM_COUNT=$(git ls-files | grep -c "^node_modules/" 2>/dev/null | head -1)
NM_COUNT=${NM_COUNT:-0}
if [ "$NM_COUNT" -gt 0 ]; then
    print_error "node_modules/ KE-TRACK! ($NM_COUNT file)"
    SENSITIVE_FOUND=true
else
    print_ok "node_modules/ tidak ke-track"
fi

# Cek vendor
V_COUNT=$(git ls-files | grep -c "^vendor/" 2>/dev/null | head -1)
V_COUNT=${V_COUNT:-0}
if [ "$V_COUNT" -gt 0 ]; then
    print_error "vendor/ KE-TRACK! ($V_COUNT file)"
    SENSITIVE_FOUND=true
else
    print_ok "vendor/ tidak ke-track"
fi

# Cek bootstrap/cache
BC_COUNT=$(git ls-files | grep -c "bootstrap/cache/.*\.php" 2>/dev/null | head -1)
BC_COUNT=${BC_COUNT:-0}
if [ "$BC_COUNT" -gt 0 ]; then
    print_warn "bootstrap/cache/ ke-track ($BC_COUNT file) — akan di-clean"
else
    print_ok "bootstrap/cache/ bersih"
fi

# Cek .bak files
BAK_COUNT=$(git ls-files | grep -c "\.bak" 2>/dev/null | head -1)
BAK_COUNT=${BAK_COUNT:-0}
if [ "$BAK_COUNT" -gt 0 ]; then
    print_warn ".bak files ke-track ($BAK_COUNT file) — akan di-clean"
else
    print_ok "Tidak ada .bak files"
fi

# Cek credentials di code
CRED_FOUND=$(grep -rn "password.*=.*['\"][^'\"]\{8,\}['\"]" app/ config/ routes/ --include="*.php" 2>/dev/null | grep -v "example\|placeholder\|xxx" | head -5)
if [ -n "$CRED_FOUND" ]; then
    print_warn "Mungkin ada hardcoded credentials — review manual"
    echo "$CRED_FOUND" | sed 's/^/     /'
else
    print_ok "Tidak ada hardcoded credentials mencurigakan"
fi

if [ "$SENSITIVE_FOUND" = true ] && [ "$DRY_RUN" = false ]; then
    echo ""
    if ! confirm "Ada file sensitif ke-track. Lanjut cleanup?"; then
        print_error "Abort. Fix manual dulu."
        exit 1
    fi
fi

# ─────────────────────────────────────────────
# STEP 5: Cleanup
# ─────────────────────────────────────────────
print_header "STEP 5: Cleanup"

if [ "$DRY_RUN" = false ]; then
    # Hapus .bak files dari disk
    BAK_DELETED=$(find . -type f \( -name "*.bak" -o -name "*.bak-*" \) -not -path "./vendor/*" -not -path "./node_modules/*" -delete -print 2>/dev/null | wc -l)
    print_ok "Hapus $BAK_DELETED file .bak dari disk"

    # Remove node_modules dari tracking
    if [ "$NM_COUNT" -gt 0 ]; then
        git ls-files | grep "^node_modules/" | xargs git rm --cached -q 2>/dev/null || true
        print_ok "Remove node_modules/ dari tracking"
    fi

    # Remove bootstrap/cache dari tracking
    if [ "$BC_COUNT" -gt 0 ]; then
        git ls-files | grep "bootstrap/cache/.*\.php" | xargs git rm --cached -q 2>/dev/null || true
        print_ok "Remove bootstrap/cache/ dari tracking"
    fi

    # Pastikan .gitignore lengkap
    GITIGNORE_ADDED=0
    for entry in "node_modules/" ".env" "/vendor" "bootstrap/cache/*.php" "*.bak" "*.bak-*" "storage/logs/" ".DS_Store"; do
        if ! grep -qF "$entry" .gitignore 2>/dev/null; then
            echo "$entry" >> .gitignore
            GITIGNORE_ADDED=$((GITIGNORE_ADDED + 1))
        fi
    done
    if [ "$GITIGNORE_ADDED" -gt 0 ]; then
        print_ok "Update .gitignore (+$GITIGNORE_ADDED entries)"
    else
        print_ok ".gitignore sudah lengkap"
    fi

    # Buat .env.example kalau belum ada
    if [ ! -f ".env.example" ] && [ -f ".env" ]; then
        cp .env .env.example
        sed -i 's/^DB_PASSWORD=.*/DB_PASSWORD=/' .env.example
        sed -i 's/^DB_USERNAME=.*/DB_USERNAME=/' .env.example
        sed -i 's/^APP_KEY=.*/APP_KEY=/' .env.example
        sed -i 's/^MAIL_PASSWORD=.*/MAIL_PASSWORD=/' .env.example
        sed -i 's/^AWS_ACCESS_KEY_ID=.*/AWS_ACCESS_KEY_ID=/' .env.example
        sed -i 's/^AWS_SECRET_ACCESS_KEY=.*/AWS_SECRET_ACCESS_KEY=/' .env.example
        sed -i 's/^REDIS_PASSWORD=.*/REDIS_PASSWORD=null/' .env.example
        print_ok "Buat .env.example dari .env"
    fi
else
    print_info "Skip (dry-run)"
fi

# ─────────────────────────────────────────────
# STEP 6: Code Quality
# ─────────────────────────────────────────────
print_header "STEP 6: Code Quality"

DD_COUNT=$(grep -rn "\bdd(\|\bdump(\|\bvar_dump(" app/ routes/ config/ --include="*.php" 2>/dev/null | wc -l)
if [ "$DD_COUNT" -gt 0 ]; then
    print_warn "$DD_COUNT file punya dd()/dump()"
else
    print_ok "Tidak ada dd()/dump() tertinggal"
fi

if [ -f "./vendor/bin/pint" ]; then
    print_step "Menjalankan Laravel Pint..."
    if [ "$DRY_RUN" = false ]; then
        if ./vendor/bin/pint --test > /dev/null 2>&1; then
            print_ok "Pint: code sudah sesuai standard"
        else
            print_warn "Pint: ada file yang perlu di-format — auto-fix"
            ./vendor/bin/pint > /dev/null 2>&1 && print_ok "Pint: auto-fix selesai"
        fi
    else
        ./vendor/bin/pint --test > /dev/null 2>&1 && print_ok "Pint: OK" || print_warn "Pint: butuh format"
    fi
else
    print_warn "Laravel Pint tidak terinstall"
fi

# ─────────────────────────────────────────────
# STEP 7: Security Scan (Checkpoint)
# ─────────────────────────────────────────────
print_header "STEP 7: Security Scan (Checkpoint)"

if php artisan list 2>/dev/null | grep -q "checkpoint:scan"; then
    print_step "Menjalankan checkpoint:scan..."
    SCAN_OUTPUT=$(php artisan checkpoint:scan --json 2>/dev/null || echo "[]")
    FAIL_COUNT=$(echo "$SCAN_OUTPUT" | jq '[.[] | select(.status=="fail")] | length' 2>/dev/null || echo "0")

    if [ "$FAIL_COUNT" -gt 0 ]; then
        print_warn "$FAIL_COUNT check FAIL"
        echo "$SCAN_OUTPUT" | jq -r '.[] | select(.status=="fail") | "     → \(.check): \(.message)"' 2>/dev/null
        if ! confirm "Ada security issue. Lanjut push?"; then
            print_error "Abort."
            exit 1
        fi
    else
        print_ok "Checkpoint: semua PASS ✅"
    fi
else
    print_warn "Checkpoint tidak terinstall"
fi

# ─────────────────────────────────────────────
# STEP 8: Composer Audit
# ─────────────────────────────────────────────
print_header "STEP 8: Composer Audit"

AUDIT_OUTPUT=$(composer audit --no-dev 2>&1 || true)
if echo "$AUDIT_OUTPUT" | grep -qi "no vulnerabilities\|0 vulnerabilities"; then
    print_ok "Composer: tidak ada vulnerabilities"
elif echo "$AUDIT_OUTPUT" | grep -qi "vulnerabilit"; then
    print_warn "Composer: ada vulnerabilities"
    echo "$AUDIT_OUTPUT" | head -20 | sed 's/^/     /'
else
    print_info "Composer audit selesai"
fi

# ─────────────────────────────────────────────
# STEP 9: Git Status
# ─────────────────────────────────────────────
print_header "STEP 9: Git Status"

git status --short | head -30
CHANGED_FILES=$(git status --short | wc -l)
echo ""
print_info "Total perubahan: $CHANGED_FILES file"

if [ "$CHANGED_FILES" -eq 0 ]; then
    print_warn "Tidak ada perubahan untuk di-commit"
    exit 0
fi

# ─────────────────────────────────────────────
# STEP 10: Commit
# ─────────────────────────────────────────────
print_header "STEP 10: Commit"

if [ "$DRY_RUN" = true ]; then
    print_info "Skip (dry-run)"
else
    git add -A

    echo ""
    print_step "Cek file besar..."
    LARGE_FILES=$(git diff --cached --name-only | xargs -I {} sh -c 'test -f "{}" && [ $(stat -c%s "{}" 2>/dev/null || echo 0) -gt 5242880 ] && echo "{}"' 2>/dev/null | head -10)
    if [ -n "$LARGE_FILES" ]; then
        print_warn "File > 5MB terdeteksi:"
        echo "$LARGE_FILES" | sed 's/^/     /'
        if ! confirm "Lanjut commit dengan file besar?"; then
            git reset
            exit 1
        fi
    else
        print_ok "Tidak ada file > 5MB"
    fi

    if [ -z "$CUSTOM_MESSAGE" ]; then
        CUSTOM_MESSAGE="chore: update project (auto-push $(date +%Y-%m-%d))"
    fi

    echo ""
    print_info "Commit message: $CUSTOM_MESSAGE"
    if confirm "Lanjut commit?"; then
        git commit -m "$CUSTOM_MESSAGE"
        print_ok "Commit berhasil"
    else
        print_error "Commit dibatalkan"
        exit 1
    fi
fi

# ─────────────────────────────────────────────
# STEP 11: Push
# ─────────────────────────────────────────────
print_header "STEP 11: Push to GitHub"

if [ "$DRY_RUN" = true ] || [ "$SKIP_PUSH" = true ]; then
    print_info "Skip push (dry-run atau --no-push)"
    print_info "Untuk push manual: git push origin $CURRENT_BRANCH"
else
    print_info "Remote: $REMOTE_URL"
    print_info "Branch: $CURRENT_BRANCH"

    if confirm "Push ke origin/$CURRENT_BRANCH sekarang?"; then
        print_step "Pushing..."
        if git push origin "$CURRENT_BRANCH" 2>&1; then
            print_ok "Push berhasil! 🎉"

            echo ""
            print_step "Commit terakhir:"
            git log -1 --oneline | sed 's/^/     /'

            echo ""
            print_step "Langkah selanjutnya:"
            echo "     • Cek di GitHub: $REMOTE_URL"
            echo "     • Verify README tampil"

            # Reminder permission
            if [ -n "$WEB_USER" ] && [ "$RUNNING_USER" = "root" ]; then
                echo ""
                print_warn "Reminder: Fix ownership karena running sebagai root"
                print_info "sudo chown -R $WEB_USER:$WEB_GROUP $PROJECT_DIR"
            fi
        else
            print_error "Push gagal!"
            print_info "Kemungkinan penyebab:"
            print_info "  • Permission denied → cek SSH key"
            print_info "  • Rejected → git pull --rebase dulu"
            print_info "  • Large file → cek dengan 'git ls-files | xargs du -h | sort -rh | head'"
            exit 1
        fi
    else
        print_warn "Push dibatalkan"
        print_info "Untuk push manual: git push origin $CURRENT_BRANCH"
    fi
fi

# ─────────────────────────────────────────────
# Summary
# ─────────────────────────────────────────────
print_header "✅ SELESAI"

print_info "Repo: $REMOTE_URL"
print_info "Branch: $CURRENT_BRANCH"
print_info "Commit: $(git log -1 --oneline)"
print_info "Running as: $RUNNING_USER"
[ -n "$WEB_USER" ] && print_info "Web server user: $WEB_USER:$WEB_GROUP"

if [ "$DRY_RUN" = true ]; then
    echo ""
    print_info "Ini dry-run — tidak ada perubahan yang di-push"
    print_info "Untuk push beneran: ./push-to-github.sh"
fi

echo ""
