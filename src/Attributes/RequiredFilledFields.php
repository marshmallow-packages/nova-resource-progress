<?php

namespace Marshmallow\ResourceProgress\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class RequiredFilledFields
{
    /**
     * Create a new attribute instance.
     *
     * @param  array|string  $classes
     * @return void
     */
    public function __construct(public string $suite, public array $fields) {}
}
