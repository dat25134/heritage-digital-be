<?php

declare(strict_types=1);

namespace App\Domain\Backup\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BackupResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'disk' => $this->disk,
            'path' => $this->path,
            'size' => $this->size,
            'size_human' => $this->formatBytes($this->size),
            'type' => $this->type,
            'status' => $this->status,
            'error_message' => $this->error_message,
            'created_at' => optional($this->created_at)?->toISOString(),
            'completed_at' => optional($this->completed_at)?->toISOString(),
            'created_by' => $this->creator?->name,
        ];
    }

    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        return round($bytes, 2) . ' ' . $units[$pow];
    }
}

