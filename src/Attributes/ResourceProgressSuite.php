<?php

namespace Marshmallow\ResourceProgress\Attributes;

use Attribute;
use Illuminate\Database\Eloquent\Model;
use Marshmallow\ResourceProgress\Contracts\ResourceProgressSuiteInterface;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class ResourceProgressSuite implements ResourceProgressSuiteInterface
{
    /**
     * Create a new attribute instance.
     *
     * @param  array|string  $classes
     * @return void
     */
    public function __construct(public string $suite, public string $name, public array $fields = []) {}

    public function getSuiteKey(): string
    {
        return $this->suite;
    }

    public function getSuiteName(): string
    {
        return $this->name ?? $this->suite;
    }

    public function getActions(Model $model): array
    {
        return $model->getProgressActions(
            $this->getSuiteKey()
        );
    }
}
