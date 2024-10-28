<?php

namespace Marshmallow\ResourceProgress\Actions\Seo;

use Marshmallow\ResourceProgress\Actions\ResourceProgressAction;
use Marshmallow\ResourceProgress\Contracts\ResourceProgressSuiteInterface;

class CheckSeoableDescription extends ResourceProgressAction
{
    public function handle(ResourceProgressSuiteInterface $suite): void
    {
        $seoable = $this->resource->seoable;
        $this->incrementCheckCount();

        if (!$seoable) {
            $this->fail(__('No SEO record found'));
            return;
        }

        if (!$seoable->description) {
            $this->fail(__('No description is provided'));
            return;
        }

        $default_seo_description = config('seoable.defaults.description');
        if ($default_seo_description == $seoable->description) {
            $this->fail(__('Default SEO description is used'));
            return;
        }

        $length = strlen($seoable->description);
        if ($length < 50 || $length > 160) {
            $this->fail(__('SEO description should be between 50 and 160 characters. You currently have :count characters', ['count' => $length]));
            return;
        }
    }
}
