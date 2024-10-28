<?php

namespace Marshmallow\ResourceProgress\Actions\Translations;

use App\Models\Language;
use Illuminate\Support\Arr;
use Marshmallow\ResourceProgress\Actions\ResourceProgressAction;
use Marshmallow\ResourceProgress\Contracts\ResourceProgressSuiteInterface;

class HasTranslatablesAction extends ResourceProgressAction
{
    public function handle(ResourceProgressSuiteInterface $suite): void
    {
        $this->resource->missingTranslatable
            ->each(function ($missing_translatable) {
                $this->incrementCheckCount();
                $this->fail(__('The data in :columns is not translated in :language yet.', [
                    'columns' => Arr::join($missing_translatable->missing, ', ', ' ' . __('and') . ' '),
                    'language' => $missing_translatable->language->name,
                ]));
            });
    }
}
