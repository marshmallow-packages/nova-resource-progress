<?php

namespace Marshmallow\ResourceProgress;

use Illuminate\Support\Arr;
use Laravel\Nova\Fields\Field;

class ResourceProgress extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'resource-progress';

    public static function make(...$arguments)
    {
        $name = $arguments[0] ?? __('Progress');
        $resource = $arguments[1] ?? Arr::get(debug_backtrace(), '1.class');
        $suites = $resource::$model::getResourceProgressSuites();

        $field = new static($name, 'resource_progress');
        $field->withMeta([
            'suites' => $suites,
            'no_data_label' => __('No data'),
        ]);

        $field->fillUsing(function () {
            /** Don't save the content of this field. */
        });

        $field->setCircleSize(20);
        $field->setStrokeWidth(3);
        $field->setProgressBarHeight(15);

        $field->setColorRanges([
            0 => '#ef4444',
            10 => '#f87171',
            20 => '#fca5a5',
            30 => '#fef08a',
            40 => '#fde047',
            50 => '#facc15',
            60 => '#eab308',
            70 => '#bbf7d0',
            80 => '#86efac',
            90 => '#22c55e',
            100 => '#16a34a',
        ]);

        return $field;
    }

    public function setColorRanges(array $color_ranges): self
    {
        return $this->withMeta([
            'color_ranges' => $color_ranges,
        ]);
    }

    public function setCircleSize(int $circle_size): self
    {
        return $this->withMeta([
            'circle_size' => $circle_size,
        ]);
    }

    public function setStrokeWidth(int $stroke_width): self
    {
        return $this->withMeta([
            'stroke_width' => $stroke_width,
        ]);
    }

    public function setProgressBarHeight(int $progress_bar_height): self
    {
        return $this->withMeta([
            'progress_bar_height' => $progress_bar_height,
        ]);
    }
}
