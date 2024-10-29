<?php

namespace Marshmallow\ResourceProgress\Actions;

use Marshmallow\ResourceProgress\Actions\ResourceProgressAction;
use Marshmallow\ResourceProgress\Contracts\ResourceProgressSuiteInterface;

class FieldFilled extends ResourceProgressAction
{
    public function handle(ResourceProgressSuiteInterface $suite): void
    {
        $fields = $suite->fields;
        $magic_getter_method = $this->resource->getProgressMagicMethodName(
            suite: $suite->getSuiteKey(),
            prepend: 'get',
            append: 'RequiredFields'
        );

        if (method_exists($this->resource, $magic_getter_method)) {
            $fields = $this->resource->{$magic_getter_method}();
        }

        collect($fields)
            ->each(function ($field) {
                $this->incrementCheckCount();
                if (!$this->resource->{$field}) {
                    $this->fail(
                        __("The field :field is not filled.", ['field' => $field])
                    );
                }
            });
    }
}
