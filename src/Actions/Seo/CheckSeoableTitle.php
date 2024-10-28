<?php

namespace Marshmallow\ResourceProgress\Actions\Seo;

use Marshmallow\ResourceProgress\Actions\ResourceProgressAction;
use Marshmallow\ResourceProgress\Contracts\ResourceProgressSuiteInterface;

class CheckSeoableTitle extends ResourceProgressAction
{
    public function handle(ResourceProgressSuiteInterface $suite): void
    {
        $seoable = $this->resource->seoable;
        $this->incrementCheckCount();

        if (!$seoable) {
            $this->fail(__('No SEO record found'));
            return;
        }

        if (!$seoable->title) {
            $this->fail(__('No description is provided'));
            return;
        }

        $default_seo_title = config('seoable.defaults.title');
        if ($default_seo_title == $seoable->title) {
            $this->fail(__('Default SEO title is used'));
            return;
        }

        $length = strlen($seoable->title);
        if ($length < 40 || $length > 70) {
            $this->fail(__('SEO title should be between 40 and 70 characters. You currently have :count characters', ['count' => $length]));
            return;
        }
    }
}
