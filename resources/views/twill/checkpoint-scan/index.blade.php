@extends('twill::layouts.free')

@section('customPageContent')
<div style="padding: 32px 24px 48px; max-width: 1200px; margin: 0 auto; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">

    {{-- ═══════════════════════════════════════════════════ --}}
    {{-- HEADER --}}
    {{-- ═══════════════════════════════════════════════════ --}}
    <div style="display: flex; justify-content: space-between; align-items: center; gap: 24px; margin-bottom: 32px; flex-wrap: wrap;">
        <div style="display: flex; align-items: center; gap: 16px;">
            <div style="width: 52px; height: 52px; border-radius: 14px; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 20px -6px rgba(99, 102, 241, 0.5); flex-shrink: 0;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#fff" style="width: 26px; height: 26px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                </svg>
            </div>
            <div>
                <h1 style="font-size: 24px; font-weight: 800; color: #111827; margin: 0; letter-spacing: -0.5px;">
                    Checkpoint Scan
                </h1>
                <p style="color: #6b7280; font-size: 13px; margin: 4px 0 0 0;">
                    Security scanner &middot; <code style="background: #f3f4f6; padding: 2px 8px; border-radius: 5px; font-size: 12px; color: #374151; border: 1px solid #e5e7eb;">php artisan checkpoint:scan</code>
                </p>
            </div>
        </div>

        <button type="button" id="btn-run"
                onclick="window.runCheckpointScan && window.runCheckpointScan()"
                style="display: inline-flex; align-items: center; gap: 10px; padding: 12px 24px; border-radius: 12px; border: none; font-weight: 600; font-size: 14px; cursor: pointer; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #fff; box-shadow: 0 4px 14px 0 rgba(99, 102, 241, 0.4); transition: all 0.2s; font-family: inherit;">
            <svg id="btn-icon-play" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 16px; height: 16px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.348a1.125 1.125 0 0 1 0 1.971l-11.54 6.347a1.125 1.125 0 0 1-1.667-.985V5.653Z" />
            </svg>
            <span id="btn-text">Run Scan</span>
        </button>
    </div>

    {{-- ═══════════════════════════════════════════════════ --}}
    {{-- STAT CARDS --}}
    {{-- ═══════════════════════════════════════════════════ --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 28px;">

        <div style="background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 22px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
                <div style="width: 32px; height: 32px; border-radius: 8px; background: #f3f4f6; display: flex; align-items: center; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#6b7280" style="width: 16px; height: 16px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z" />
                    </svg>
                </div>
                <div style="font-size: 11px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.6px;">
                    Total
                </div>
            </div>
            <div id="stat-total" style="font-size: 34px; font-weight: 800; color: #111827; line-height: 1; letter-spacing: -1px;">
                {{ $latest->total_checks ?? 0 }}
            </div>
        </div>

        <div style="background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 22px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
                <div style="width: 32px; height: 32px; border-radius: 8px; background: #ecfdf5; display: flex; align-items: center; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="#10b981" style="width: 16px; height: 16px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                </div>
                <div style="font-size: 11px; font-weight: 700; color: #10b981; text-transform: uppercase; letter-spacing: 0.6px;">
                    Passed
                </div>
            </div>
            <div id="stat-pass" style="font-size: 34px; font-weight: 800; color: #10b981; line-height: 1; letter-spacing: -1px;">
                {{ $latest->pass_count ?? 0 }}
            </div>
        </div>

        <div style="background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 22px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
                <div style="width: 32px; height: 32px; border-radius: 8px; background: #fffbeb; display: flex; align-items: center; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#f59e0b" style="width: 16px; height: 16px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>
                </div>
                <div style="font-size: 11px; font-weight: 700; color: #f59e0b; text-transform: uppercase; letter-spacing: 0.6px;">
                    Warnings
                </div>
            </div>
            <div id="stat-warn" style="font-size: 34px; font-weight: 800; color: #f59e0b; line-height: 1; letter-spacing: -1px;">
                {{ $latest->warn_count ?? 0 }}
            </div>
        </div>

        <div style="background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 22px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
                <div style="width: 32px; height: 32px; border-radius: 8px; background: #fef2f2; display: flex; align-items: center; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#ef4444" style="width: 16px; height: 16px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <div style="font-size: 11px; font-weight: 700; color: #ef4444; text-transform: uppercase; letter-spacing: 0.6px;">
                    Failed
                </div>
            </div>
            <div id="stat-fail" style="font-size: 34px; font-weight: 800; color: #ef4444; line-height: 1; letter-spacing: -1px;">
                {{ $latest->fail_count ?? 0 }}
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════ --}}
    {{-- ALERTS --}}
    {{-- ═══════════════════════════════════════════════════ --}}
    <div id="alert-loading" style="display:none; padding: 16px 20px; border-radius: 12px; margin-bottom: 20px; background: #eef2ff; border: 1px solid #c7d2fe; color: #3730a3; font-size: 14px;">
        <strong>⏳ Menjalankan security scan...</strong>
        <div style="font-size: 13px; margin-top: 4px; opacity: 0.85;">Proses 30 detik - 2 menit. Jangan tutup halaman ini.</div>
    </div>

    <div id="alert-error" style="display:none; padding: 16px 20px; border-radius: 12px; margin-bottom: 20px; background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; font-size: 14px; font-weight: 600;">
        ⚠️ <span id="alert-error-text"></span>
    </div>

    {{-- ═══════════════════════════════════════════════════ --}}
    {{-- OUTPUT PANEL --}}
    {{-- ═══════════════════════════════════════════════════ --}}
    <div style="background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); margin-bottom: 24px; overflow: hidden;">

        <div style="padding: 16px 24px; border-bottom: 1px solid #f3f4f6; display: flex; justify-content: space-between; align-items: center; background: linear-gradient(180deg, #fff 0%, #f9fafb 100%); flex-wrap: wrap; gap: 12px;">
            <div style="font-weight: 700; font-size: 14px; color: #111827; display: flex; align-items: center; gap: 10px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#6366f1" style="width: 18px; height: 18px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 7.5l3 2.25-3 2.25m4.5 0h3m-9 8.25h13.5A2.25 2.25 0 0 0 21 18V6a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 6v12a2.25 2.25 0 0 0 2.25 2.25Z" />
                </svg>
                Output Scanner
            </div>
            <div style="font-size: 12px; color: #6b7280; display: flex; align-items: center; gap: 16px;">
                <span id="meta-ran-at">
                    @if($latest)
                        🕐 {{ $latest->created_at->format('d M Y, H:i') }}
                    @endif
                </span>
                <span id="meta-duration">
                    @if($latest)
                        ⚡ {{ $latest->duration_seconds }}s
                    @endif
                </span>
            </div>
        </div>

        <div style="padding: 20px 24px;">
            <div style="background: #0f172a; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px -4px rgba(0,0,0,0.3);">
                <div style="padding: 10px 16px; background: rgba(255,255,255,0.03); display: flex; align-items: center; gap: 8px; border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <span style="width: 11px; height: 11px; border-radius: 50%; background: #ff5f57; display: inline-block;"></span>
                    <span style="width: 11px; height: 11px; border-radius: 50%; background: #febc2e; display: inline-block;"></span>
                    <span style="width: 11px; height: 11px; border-radius: 50%; background: #28c840; display: inline-block;"></span>
                    <span style="font-size: 11px; color: rgba(255,255,255,0.5); font-family: 'SF Mono', Consolas, monospace; margin-left: auto; letter-spacing: 0.5px;">checkpoint:scan --json</span>
                </div>
                <pre id="output" style="margin: 0; padding: 20px 22px; max-height: 420px; overflow: auto; font-family: 'SF Mono', 'Fira Code', Consolas, monospace; font-size: 12.5px; line-height: 1.7; color: #e2e8f0; white-space: pre-wrap; word-break: break-word;">@if($latest && $latest->raw_output){{ $latest->raw_output }}@else$ Klik tombol "Run Scan" untuk memulai security audit...@endif</pre>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════ --}}
    {{-- HISTORY PANEL --}}
    {{-- ═══════════════════════════════════════════════════ --}}
    <div style="background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); overflow: hidden;">

        <div style="padding: 16px 24px; border-bottom: 1px solid #f3f4f6; background: linear-gradient(180deg, #fff 0%, #f9fafb 100%);">
            <div style="font-weight: 700; font-size: 14px; color: #111827; display: flex; align-items: center; gap: 10px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#6366f1" style="width: 18px; height: 18px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                Riwayat Scan
                <span style="font-size: 12px; font-weight: 500; color: #6b7280; margin-left: 6px;">(20 terakhir)</span>
            </div>
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 13.5px;">
                <thead style="background: #f9fafb;">
                    <tr>
                        <th style="padding: 14px 24px; text-align: left; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px; color: #6b7280; border-bottom: 1px solid #e5e7eb;">Waktu</th>
                        <th style="padding: 14px 24px; text-align: left; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px; color: #6b7280; border-bottom: 1px solid #e5e7eb;">Status</th>
                        <th style="padding: 14px 24px; text-align: center; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px; color: #6b7280; border-bottom: 1px solid #e5e7eb;">Pass</th>
                        <th style="padding: 14px 24px; text-align: center; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px; color: #6b7280; border-bottom: 1px solid #e5e7eb;">Warn</th>
                        <th style="padding: 14px 24px; text-align: center; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px; color: #6b7280; border-bottom: 1px solid #e5e7eb;">Fail</th>
                        <th style="padding: 14px 24px; text-align: right; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px; color: #6b7280; border-bottom: 1px solid #e5e7eb;">Durasi</th>
                        <th style="padding: 14px 24px; text-align: center; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px; color: #6b7280; border-bottom: 1px solid #e5e7eb;">Trigger</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($history as $h)
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 14px 24px; color: #4b5563;">
                                {{ $h->created_at->format('d M Y') }}
                                <span style="color: #d1d5db;">&middot;</span>
                                {{ $h->created_at->format('H:i') }}
                            </td>
                            <td style="padding: 14px 24px;">
                                @if($h->status === 'pass')
                                    <span style="display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 999px; font-size: 11px; font-weight: 700; letter-spacing: 0.3px; text-transform: uppercase; background: #ecfdf5; color: #047857;">
                                        <span style="width: 6px; height: 6px; border-radius: 50%; background: currentColor;"></span>
                                        Pass
                                    </span>
                                @elseif($h->status === 'warn')
                                    <span style="display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 999px; font-size: 11px; font-weight: 700; letter-spacing: 0.3px; text-transform: uppercase; background: #fffbeb; color: #b45309;">
                                        <span style="width: 6px; height: 6px; border-radius: 50%; background: currentColor;"></span>
                                        Warn
                                    </span>
                                @else
                                    <span style="display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 999px; font-size: 11px; font-weight: 700; letter-spacing: 0.3px; text-transform: uppercase; background: #fef2f2; color: #b91c1c;">
                                        <span style="width: 6px; height: 6px; border-radius: 50%; background: currentColor;"></span>
                                        Fail
                                    </span>
                                @endif
                            </td>
                            <td style="padding: 14px 24px; text-align: center; font-weight: 700; color: #10b981;">{{ $h->pass_count }}</td>
                            <td style="padding: 14px 24px; text-align: center; font-weight: 700; color: #f59e0b;">{{ $h->warn_count }}</td>
                            <td style="padding: 14px 24px; text-align: center; font-weight: 700; color: #ef4444;">{{ $h->fail_count }}</td>
                            <td style="padding: 14px 24px; text-align: right; color: #6b7280; font-size: 12.5px;">{{ $h->duration_seconds }}s</td>
                            <td style="padding: 14px 24px; text-align: center;">
                                <span style="background: #f3f4f6; padding: 3px 9px; border-radius: 5px; font-size: 11px; color: #4b5563; border: 1px solid #e5e7eb; font-family: 'SF Mono', Consolas, monospace;">{{ $h->triggered_by }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 48px 24px; color: #9ca3af; font-size: 14px;">
                                Belum ada riwayat scan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('extra_js')
<script>
(function() {
    'use strict';

    var runUrl = @json(route('twill.checkpoint-scan.run'));
    var csrfToken = @json(csrf_token());

    window.runCheckpointScan = async function() {
        if (!confirm('Jalankan checkpoint scan sekarang? Proses ini bisa memakan waktu hingga 2 menit.')) return;

        var btn = document.getElementById('btn-run');
        var btnText = document.getElementById('btn-text');
        var loading = document.getElementById('alert-loading');
        var errorBox = document.getElementById('alert-error');
        var errorText = document.getElementById('alert-error-text');

        if (btn) {
            btn.disabled = true;
            btn.style.opacity = '0.6';
            btn.style.cursor = 'not-allowed';
        }
        if (btnText) btnText.textContent = '⏳ Scanning...';
        if (loading) loading.style.display = 'block';
        if (errorBox) errorBox.style.display = 'none';

        try {
            var res = await fetch(runUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
            });

            var data = await res.json();
            if (!res.ok) throw new Error(data.message || 'Server error');

            var el;
            if ((el = document.getElementById('stat-total'))) el.textContent = data.summary.total;
            if ((el = document.getElementById('stat-pass'))) el.textContent = data.summary.pass;
            if ((el = document.getElementById('stat-warn'))) el.textContent = data.summary.warn;
            if ((el = document.getElementById('stat-fail'))) el.textContent = data.summary.fail;
            if ((el = document.getElementById('output'))) el.textContent = data.raw || '(kosong)';
            if ((el = document.getElementById('meta-ran-at'))) el.textContent = '🕐 ' + data.ran_at;
            if ((el = document.getElementById('meta-duration'))) el.textContent = '⚡ ' + data.duration + 's';

            if (data.summary.fail > 0) {
                if (errorText) errorText.textContent = 'Ditemukan ' + data.summary.fail + ' FAIL. Cek output di bawah.';
                if (errorBox) errorBox.style.display = 'block';
            } else if (data.summary.warn > 0) {
                if (errorText) errorText.textContent = 'Ditemukan ' + data.summary.warn + ' WARN. Cek output di bawah.';
                if (errorBox) errorBox.style.display = 'block';
            }
        } catch (e) {
            if (errorText) errorText.textContent = e.message;
            if (errorBox) errorBox.style.display = 'block';
        } finally {
            if (btn) {
                btn.disabled = false;
                btn.style.opacity = '1';
                btn.style.cursor = 'pointer';
            }
            if (btnText) btnText.textContent = '▶ Run Scan';
            if (loading) loading.style.display = 'none';
        }
    };

    console.log('[Checkpoint Scan] Ready. runUrl =', runUrl);
})();
</script>
@endpush
