<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

class DiagnoseMenuLink extends Command
{
    protected $signature = 'menu:diagnose {--model=App\\Models\\MenuLink : Model class}';

    protected $description = 'Diagnostik lengkap nested menu Twill';

    protected string $outputFile;

    protected array $report = [];

    protected string $modelClass;

    protected string $table;

    protected $model;

    public function handle(): int
    {
        $this->outputFile = storage_path('logs/menu_diagnose_'.date('Ymd_His').'.txt');
        $this->report = [];
        $this->modelClass = $this->option('model');

        $this->rLine('');
        $this->rLine('🔍 MENU LINK DIAGNOSTIC — '.now()->toDateTimeString());
        $this->rLine('PHP: '.PHP_VERSION.' | Laravel: '.app()->version());

        // 1. Model
        if (! $this->checkModel()) {
            return $this->saveAndExit(self::FAILURE);
        }

        // 2. Traits
        $this->checkTraits();

        // 3. Tabel & Kolom
        $this->checkTable();

        // 4. Relasi
        $this->checkRelations();

        // 5. Fillable & atribut
        $this->checkFillable();

        // 6. Data di DB
        $this->checkData();

        // 7. Dump data
        $this->dumpData();

        // 8. Query seperti di view
        $this->checkViewQuery();

        // 9. Query tanpa filter published
        $this->checkQueryNoPublished();

        // 10. Cek relasi children manual
        $this->checkChildrenRelation();

        // 11. Form admin Twill
        $this->checkTwillForm();

        // 12. Navigation config
        $this->checkNavigation();

        // 13. Route admin
        $this->checkRoutes();

        // 14. Controller
        $this->checkController();

        // 15. Migration
        $this->checkMigration();

        // 16. Blade view yang memakai menuItems
        $this->checkBladeUsage();

        // 17. Kesimpulan
        $this->printConclusions();

        return $this->saveAndExit(self::SUCCESS);
    }

    // ═══════════════════════════════════════════════════════════════
    // CHECKERS
    // ═══════════════════════════════════════════════════════════════

    protected function checkModel(): bool
    {
        $this->rSection('1. MODEL CLASS');

        if (! class_exists($this->modelClass)) {
            $this->rFail("Model [{$this->modelClass}] TIDAK DITEMUKAN");

            return false;
        }

        $this->rOk("✓ Model: {$this->modelClass}");
        $this->model = new $this->modelClass;
        $this->table = $this->model->getTable();
        $this->rInfo("  Tabel: {$this->table}");

        $file = (new \ReflectionClass($this->modelClass))->getFileName();
        $this->rInfo('  File: '.str_replace(base_path().'/', '', $file));

        return true;
    }

    protected function checkTraits(): void
    {
        $this->rSection('2. TRAITS');

        $traits = class_uses_recursive($this->modelClass);
        $checks = [
            'A17\Twill\Models\Behaviors\HasNesting' => true,   // WAJIB
            'A17\Twill\Models\Behaviors\HasPosition' => true,   // WAJIB
            'A17\Twill\Models\Behaviors\HasRelated' => false,
            'A17\Twill\Models\Behaviors\HasTranslation' => false,
            'A17\Twill\Models\Behaviors\HasMedias' => false,
            'A17\Twill\Models\Behaviors\HasFiles' => false,
            'A17\Twill\Models\Behaviors\HasSlug' => false,
            'A17\Twill\Models\Behaviors\HasRevisions' => false,
        ];

        foreach ($checks as $trait => $critical) {
            $short = class_basename($trait);
            if (in_array($trait, $traits)) {
                $this->rOk("✓ {$short}");
            } else {
                if ($critical) {
                    $this->rFail("❌ {$short} — KRITIS (WAJIB)");
                } else {
                    $this->rWarn("·  {$short} — tidak dipakai (opsional)");
                }
            }
        }

        // Cek interface Sortable
        if (in_array('A17\Twill\Models\Behaviors\Sortable', class_implements($this->modelClass))) {
            $this->rOk('✓ implements Sortable');
        } else {
            $this->rWarn('·  tidak implements Sortable');
        }

        // Cek apakah HasTranslation terpakai tapi tidak ada tabel translations
        if (in_array('A17\Twill\Models\Behaviors\HasTranslation', $traits)) {
            $transTable = $this->table.'_translations';
            if (! Schema::hasTable($transTable)) {
                $this->rFail("❌ Pakai HasTranslation tapi tabel `{$transTable}` TIDAK ADA!");
            } else {
                $this->rOk("✓ Tabel translations ada: {$transTable}");
            }
        }
    }

    protected function checkTable(): void
    {
        $this->rSection('3. STRUKTUR TABEL');

        if (! Schema::hasTable($this->table)) {
            $this->rFail("❌ Tabel `{$this->table}` TIDAK ADA!");

            return;
        }
        $this->rOk("✓ Tabel ada: {$this->table}");

        $required = ['id', 'title', 'parent_id', 'position', 'published'];
        $optional = ['deleted_at', 'created_at', 'updated_at', 'description', 'url', 'browser_name'];

        $this->rInfo('Kolom wajib:');
        foreach ($required as $col) {
            if (Schema::hasColumn($this->table, $col)) {
                $this->rOk("  ✓ {$col}");
            } else {
                $this->rFail("  ❌ {$col} — HILANG!");
            }
        }

        $this->rInfo('Kolom opsional:');
        foreach ($optional as $col) {
            if (Schema::hasColumn($this->table, $col)) {
                $this->rOk("  ✓ {$col}");
            }
        }

        // Dump semua kolom dari information_schema (lebih reliable)
        try {
            $cols = DB::select('
                SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE, COLUMN_DEFAULT
                FROM information_schema.COLUMNS
                WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?
                ORDER BY ORDINAL_POSITION
            ', [$this->table]);

            $this->rInfo("Semua kolom ({$this->table}) via information_schema:");
            foreach ($cols as $c) {
                $nullable = $c->IS_NULLABLE === 'YES' ? 'NULL' : 'NOT NULL';
                $default = $c->COLUMN_DEFAULT !== null ? "DEFAULT '{$c->COLUMN_DEFAULT}'" : '';
                $this->rInfo("  - {$c->COLUMN_NAME} : {$c->DATA_TYPE} {$nullable} {$default}");
            }
        } catch (\Throwable $e) {
            $this->rWarn('Gagal info_schema: '.$e->getMessage());
        }
    }

    protected function checkRelations(): void
    {
        $this->rSection('4. RELASI MODEL');

        foreach (['children', 'parent'] as $rel) {
            if (! method_exists($this->model, $rel)) {
                $this->rFail("❌ Method `{$rel}()` TIDAK ADA");

                continue;
            }
            try {
                $relation = $this->model->{$rel}();
                $type = class_basename($relation);
                $related = get_class($relation->getRelated());

                $foreignKey = method_exists($relation, 'getForeignKeyName')
                    ? $relation->getForeignKeyName() : '-';
                $ownerKey = method_exists($relation, 'getOwnerKey')
                    ? $relation->getOwnerKey() : '-';

                $this->rOk("✓ {$rel}() → {$type}");
                $this->rInfo("    Related: {$related}");
                if ($rel === 'children') {
                    $this->rInfo("    FK: {$foreignKey}, OwnerKey: {$ownerKey}");
                }
            } catch (\Throwable $e) {
                $this->rFail("❌ {$rel}() error: ".$e->getMessage());
            }
        }
    }

    protected function checkFillable(): void
    {
        $this->rSection('5. FILLABLE & ATRIBUT');

        $fillable = $this->model->getFillable();
        $this->rInfo('Fillable:');
        foreach ($fillable as $f) {
            $this->rInfo("  - {$f}");
        }

        $must = ['parent_id', 'position', 'published', 'title'];
        $this->rInfo('Cek wajib:');
        foreach ($must as $f) {
            if (in_array($f, $fillable)) {
                $this->rOk("  ✓ {$f} bisa di-mass-assign");
            } else {
                $this->rWarn("  ⚠ {$f} TIDAK di fillable — set parent manual akan gagal");
            }
        }

        if (method_exists($this->model, 'getTranslatedAttributes')) {
            $trans = $this->model->getTranslatedAttributes();
            if (! empty($trans)) {
                $this->rInfo('Translated attributes:');
                foreach ($trans as $t) {
                    $this->rInfo("  - {$t}");
                }
            }
        }
    }

    protected function checkData(): void
    {
        $this->rSection('6. DATA DI DATABASE');

        $total = DB::table($this->table)->whereNull('deleted_at')->count();
        $trashed = Schema::hasColumn($this->table, 'deleted_at')
            ? DB::table($this->table)->whereNotNull('deleted_at')->count() : 0;
        $parents = DB::table($this->table)->whereNull('parent_id')->whereNull('deleted_at')->count();
        $children = DB::table($this->table)->whereNotNull('parent_id')->whereNull('deleted_at')->count();

        $this->rInfo("Total (aktif): {$total}");
        if ($trashed > 0) {
            $this->rInfo("Total (soft-deleted): {$trashed}");
        }
        $this->rInfo("Parent (parent_id NULL): {$parents}");
        $this->rInfo("Child (parent_id NOT NULL): {$children}");

        if ($children === 0) {
            $this->rFail('❌ TIDAK ADA CHILD — nested menu tidak akan pernah muncul!');
        } else {
            $this->rOk("✓ Ada {$children} child terdeteksi");
        }

        if (Schema::hasColumn($this->table, 'published')) {
            $pub = DB::table($this->table)->where('published', 1)->whereNull('deleted_at')->count();
            $draft = DB::table($this->table)->where('published', 0)->whereNull('deleted_at')->count();
            $this->rInfo("Published: {$pub} | Draft: {$draft}");

            $draftParents = DB::table($this->table)->whereNull('parent_id')
                ->where('published', 0)->whereNull('deleted_at')->count();
            $draftChildren = DB::table($this->table)->whereNotNull('parent_id')
                ->where('published', 0)->whereNull('deleted_at')->count();

            if ($draftParents > 0) {
                $this->rWarn("⚠ {$draftParents} parent DRAFT — tidak muncul karena scope published()");
            }
            if ($draftChildren > 0) {
                $this->rWarn("⚠ {$draftChildren} child DRAFT — disembunyikan oleh ->published()");
            }
        }
    }

    protected function dumpData(): void
    {
        $this->rSection('7. DUMP SEMUA DATA');

        try {
            $cols = ['id', 'parent_id', 'position', 'published'];
            if (Schema::hasColumn($this->table, 'title')) {
                $cols[] = 'title';
            }
            if (Schema::hasColumn($this->table, 'deleted_at')) {
                $cols[] = 'deleted_at';
            }

            $rows = DB::table($this->table)
                ->select($cols)
                ->orderByRaw('parent_id IS NULL DESC')
                ->orderBy('parent_id')
                ->orderBy('position')
                ->get();

            if ($rows->isEmpty()) {
                $this->rWarn('⚠ Tabel KOSONG');

                return;
            }

            foreach ($rows as $r) {
                $parent = $r->parent_id ?? 'NULL';
                $pos = $r->position ?? '-';
                $pub = isset($r->published) ? ($r->published ? 'PUB' : 'DRAFT') : '?';
                $del = (isset($r->deleted_at) && $r->deleted_at) ? ' [DELETED]' : '';
                $indent = $r->parent_id ? '  └── ' : '';
                $title = $r->title ?? '(no title)';
                $this->rInfo(sprintf(
                    '%sID:%-4s parent:%-6s pos:%-4s [%s] %s%s',
                    $indent, $r->id, $parent, $pos, $pub, $title, $del
                ));
            }
        } catch (\Throwable $e) {
            $this->rFail('Dump error: '.$e->getMessage());
        }
    }

    protected function checkViewQuery(): void
    {
        $this->rSection('8. QUERY SEPERTI DI VIEW');

        try {
            $menuItems = $this->modelClass::published()
                ->whereNull('parent_id')
                ->orderBy('position')
                ->with(['children' => fn ($q) => $q->published()->orderBy('position')])
                ->get();

            $this->rOk('✓ Query jalan. Hasil: '.$menuItems->count().' parent');

            foreach ($menuItems as $item) {
                $cc = $item->children ? $item->children->count() : 0;
                $mark = $cc > 0 ? '✓' : '✗';
                $this->rInfo("  {$mark} [{$item->id}] '{$item->title}' → {$cc} anak");
                if ($item->children) {
                    foreach ($item->children as $c) {
                        $this->rInfo("        └── [{$c->id}] '{$c->title}'");
                    }
                }
            }
        } catch (\Throwable $e) {
            $this->rFail('Query ERROR: '.$e->getMessage());
            $this->rInfo('  '.$e->getFile().':'.$e->getLine());
        }
    }

    protected function checkQueryNoPublished(): void
    {
        $this->rSection('9. QUERY TANPA FILTER PUBLISHED');

        try {
            $all = $this->modelClass::query()
                ->whereNull('parent_id')
                ->orderBy('position')
                ->with(['children' => fn ($q) => $q->orderBy('position')])
                ->get();

            $this->rInfo('Total parent: '.$all->count());
            foreach ($all as $item) {
                $cc = $item->children ? $item->children->count() : 0;
                $this->rInfo("  [{$item->id}] '{$item->title}' → {$cc} anak");
            }
        } catch (\Throwable $e) {
            $this->rWarn('Error: '.$e->getMessage());
        }
    }

    protected function checkChildrenRelation(): void
    {
        $this->rSection('10. TEST RELASI children() MANUAL');

        try {
            $withChild = DB::table($this->table)
                ->whereNotNull('parent_id')
                ->whereNull('deleted_at')
                ->first();

            if (! $withChild) {
                $this->rWarn('Tidak ada child di DB, skip.');

                return;
            }

            $parent = $this->modelClass::find($withChild->parent_id);
            if (! $parent) {
                $this->rFail("Parent ID {$withChild->parent_id} tidak ditemukan");

                return;
            }

            $this->rInfo("Parent: [{$parent->id}] {$parent->title}");
            $this->rInfo('Children query SQL:');
            $this->rInfo('  '.$parent->children()->toSql());

            $kids = $parent->children()->get();
            $this->rInfo('Hasil relasi children(): '.$kids->count());
            foreach ($kids as $k) {
                $this->rInfo("  └── [{$k->id}] {$k->title}");
            }
        } catch (\Throwable $e) {
            $this->rFail('Error: '.$e->getMessage());
        }
    }

    protected function checkTwillForm(): void
    {
        $this->rSection('11. FORM ADMIN TWILL');

        $candidates = [
            'resources/views/twill/menuLinks/form.blade.php',
            'resources/views/twill/menu_links/form.blade.php',
            'resources/views/twill/MenuLinks/form.blade.php',
            'resources/views/twill/MenuLink/form.blade.php',
            'resources/views/twill/menuLinks/form.blade.php',
        ];

        $found = null;
        foreach ($candidates as $path) {
            if (File::exists(base_path($path))) {
                $found = $path;
                break;
            }
        }

        // Kalau tidak ada di kandidat, cari otomatis
        if (! $found && File::isDirectory(base_path('resources/views/twill'))) {
            $dirs = File::directories(base_path('resources/views/twill'));
            foreach ($dirs as $dir) {
                if (stripos(basename($dir), 'menu') !== false) {
                    $formFile = $dir.'/form.blade.php';
                    if (File::exists($formFile)) {
                        $found = 'resources/views/twill/'.basename($dir).'/form.blade.php';
                        break;
                    }
                }
            }
        }

        if (! $found) {
            $this->rFail('❌ Form admin TIDAK DITEMUKAN!');
            $this->rInfo('  Kemungkinan folder di resources/views/twill/:');
            if (File::isDirectory(base_path('resources/views/twill'))) {
                foreach (File::directories(base_path('resources/views/twill')) as $d) {
                    $this->rInfo('    - '.basename($d));
                }
            }

            return;
        }

        $this->rOk("✓ Form ditemukan: {$found}");
        $content = File::get(base_path($found));

        // Cek indikator field parent/nesting
        $indicators = [
            'parent_id' => 'Field parent_id',
            'twill::input' => 'Input standar',
            'browser' => 'Field browser (HasRelated)',
            'nestable' => 'Field nestable',
            'related' => 'Field related',
            'position' => 'Field position',
            'publish' => 'Toggle publish',
        ];

        $this->rInfo('Indikator di form:');
        foreach ($indicators as $key => $label) {
            if (stripos($content, $key) !== false) {
                $this->rOk("  ✓ Ada: {$label}");
            }
        }

        // Cek khusus parent/nesting
        $hasParentField = stripos($content, 'parent') !== false
            || stripos($content, 'nest') !== false
            || stripos($content, 'tree') !== false;

        if ($hasParentField) {
            $this->rOk('✓ Form kemungkinan punya UI parent/nesting');
        } else {
            $this->rFail('❌ Form TIDAK PUNYA field parent/nesting!');
            $this->rInfo('  → Ini penyebab parent_id selalu kosong.');
            $this->rInfo('  → Lihat isi lengkap form di bawah untuk konfirmasi:');
        }

        $this->rInfo('--- Isi form (30 baris pertama) ---');
        $lines = array_slice(explode("\n", $content), 0, 30);
        foreach ($lines as $ln) {
            $this->rInfo('  | '.$ln);
        }
    }

    protected function checkNavigation(): void
    {
        $this->rSection('12. NAVIGATION CONFIG');

        $navFile = config_path('twill-navigation.php');
        if (! File::exists($navFile)) {
            $this->rWarn('config/twill-navigation.php TIDAK ADA');

            return;
        }

        $content = File::get($navFile);
        if (stripos($content, 'menulink') !== false || stripos($content, 'menu_link') !== false) {
            $this->rOk('✓ MenuLink terdaftar di twill-navigation.php');
        } else {
            $this->rWarn('⚠ MenuLink tidak ditemukan di twill-navigation.php');
        }
    }

    protected function checkRoutes(): void
    {
        $this->rSection('13. ROUTE ADMIN');

        $hasAdminRoute = false;
        foreach (Route::getRoutes() as $route) {
            $uri = $route->uri();
            if (stripos($uri, 'menulink') !== false || stripos($uri, 'menu_link') !== false) {
                $hasAdminRoute = true;
                $this->rInfo('  '.implode('|', $route->methods())." /{$uri} → ".$route->getName());
            }
        }
        if (! $hasAdminRoute) {
            $this->rFail('❌ Tidak ada route untuk MenuLink!');
        } else {
            $this->rOk('✓ Route admin terdaftar');
        }
    }

    protected function checkController(): void
    {
        $this->rSection('14. CONTROLLER');

        $candidates = [
            'app/Http/Controllers/Twill/MenuLinkController.php',
            'app/Http/Controllers/Twill/MenuLinksController.php',
        ];

        foreach ($candidates as $path) {
            if (File::exists(base_path($path))) {
                $this->rOk("✓ Controller: {$path}");
                $content = File::get(base_path($path));

                foreach (['HasNesting', 'nested', '$indexOptions', '$modelName'] as $key) {
                    if (stripos($content, $key) !== false) {
                        $this->rInfo("  ✓ Mengandung: {$key}");
                    }
                }

                return;
            }
        }
        $this->rWarn('Controller Twill MenuLink tidak ditemukan di lokasi standar');
    }

    protected function checkMigration(): void
    {
        $this->rSection('15. MIGRATION');

        $files = File::glob(database_path('migrations/*menu_link*'));
        if (empty($files)) {
            $this->rWarn('Tidak ada file migration *menu_link*');

            return;
        }
        foreach ($files as $f) {
            $this->rInfo('  - '.basename($f));
            $content = File::get($f);
            if (stripos($content, 'parent_id') !== false) {
                $this->rOk('    ✓ Ada parent_id di migration');
            }
            if (stripos($content, 'position') !== false) {
                $this->rOk('    ✓ Ada position di migration');
            }
        }
    }

    protected function checkBladeUsage(): void
    {
        $this->rSection('16. BLADE YANG PAKAI MENU');

        $viewsDir = resource_path('views');
        $hits = [];

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($viewsDir, \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $content = File::get($file->getPathname());
                if (stripos($content, 'menuItems') !== false
                    || stripos($content, 'MenuLink::') !== false) {
                    $hits[] = str_replace($viewsDir.'/', '', $file->getPathname());
                }
            }
        }

        if (empty($hits)) {
            $this->rWarn('Tidak ada blade yang pakai menuItems');
        } else {
            $this->rOk('✓ Ditemukan di:');
            foreach ($hits as $h) {
                $this->rInfo("  - {$h}");
            }
        }
    }

    protected function printConclusions(): void
    {
        $this->rSection('17. KESIMPULAN');

        $conclusions = [];

        // Cek child
        $childCount = DB::table($this->table)->whereNotNull('parent_id')->whereNull('deleted_at')->count();
        if ($childCount === 0) {
            $conclusions[] = '❌ AKAR MASALAH: Tidak ada child di DB. Solusi: buka admin, edit menu, set parent.';
        }

        // Cek form
        $formPaths = [
            base_path('resources/views/twill/menuLinks/form.blade.php'),
            base_path('resources/views/twill/menu_links/form.blade.php'),
        ];
        $formHasParent = false;
        foreach ($formPaths as $p) {
            if (File::exists($p)) {
                $c = File::get($p);
                if (stripos($c, 'parent') !== false || stripos($c, 'nest') !== false) {
                    $formHasParent = true;
                }
                break;
            }
        }

        if (! $formHasParent && $childCount === 0) {
            $conclusions[] = '❌ Form admin kemungkinan TIDAK punya field parent/nesting';
            $conclusions[] = '  → Perlu tambahkan field parent di form.blade.php';
        }

        // Cek trait
        $traits = class_uses_recursive($this->modelClass);
        if (! in_array('A17\Twill\Models\Behaviors\HasNesting', $traits)) {
            $conclusions[] = '❌ Trait HasNesting tidak ada di model';
        }
        if (! in_array('A17\Twill\Models\Behaviors\HasPosition', $traits)) {
            $conclusions[] = '❌ Trait HasPosition tidak ada di model';
        }

        if (empty($conclusions)) {
            $conclusions[] = '✅ Semua pemeriksaan dasar lolos.';
            $conclusions[] = '   Kalau dropdown tetap tidak muncul → cek JavaScript/Alpine di view.';
        }

        foreach ($conclusions as $c) {
            $this->rInfo($c);
        }
    }

    // ═══════════════════════════════════════════════════════════════
    // HELPERS
    // ═══════════════════════════════════════════════════════════════

    protected function rLine(string $msg): void
    {
        $this->line($msg);
        $this->report[] = $msg;
    }

    protected function rSection(string $title): void
    {
        $bar = str_repeat('═', 70);
        $this->rLine('');
        $this->rLine($bar);
        $this->rLine("  {$title}");
        $this->rLine($bar);
    }

    protected function rInfo(string $msg): void
    {
        $this->line($msg);
        $this->report[] = $msg;
    }

    protected function rOk(string $msg): void
    {
        $this->line("<fg=green>{$msg}</>");
        $this->report[] = $msg;
    }

    protected function rWarn(string $msg): void
    {
        $this->line("<fg=yellow>{$msg}</>");
        $this->report[] = $msg;
    }

    protected function rFail(string $msg): void
    {
        $this->line("<fg=red>{$msg}</>");
        $this->report[] = $msg;
    }

    protected function saveAndExit(int $code): int
    {
        File::ensureDirectoryExists(dirname($this->outputFile));
        File::put($this->outputFile, implode(PHP_EOL, $this->report));

        $this->line('');
        $this->line('<fg=cyan>📄 Laporan disimpan di:</>');
        $this->line("<fg=cyan>{$this->outputFile}</>");
        $this->line('');

        return $code;
    }
}
