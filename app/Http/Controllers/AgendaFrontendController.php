<?php

namespace App\Http\Controllers;

use App\Models\Agenda;

class AgendaFrontendController extends Controller
{
    public function show($slug)
    {
        // Mengambil data agenda berdasarkan slug dan status published
        $agenda = Agenda::forSlug($slug)->where('published', true)->firstOrFail();

        $referenceDate = $agenda->agenda_date ?? $agenda->created_at;

        // Mengambil agenda berikutnya (berdasarkan tanggal setelah agenda saat ini)
        $nextAgenda = Agenda::where('published', true)
            ->where('id', '!=', $agenda->id)
            ->where('agenda_date', '>', $referenceDate)
            ->orderBy('agenda_date', 'asc')
            ->first();

        // Opsi Looping: Jika tidak ada agenda setelahnya, ambil agenda pertama (paling awal)
        if (! $nextAgenda) {
            $nextAgenda = Agenda::where('published', true)
                ->where('id', '!=', $agenda->id)
                ->orderBy('agenda_date', 'asc')
                ->first();
        }

        return view('site.agenda-detail', compact('agenda', 'nextAgenda'));
    }
}
