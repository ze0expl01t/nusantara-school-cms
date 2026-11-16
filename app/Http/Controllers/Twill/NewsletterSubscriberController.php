<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Http\Controllers\Admin\ModuleController as BaseModuleController;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;
use App\Models\NewsletterSubscriber;

class NewsletterSubscriberController extends BaseModuleController
{
    protected $moduleName = 'newsletterSubscribers';

    protected $titleColumnKey = 'email';

    protected $indexOptions = [
        'permalink' => false,
        'publish' => false,
        'reorder' => false,
        'create' => false,
        'edit' => false,
        'delete' => true,
    ];

    public function getForm(TwillModelContract $model): Form
    {
        return parent::getForm($model);
    }

    protected function additionalIndexTableColumns(): TableColumns
    {
        $table = parent::additionalIndexTableColumns();

        $table->add(
            Text::make()->field('name')->title('Nama')
        );

        $table->add(
            Text::make()->field('status')->title('Status')
        );

        $table->add(
            Text::make()->field('verified_at')->title('Diverifikasi')
        );

        $table->add(
            Text::make()->field('created_at')->title('Tanggal Daftar')
        );

        return $table;
    }

    public function exportCsv()
    {
        $subscribers = NewsletterSubscriber::where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->get(['email', 'name', 'verified_at', 'created_at']);

        $filename = 'newsletter-subscribers-'.date('Y-m-d').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($subscribers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Email', 'Nama', 'Terverifikasi', 'Tanggal Daftar']);

            foreach ($subscribers as $s) {
                fputcsv($file, [
                    $s->email,
                    $s->name ?? '-',
                    $s->verified_at ? $s->verified_at->format('Y-m-d H:i') : '-',
                    $s->created_at->format('Y-m-d H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
