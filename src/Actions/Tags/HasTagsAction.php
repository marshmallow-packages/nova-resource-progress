<?php

namespace Marshmallow\ResourceProgress\Actions\Tags;

use Illuminate\Support\Str;
use Marshmallow\ResourceProgress\Actions\ResourceProgressAction;
use Marshmallow\ResourceProgress\Contracts\ResourceProgressSuiteInterface;

class HasTagsAction extends ResourceProgressAction
{
    public function handle(ResourceProgressSuiteInterface $suite): void
    {
        // Run your magic here

        collect($this->options)
            ->each(function (string $tag): void {
                /** Increment all the checks you do */
                $this->incrementCheckCount();

                if (!$this->resource->{$tag}) {
                    /** Trigger a fail message if one of the checks fails */
                    $this->fail(__('The tag `:tag` is not available', ['tag' => Str::of($tag)->remove('mm_tag_')]));
                }
            });
    }
}
