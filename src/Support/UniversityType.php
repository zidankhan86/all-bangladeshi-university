<?php

namespace NexCoreIT\BangladeshUniversities\Support;

final class UniversityType
{
    public const PUBLIC = 'public';
    public const PRIVATE = 'private';
    public const INTERNATIONAL = 'international';
    public const SPECIALIZED = 'specialized';

    public static function values(): array
    {
        return [
            self::PUBLIC,
            self::PRIVATE,
            self::INTERNATIONAL,
            self::SPECIALIZED,
        ];
    }
}
