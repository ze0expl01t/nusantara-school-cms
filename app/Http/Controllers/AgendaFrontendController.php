<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class AgendaFrontendController extends Controller
{
    public function show($slug)
    {
        $agenda = Agenda::forSlug($slug)->where('published', true)->firstOrFail();

        $referenceDate = $agenda->agenda_date ?? $agenda->created_at;

        $nextAgenda = Agenda::where('published', true)
            ->where('id', '!=', $agenda->id)
            ->where('agenda_date', '>', $referenceDate)
            ->orderBy('agenda_date', 'asc')
            ->first();

        if (! $nextAgenda) {
            $nextAgenda = Agenda::where('published', true)
                ->where('id', '!=', $agenda->id)
                ->orderBy('agenda_date', 'asc')
                ->first();
        }

        return view('site.agenda-detail', compact('agenda', 'nextAgenda'));
    }

    public function downloadIcs($slug): Response
    {
        $agenda = Agenda::forSlug($slug)->where('published', true)->firstOrFail();

        $tzName = 'Asia/Jakarta';
        $startDt = $agenda->agenda_date
            ? Carbon::parse($agenda->agenda_date)->timezone($tzName)
            : Carbon::now($tzName)->addDay()->setTime(9, 0);

        $endDt = $startDt->copy()->addHours(2);

        if (filled($agenda->time_slot ?? null)) {
            if (preg_match('/(\d{1,2})[:.](\d{2})\s*[-–]\s*(\d{1,2})[:.](\d{2})/', (string) $agenda->time_slot, $m)) {
                $sh = min(23, max(0, (int) $m[1]));
                $sm = min(59, max(0, (int) $m[2]));
                $eh = min(23, max(0, (int) $m[3]));
                $em = min(59, max(0, (int) $m[4]));

                $startDt = $startDt->copy()->setTime($sh, $sm, 0);
                $endDt = $startDt->copy()->setTime($eh, $em, 0);
                if ($endDt->lessThanOrEqualTo($startDt)) {
                    $endDt->addDay();
                }
            }
        }

        $escapeIcs = function (?string $text): string {
            if ($text === null) {
                return '';
            }
            $normalized = str_replace(["\r\n", "\r"], "\n", $text);
            $normalized = str_replace(['\\', ';', ','], ['\\\\', '\\;', '\\,'], $normalized);
            return str_replace("\n", '\\n', $normalized);
        };

        $venue = filled($agenda->venue ?? null) ? trim((string) $agenda->venue) : '';
        $location = filled($agenda->location ?? null) ? trim((string) $agenda->location) : '';
        $locationCombined = trim(implode(' — ', array_filter([$venue !== '' ? $venue : null, ($location !== '' && $location !== $venue) ? $location : null])));

        $descParts = [];
        $plainDesc = trim(preg_replace("/\n{3,}/", "\n\n", strip_tags(str_replace(['<br>', '<br/>', '<br />', '</p>'], ["\n", "\n", "\n", "\n\n"], (string) ($agenda->description ?? '')))));
        if ($plainDesc !== '') {
            $descParts[] = $plainDesc;
        }
        if (filled($agenda->time_slot ?? null)) {
            $descParts[] = 'Jam: ' . trim((string) $agenda->time_slot);
        }
        if (filled($agenda->organizer ?? null)) {
            $descParts[] = 'Penyelenggara: ' . trim((string) $agenda->organizer);
        }
        if (filled($agenda->participants ?? null)) {
            $descParts[] = 'Peserta: ' . trim((string) $agenda->participants);
        }
        if (filled($agenda->external_url ?? null)) {
            $ext = trim((string) $agenda->external_url);
            if (preg_match('#^https?://#i', $ext) && filter_var($ext, FILTER_VALIDATE_URL)) {
                $descParts[] = 'URL: ' . $ext;
            }
        }

        $uidHost = parse_url(url('/'), PHP_URL_HOST) ?: 'nusantara.local';
        $uid = 'agenda-' . $agenda->id . '-' . md5((string) $slug) . '@' . $uidHost;
        $nowUtc = Carbon::now('UTC')->format('Ymd\THis\Z');
        $dtStartUtc = $startDt->copy()->timezone('UTC')->format('Ymd\THis\Z');
        $dtEndUtc = $endDt->copy()->timezone('UTC')->format('Ymd\THis\Z');

        $lines = [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//Nusantara School CMS//Agenda Calendar//ID',
            'CALSCALE:GREGORIAN',
            'METHOD:PUBLISH',
            'BEGIN:VEVENT',
            'UID:' . $escapeIcs($uid),
            'DTSTAMP:' . $nowUtc,
            'DTSTART:' . $dtStartUtc,
            'DTEND:' . $dtEndUtc,
            'SUMMARY:' . $escapeIcs((string) $agenda->title),
        ];

        if ($locationCombined !== '') {
            $lines[] = 'LOCATION:' . $escapeIcs($locationCombined);
        }

        if (! empty($descParts)) {
            $lines[] = 'DESCRIPTION:' . $escapeIcs(implode("\n\n", $descParts));
        }

        $lines[] = 'URL:' . $escapeIcs(route('agenda.show', $slug));
        $lines[] = 'END:VEVENT';
        $lines[] = 'END:VCALENDAR';

        $icsContent = implode("\r\n", $lines) . "\r\n";
        $filename = Str::slug((string) ($agenda->title ?: $slug)) . '.ics';

        return response($icsContent, 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
