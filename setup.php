<?php
if (php_sapi_name() !== 'cli') exit("Jalankan via terminal: php setup.php\n");
echo "\n=== AUTO SETUP PORTAL SEKOLAH (LARAVEL + TWILL CMS) ===\n";
if (!file_exists('.env') && file_exists('.env.example')) { copy('.env.example', '.env'); echo "[1/5] .env dibuat\n"; }
if (!file_exists('vendor/autoload.php')) passthru('composer install --no-interaction --prefer-dist');
if (preg_match('/^APP_KEY=\s*$/m', file_get_contents('.env'))) passthru('php artisan key:generate');
if (!file_exists('public/storage')) passthru('php artisan storage:link');
passthru(is_dir('node_modules') ? 'npm run build' : 'npm install && npm run build');
passthru('php artisan optimize:clear');
echo "\nSelesai! Sesuaikan DB di .env lalu jalankan: php artisan migrate && php artisan twill:superadmin\n";
