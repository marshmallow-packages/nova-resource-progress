<?php

namespace Marshmallow\ResourceProgress\Traits;

use ReflectionClass;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Marshmallow\ResourceProgress\Contracts\ResourceProgressSuiteInterface;

trait TrackResourceProgress
{
    public function updateProgress(ResourceProgressSuiteInterface $suite)
    {
        $progress = [
            'checks_done' => 0,
            'checks_failed' => 0,
            'messages' => [],
        ];

        $actions = $suite->getActions($this);

        collect($actions)->each(function ($action) use ($suite, &$progress) {
            $action = is_string($action) ? new $action($this) : $action;
            $action->handle($suite);

            $progress['checks_done'] += $action->getTotalCheckCount();
            $progress['checks_failed'] += $action->getTotalFailedCount();
            $progress['messages'] = array_merge($progress['messages'], $action->getFailedMessages());
        });

        $checks_done = Arr::get($progress, 'checks_done', 0);
        $checks_failed = Arr::get($progress, 'checks_failed', 0);

        $percentage = ($checks_done > 0)
            ? round(($checks_done - $checks_failed) / $checks_done * 100)
            : 0;

        Arr::set($progress, 'percentage', $percentage);
        $current_progress = $this->resource_progress ?? [];

        Arr::set($current_progress, $suite->getSuiteKey(), $progress);
        $this->resource_progress = $current_progress;

        return $progress;
    }

    protected function setProgressActions(): array
    {
        return [];
    }

    public function getProgressMagicMethodName(string $suite, string $prepend, string $append): string
    {
        return Str::of($suite)
            ->studly()
            ->prepend($prepend)
            ->append($append)
            ->toString();
    }

    public function getProgressActions(string $suite = 'progress'): array
    {
        $get_suite_action_method_name = $this->getProgressMagicMethodName(
            suite: $suite,
            prepend: 'set',
            append: 'Actions',
        );

        $suite_actions = (method_exists($this, $get_suite_action_method_name))
            ? $this->{$get_suite_action_method_name}()
            : [];

        $suite_actions = collect($suite_actions)
            ->map(function ($action) {
                $progress_suite = new ReflectionClass($action);
                if (in_array(ResourceProgressSuiteInterface::class, $progress_suite->getInterfaceNames())) {
                    /** If this is a suite, we get the actions from the suite */
                    return (new $action($this))->getActions($this);
                } else {
                    return $action;
                }
            })->flatten()->toArray();

        return array_merge(
            config("resource-progress.{$suite}", []),
            $suite_actions,
        );
    }

    public static function getResourceProgressSuites(): Collection
    {
        $reflectionClass = new ReflectionClass(self::class);
        $suites = $reflectionClass->getAttributes();

        return collect($suites)
            ->map(function ($attribute) {
                return $attribute->newInstance();
            })
            ->filter(function ($attribute) {
                return $attribute instanceof ResourceProgressSuiteInterface;
            })->mapWithKeys(function ($attribute) {
                $suite_type = $attribute->getSuiteKey();
                $suite_name = $attribute->getSuiteName();
                return [$suite_type => [
                    'name' => $suite_name,
                    'attribute' => $attribute,
                ]];
            });
    }
}
