<?php

namespace Marshmallow\ResourceProgress\Nova\Actions;

use AllowDynamicProperties;
use Illuminate\Support\Arr;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Marshmallow\ResourceProgress\Observers\ModelObserver;
use Marshmallow\ResourceProgress\Jobs\UpdateResourceProgressJob;

#[AllowDynamicProperties]
class IndexResourcesProgress extends Action implements ShouldQueue
{
    use Queueable;
    use InteractsWithQueue;

    public $confirmText = 'This action will be run on the background. Once you start this action, it can take a view minutes before you will see the results in your resources.';

    public function __construct(protected string $nova_resource_class)
    {
        //
    }

    /**
     * Perform the action on the given models.
     */
    public function handle(ActionFields $actionFields, Collection $models): void
    {
        (new $this->nova_resource_class)::$model::cursor()
            ->each(function (Model $model) {
                UpdateResourceProgressJob::dispatch($model);
            });
    }
}
