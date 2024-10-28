<?php

namespace Marshmallow\ResourceProgress\Observers;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;

class ModelObserver
{
    public function created(Model $model): void
    {
        $this->updateProgressSuites($model);
    }

    public function updated(Model $model): void
    {
        $this->updateProgressSuites($model);
    }

    public function restored(Model $model): void
    {
        $this->updateProgressSuites($model);
    }

    public function updateProgressSuites(Model $model)
    {
        $model_class = get_class($model);
        $model_class::getResourceProgressSuites()
            ->each(function ($attribute_settings, $suite) use ($model): void {
                $model->fresh()->updateProgress(
                    Arr::get($attribute_settings, 'attribute')
                );
            });
    }
}
