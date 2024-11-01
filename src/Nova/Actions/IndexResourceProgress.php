<?php

namespace Marshmallow\ResourceProgress\Nova\Actions;

use AllowDynamicProperties;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Marshmallow\ResourceProgress\Observers\ModelObserver;

#[AllowDynamicProperties]
class IndexResourceProgress extends Action
{
    use Queueable;
    use InteractsWithQueue;

    /**
     * Perform the action on the given models.
     */
    public function handle(ActionFields $actionFields, Collection $models): void
    {
        $models->each(function (Model $model) {
            (new ModelObserver)->updateProgressSuites($model);
        });
    }
}
