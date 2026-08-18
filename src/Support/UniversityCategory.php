<?php

namespace NexCoreIT\BangladeshUniversities\Support;

final class UniversityCategory
{
    public const GENERAL = 'general';
    public const ENGINEERING = 'engineering';
    public const MEDICAL = 'medical';
    public const AGRICULTURAL = 'agricultural';
    public const SCIENCE_TECHNOLOGY = 'science_technology';
    public const TEXTILE = 'textile';
    public const AVIATION = 'aviation';
    public const MARITIME = 'maritime';
    public const OPEN = 'open';
    public const OTHER = 'other';

    public static function values(): array
    {
        return [
            self::GENERAL,
            self::ENGINEERING,
            self::MEDICAL,
            self::AGRICULTURAL,
            self::SCIENCE_TECHNOLOGY,
            self::TEXTILE,
            self::AVIATION,
            self::MARITIME,
            self::OPEN,
            self::OTHER,
        ];
    }
}
