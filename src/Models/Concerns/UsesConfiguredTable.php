<?php

namespace NexCoreIT\BangladeshUniversities\Models\Concerns;

trait UsesConfiguredTable
{
    public function getTable(): string
    {
        return config($this->tableConfigKey, $this->fallbackTable);
    }
}
