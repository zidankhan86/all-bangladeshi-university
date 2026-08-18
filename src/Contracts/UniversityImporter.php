<?php

namespace NexCoreIT\BangladeshUniversities\Contracts;

interface UniversityImporter
{
    public function import(?string $type = null): array;
}
