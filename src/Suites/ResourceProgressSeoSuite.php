<?php

namespace Marshmallow\ResourceProgress\Suites;

use Illuminate\Database\Eloquent\Model;
use Marshmallow\ResourceProgress\Actions\CheckSeoable;
use Marshmallow\ResourceProgress\Actions\Seo\CheckSeoableTitle;
use Marshmallow\ResourceProgress\Actions\Seo\CheckSeoableDescription;
use Marshmallow\ResourceProgress\Contracts\ResourceProgressSuiteInterface;

#[\Attribute]
class ResourceProgressSeoSuite implements ResourceProgressSuiteInterface
{
    public function getSuiteKey(): string
    {
        return 'seo';
    }

    public function getSuiteName(): string
    {
        return __('SEO');
    }

    public function getActions(Model $model): array
    {
        return [
            CheckSeoableTitle::class,
            CheckSeoableDescription::class,
        ];
    }
}
