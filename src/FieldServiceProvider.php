<?php

namespace Marshmallow\ResourceProgress;

use Laravel\Nova\Nova;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\File;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Marshmallow\ResourceProgress\Observers\ModelObserver;
use Marshmallow\ResourceProgress\Traits\TrackResourceProgress;
use Marshmallow\ResourceProgress\Console\CreateResourceProgressSuite;
use Marshmallow\ResourceProgress\Console\CreateResourceProgressAction;

class FieldServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /** Register the observer for all the classes */
        $this->getModels()
            ->each(function ($model_class) {
                $model_class::observe(ModelObserver::class);
            });

        Nova::serving(function (ServingNova $event) {
            Nova::script('resource-progress', __DIR__ . '/../dist/js/field.js');
            Nova::style('resource-progress', __DIR__ . '/../dist/css/field.css');
        });

        if ($this->app->runningInConsole()) {
            $this->commands([
                CreateResourceProgressAction::class,
                CreateResourceProgressSuite::class,
            ]);
        }

        $this->publishes([
            __DIR__ . '/../config/resource-progress.php' => config_path('resource-progress.php'),
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/resource-progress.php',
            'resource-progress'
        );
    }

    protected function getModels(): Collection
    {
        $models = collect(File::allFiles(app_path()))
            ->map(function ($item) {
                $path = $item->getRelativePathName();

                /** @var \Illuminate\Foundation\Application $app */
                $app = Container::getInstance();

                $class = sprintf(
                    '\%s%s',
                    $app->getNamespace(),
                    strtr(substr($path, 0, strrpos($path, '.')), '/', '\\')
                );

                return $class;
            })
            ->filter(function ($class) {
                $valid = false;

                if (class_exists($class)) {
                    $reflection = new \ReflectionClass($class);
                    $valid = $reflection->isSubclassOf(Model::class) &&
                        !$reflection->isAbstract();

                    if ($valid) {
                        $valid = in_array(TrackResourceProgress::class, class_uses_recursive($class));
                    }
                }

                return $valid;
            });

        return $models->values();
    }
}
