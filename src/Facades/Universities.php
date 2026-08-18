<?php

namespace NexCoreIT\BangladeshUniversities\Facades;

use Illuminate\Support\Facades\Facade;

class Universities extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'bangladesh-universities';
    }
}
