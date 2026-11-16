<?php

namespace App\Repositories;

use A17\Twill\Repositories\ModuleRepository;
use App\Models\NewsletterSubscriber;

class NewsletterSubscriberRepository extends ModuleRepository
{
    public function __construct(NewsletterSubscriber $model)
    {
        $this->model = $model;
    }
}
