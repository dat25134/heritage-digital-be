<?php

declare(strict_types=1);

namespace App\Domain\Backup\DTOs;

class BackupDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $disk,
        public readonly string $path,
        public readonly int $size,
        public readonly string $type,
        public readonly ?string $createdAt = null,
    ) {}
}

