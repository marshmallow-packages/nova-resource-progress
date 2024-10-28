<?php

namespace Marshmallow\ResourceProgress\Actions;

use Illuminate\Database\Eloquent\Model;
use Marshmallow\ResourceProgress\Contracts\ResourceProgressSuiteInterface;

abstract class ResourceProgressAction
{
    protected array $messages = [];
    protected int $checks_done = 0;
    protected int $checks_failed = 0;

    public function __construct(protected Model $resource, protected array $options = [])
    {
        //
    }

    public function fail(string $failed_reason): void
    {
        $this->incrementCheckFailedCount();
        $this->messages[] = $failed_reason;
    }

    public function getFailedMessages(): array
    {
        return $this->messages;
    }

    public function incrementCheckCount(): void
    {
        $this->checks_done++;
    }

    public function getTotalCheckCount(): int
    {
        return $this->checks_done;
    }

    public function incrementCheckFailedCount(): void
    {
        $this->checks_failed++;
    }

    public function getTotalFailedCount(): int
    {
        return $this->checks_failed;
    }

    public abstract function handle(ResourceProgressSuiteInterface $suite): void;
}
