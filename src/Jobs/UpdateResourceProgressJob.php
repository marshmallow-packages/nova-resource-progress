<?php

namespace Marshmallow\ResourceProgress\Jobs;

use AllowDynamicProperties;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Marshmallow\ResourceProgress\Observers\ModelObserver;

#[AllowDynamicProperties]
class UpdateResourceProgressJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Model $model)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        (new ModelObserver)->updateProgressSuites($this->model);
    }
}
