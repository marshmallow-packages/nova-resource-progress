<?php

namespace Marshmallow\ResourceProgress\Actions\Media;

use Marshmallow\ResourceProgress\Actions\ResourceProgressAction;
use Marshmallow\ResourceProgress\Contracts\ResourceProgressSuiteInterface;

class HasMediaAction extends ResourceProgressAction
{
    public function handle(ResourceProgressSuiteInterface $suite): void
    {
        collect($this->options)
            ->each(function (string $media_collection): void {
                $this->incrementCheckCount();

                if ($this->resource->getMedia($media_collection)->count() === 0) {
                    $this->fail(__('No media uploaded to :collection', ['collection' => $media_collection]));
                }
            });
    }
}
