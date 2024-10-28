<?php

namespace Marshmallow\ResourceProgress\Actions;

use ReflectionClass;
use Illuminate\Support\Arr;
use Marshmallow\ResourceProgress\Actions\ResourceProgressAction;
use Marshmallow\ResourceProgress\Attributes\RequiredFilledFields;
use Marshmallow\ResourceProgress\Contracts\ResourceProgressSuiteInterface;

class FieldFilled extends ResourceProgressAction
{
    public function handle(ResourceProgressSuiteInterface $suite): void
    {
        $fields = $this->getRequiredFields($suite);
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

    protected function getRequiredFields(ResourceProgressSuiteInterface $suite)
    {
        $reflectionClass = new ReflectionClass(get_class($this->resource));
        $rules = collect($reflectionClass->getAttributes(RequiredFilledFields::class))
            ->filter(fn($rule) => Arr::get($rule->getArguments(), 'suite') == $suite->getSuiteKey())
            ->first();

        return Arr::get($rules->getArguments(), 'fields');
    }
}
