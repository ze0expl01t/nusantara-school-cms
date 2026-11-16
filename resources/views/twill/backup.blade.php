@extends('twill::layouts.free')

@section('customPageContent')

@php
    // ============================================================
    // DYNAMIC ADMIN PATH — Otomatis mengikuti ADMIN_APP_PATH
    // Tidak perlu diubah saat path admin berubah
    // ============================================================
    $adminPath = config('twill.admin_app_path', 'admin');
    $settingsUrl = url("/{$adminPath}/settings/list/backup-settings");
@endphp

<div style="padding: 24px; max-width: 1200px; margin: 0 auto;">

    {{-- HEADER --}}
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px;">
        <div style="display: flex; align-items: center; gap: 12px;">
            {{-- Heroicon: archive-box --}}
            <div style="width: 44px; height: 44px; background: #ebf8ff; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #3182ce;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" style="width: 24px; height: 24px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0-3-3m3 3 3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                </svg>
            </div>
            <div>
                <h1 style="font-size: 24px; font-weight: 700; color: #1a202c; margin: 0;">
                    Backup Management
                </h1>
                <p style="color: #718096; font-size: 14px; margin-top: 4px;">
                    Kelola backup database dan file uploads.
                </p>
            </div>
        </div>

        {{-- Tombol Buat Backup Baru — pakai route name (auto-adaptif) --}}
        <form method="POST" action="{{ route('twill.backup.create') }}">
            @csrf
            <button type="submit"
                    onclick="return confirm('Mulai backup sekarang? Proses ini bisa memakan waktu beberapa menit.');"
                    style="background: #38a169; color: white; padding: 10px 18px; border-radius: 8px; border: none; font-weight: 600; cursor: pointer; font-size: 14px; display: inline-flex; align-items: center; gap: 8px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 16px; height: 16px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Buat Backup Baru
            </button>
        </form>
    </div>

    {{-- FLASH MESSAGE --}}
    @if(session('backup_success'))
        <div style="background: #f0fff4; border: 1px solid #38a169; color: #276749; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; display: flex; align-items: center; gap: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 18px; height: 18px; flex-shrink: 0;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            {{ session('backup_success') }}
        </div>
    @endif

    @if(session('backup_error'))
        <div style="background: #fff5f5; border: 1px solid #e53e3e; color: #c53030; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; display: flex; align-items: center; gap: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 18px; height: 18px; flex-shrink: 0;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
            </svg>
            {{ session('backup_error') }}
        </div>
    @endif

    {{-- STATISTIK --}}
    @php
        $diskName = config('backup.destination.disks.0', 'backups');
        $disk = \Illuminate\Support\Facades\Storage::disk($diskName);
        $allFiles = collect($disk->allFiles())
            ->filter(fn($f) => str_ends_with($f, '.zip'))
            ->map(fn($f) => [
                'name'  => basename($f),
                'path'  => $f,
                'size'  => $disk->size($f),
                'mtime' => $disk->lastModified($f),
            ]);
        $allFiles = $allFiles->sortByDesc('mtime')->values();
        $totalSize = $allFiles->sum('size');
        $latest = $allFiles->first();

        $retention = config('backup.cleanup.default_strategy.keep_all_backups_for_days', 7);
        $weekly = config('backup.cleanup.default_strategy.keep_weekly_backups_for_weeks', 2);
    @endphp

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">

        {{-- Total Backup --}}
        <div style="background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="#3182ce" style="width: 16px; height: 16px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.429 9.75 2.25 12l4.179 2.25m0-4.5 5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0 4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0-5.571 3-5.571-3" />
                </svg>
                <div style="font-size: 11px; font-weight: 600; color: #718096; text-transform: uppercase; letter-spacing: 0.5px;">
                    Total Backup
                </div>
            </div>
            <div style="font-size: 32px; font-weight: 700; color: #2d3748; line-height: 1;">
                {{ $allFiles->count() }}
            </div>
        </div>

        {{-- Total Ukuran --}}
        <div style="background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="#38a169" style="width: 16px; height: 16px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                </svg>
                <div style="font-size: 11px; font-weight: 600; color: #718096; text-transform: uppercase; letter-spacing: 0.5px;">
                    Total Ukuran
                </div>
            </div>
            <div style="font-size: 32px; font-weight: 700; color: #2d3748; line-height: 1;">
                {{ number_format($totalSize / 1024 / 1024, 1) }} <span style="font-size: 16px; color: #718096;">MB</span>
            </div>
        </div>

        {{-- Backup Terakhir --}}
        <div style="background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="#805ad5" style="width: 16px; height: 16px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <div style="font-size: 11px; font-weight: 600; color: #718096; text-transform: uppercase; letter-spacing: 0.5px;">
                    Backup Terakhir
                </div>
            </div>
            <div style="font-size: 20px; font-weight: 700; color: #2d3748; line-height: 1.3;">
                @if($latest)
                    {{ \Carbon\Carbon::createFromTimestamp($latest['mtime'])->diffForHumans() }}
                @else
                    <span style="color: #a0aec0;">—</span>
                @endif
            </div>
        </div>

        {{-- Status --}}
        <div style="background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                @if($latest)
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="#38a169" style="width: 16px; height: 16px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="#e53e3e" style="width: 16px; height: 16px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                    </svg>
                @endif
                <div style="font-size: 11px; font-weight: 600; color: #718096; text-transform: uppercase; letter-spacing: 0.5px;">
                    Status
                </div>
            </div>
            <div style="font-size: 20px; font-weight: 700; line-height: 1.3; color: {{ $latest ? '#38a169' : '#e53e3e' }};">
                {{ $latest ? 'Healthy' : 'Belum Ada' }}
            </div>
        </div>
    </div>

    {{-- DAFTAR BACKUP --}}
    <div style="background: white; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden;">
        <div style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; gap: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="#4a5568" style="width: 18px; height: 18px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
            </svg>
            <div style="font-weight: 700; color: #2d3748; font-size: 15px;">
                Daftar Backup
            </div>
        </div>

        @if($allFiles->isEmpty())
            <div style="padding: 48px 20px; text-align: center; color: #a0aec0; font-size: 14px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 48px; height: 48px; margin: 0 auto 12px; display: block; opacity: 0.5;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                </svg>
                Belum ada backup. Klik tombol <strong>Buat Backup Baru</strong> di atas.
            </div>
        @else
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f7fafc; text-align: left;">
                        <th style="padding: 12px 20px; font-size: 11px; font-weight: 600; color: #718096; text-transform: uppercase; letter-spacing: 0.5px;">Nama File</th>
                        <th style="padding: 12px 20px; font-size: 11px; font-weight: 600; color: #718096; text-transform: uppercase; letter-spacing: 0.5px;">Ukuran</th>
                        <th style="padding: 12px 20px; font-size: 11px; font-weight: 600; color: #718096; text-transform: uppercase; letter-spacing: 0.5px;">Tanggal</th>
                        <th style="padding: 12px 20px; font-size: 11px; font-weight: 600; color: #718096; text-transform: uppercase; letter-spacing: 0.5px; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allFiles as $file)
                        <tr style="border-top: 1px solid #edf2f7;">
                            <td style="padding: 12px 20px; font-size: 13px; color: #2d3748; font-family: monospace;">
                                <span style="display: inline-flex; align-items: center; gap: 8px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="#e53e3e" style="width: 16px; height: 16px; flex-shrink: 0;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                    </svg>
                                    {{ $file['name'] }}
                                </span>
                            </td>
                            <td style="padding: 12px 20px; font-size: 13px; color: #4a5568;">
                                {{ number_format($file['size'] / 1024 / 1024, 2) }} MB
                            </td>
                            <td style="padding: 12px 20px; font-size: 13px; color: #4a5568;">
                                {{ \Carbon\Carbon::createFromTimestamp($file['mtime'])->format('d M Y, H:i') }}
                            </td>
                            <td style="padding: 12px 20px; text-align: right; white-space: nowrap;">
                                {{-- Download Button — pakai route name (auto-adaptif) --}}
                                <a href="{{ route('twill.backup.download', ['file' => $file['path']]) }}"
                                   style="color: #3182ce; font-weight: 600; font-size: 13px; text-decoration: none; margin-right: 16px; display: inline-flex; align-items: center; gap: 4px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 14px; height: 14px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                    </svg>
                                    Download
                                </a>

                                {{-- Delete Button — pakai route name (auto-adaptif) --}}
                                <form method="POST"
                                      action="{{ route('twill.backup.delete', ['file' => $file['path']]) }}"
                                      style="display: inline;"
                                      onsubmit="return confirm('Hapus backup {{ $file['name'] }}? Tindakan ini tidak bisa dibatalkan.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            style="background: none; border: none; color: #e53e3e; font-weight: 600; font-size: 13px; cursor: pointer; padding: 0; display: inline-flex; align-items: center; gap: 4px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 14px; height: 14px;">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{-- INFO RETENSI — Settings URL DINAMIS --}}
    <div style="margin-top: 20px; padding: 14px 18px; background: #ebf8ff; border-radius: 8px; font-size: 13px; color: #2b6cb0; line-height: 1.6; display: flex; align-items: flex-start; gap: 10px;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 18px; height: 18px; flex-shrink: 0; margin-top: 1px;">
            <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
        </svg>
        <div>
            <strong>Info:</strong> Backup otomatis berjalan setiap hari.
            Retention: <strong>{{ $retention }} hari penuh</strong> + <strong>{{ $weekly }} minggu arsip</strong>.
            Konfigurasi bisa diubah di
            <a href="{{ $settingsUrl }}" style="color: #2b6cb0; text-decoration: underline;">
                Settings → Backup
            </a>.
        </div>
    </div>

</div>
@stop