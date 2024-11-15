<?php

namespace Marshmallow\ResourceProgress\Observers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;

class ModelObserver
{
    /**
     * Indicates if ResourceProgress will dispatch the observer's events after all database transactions have committed.
     *
     * @var bool
     */
    public $afterCommit;

    /**
     * Create a new observer instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->afterCommit = Config::get('resource-progress.after_commit', false);
    }

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
