<?php

namespace Marshmallow\ResourceProgress\Suites;

use Illuminate\Database\Eloquent\Model;
use Marshmallow\ResourceProgress\Actions\CheckTranslatables;
use Marshmallow\ResourceProgress\Contracts\ResourceProgressSuiteInterface;

#[\Attribute]
class ResourceProgressTranslationsSuite implements ResourceProgressSuiteInterface
{
    public function getSuiteKey(): string
    {
        return 'translations';
    }

    public function getSuiteName(): string
    {
        return __('Translations');
    }

    public function getActions(Model $model): array
    {
        return [
            CheckTranslatables::class,
        ];
    }
}
