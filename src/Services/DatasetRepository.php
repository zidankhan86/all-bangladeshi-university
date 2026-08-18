<?php

namespace NexCoreIT\BangladeshUniversities\Services;

use RuntimeException;

class DatasetRepository
{
    public function universities(?string $type = null): array
    {
        $path = $this->path('universities.json');
        $records = $this->readJson($path)['universities'] ?? [];

        if ($type === null) {
            return $records;
        }

        return array_values(array_filter($records, fn (array $record) => ($record['type'] ?? null) === $type));
    }

    public function locations(): array
    {
        return $this->readJson($this->path('locations.json'))['locations'] ?? [];
    }

    protected function path(string $file): string
    {
        $configured = config('bangladesh-universities.dataset_path');

        return $configured
            ? rtrim($configured, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.$file
            : dirname(__DIR__, 2).DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.$file;
    }

    protected function readJson(string $path): array
    {
        if (! is_file($path)) {
            throw new RuntimeException("Dataset file not found: {$path}");
        }

        $decoded = json_decode((string) file_get_contents($path), true);

        if (! is_array($decoded)) {
            throw new RuntimeException("Dataset file contains invalid JSON: {$path}");
        }

        return $decoded;
    }
}
