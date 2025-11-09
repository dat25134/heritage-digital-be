<?php

declare(strict_types=1);

namespace App\Domain\Backup\Actions;

use App\Domain\Backup\Models\Backup;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListBackupsAction
{
    public function execute(
        ?string $status = null,
        ?string $type = null,
        ?string $sort = 'created_at',
        string $direction = 'desc',
        int $perPage = 15
    ): LengthAwarePaginator {
        $query = Backup::query()
            ->filterByStatus($status)
            ->filterByType($type);

        // Apply sorting
        if ($sort === 'size') {
            $query->orderBySize($direction);
        } else {
            $query->orderByCreatedAt($direction);
        }

        return $query->paginate($perPage);
    }
}

