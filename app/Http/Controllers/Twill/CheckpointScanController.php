<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Http\Controllers\Admin\Controller as BaseController;
use App\Models\CheckpointScanResult;
use App\Services\CheckpointScanService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Custom CMS Page: Checkpoint Scan
 *
 * Bukan CRUD module — ini tool page yang menampilkan dashboard
 * hasil security scanner `php artisan checkpoint:scan`.
 *
 * Controller ini extends base Controller Twill (bukan ModuleController)
 * sesuai pola Custom CMS Pages di Twill 3.x.
 */
class CheckpointScanController extends BaseController
{
    public function index(): View
    {
        $latest = CheckpointScanResult::latest()->first();
        $history = CheckpointScanResult::latest()->limit(20)->get();

        $stats = [
            'total_scans' => CheckpointScanResult::count(),
            'pass_scans' => CheckpointScanResult::where('fail_count', 0)
                ->where('warn_count', 0)
                ->count(),
            'fail_scans' => CheckpointScanResult::where('fail_count', '>', 0)->count(),
        ];

        return view('twill.checkpoint-scan.index', compact('latest', 'history', 'stats'));
    }

    public function run(Request $request, CheckpointScanService $scanner): JsonResponse
    {
        @set_time_limit(300);
        @ini_set('max_execution_time', '300');

        $result = $scanner->run('manual');

        return response()->json([
            'success' => $result->exit_code === 0,
            'status' => $result->status,
            'summary' => [
                'total' => $result->total_checks,
                'pass' => $result->pass_count,
                'warn' => $result->warn_count,
                'fail' => $result->fail_count,
            ],
            'duration' => $result->duration_seconds,
            'ran_at' => $result->created_at->toDateTimeString(),
            'raw' => $result->raw_output,
        ]);
    }
}
