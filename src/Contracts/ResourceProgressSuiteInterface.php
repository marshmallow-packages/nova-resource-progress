<?php

namespace Marshmallow\ResourceProgress\Contracts;

use Illuminate\Database\Eloquent\Model;

interface ResourceProgressSuiteInterface
{
    public function getSuiteKey(): string;
    public function getSuiteName(): string;
    public function getActions(Model $model): array;
}
