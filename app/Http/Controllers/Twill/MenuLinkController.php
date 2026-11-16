<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Http\Controllers\Admin\NestedModuleController as BaseModuleController;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\Fields\Browser;
use A17\Twill\Services\Forms\Form;
use App\Models\Page;

class MenuLinkController extends BaseModuleController
{
    protected $moduleName = 'menuLinks';

    protected $showOnlyParentItemsInBrowsers = true;

    protected $nestedItemsDepth = 3;

    /**
     * Nonaktifkan pagination agar drag & drop nested menu berfungsi.
     */
    protected $perPage = -1;

    // 👇 TAMBAHKAN BLOK INI
    protected $indexOptions = [
        'reorder' => true,   // Mengaktifkan fitur drag-and-drop untuk urutan
        'nested' => true,    // Memastikan tampilan hierarki (tree) muncul
        // Opsi lain bisa Anda sesuaikan
        'create' => true,
        'edit' => true,
        'publish' => true,
        'delete' => true,
    ];

    protected function setUpController(): void
    {
        $this->disablePermalink();
    }

    public function getForm(TwillModelContract $model): Form
    {
        $form = parent::getForm($model);
        $form->add(Browser::make()->name('page')->modules([Page::class]));

        return $form;
    }
}
