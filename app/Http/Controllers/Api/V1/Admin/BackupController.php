<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Domain\Backup\Actions\CreateBackupAction;
use App\Domain\Backup\Actions\DeleteBackupAction;
use App\Domain\Backup\Actions\GetBackupInfoAction;
use App\Domain\Backup\Actions\ListBackupsAction;
use App\Domain\Backup\Actions\RestoreBackupAction;
use App\Domain\Backup\Models\Backup;
use App\Domain\Backup\Resources\BackupResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\RestoreBackupRequest;
use App\Http\Requests\Api\V1\Admin\StoreBackupRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BackupController extends Controller
{
    public function __construct(
        private readonly ListBackupsAction $listBackupsAction,
        private readonly CreateBackupAction $createBackupAction,
        private readonly GetBackupInfoAction $getBackupInfoAction,
        private readonly DeleteBackupAction $deleteBackupAction,
        private readonly RestoreBackupAction $restoreBackupAction
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Backup::class);

        $status = $request->string('status')->toString() ?: null;
        $type = $request->string('type')->toString() ?: null;
        $sort = $request->string('sort', 'created_at')->toString();
        $direction = str_starts_with($sort, '-') ? 'desc' : 'asc';
        $sortColumn = ltrim($sort, '-');
        $perPage = $request->integer('per_page', 15);

        $backups = $this->listBackupsAction->execute(
            status: $status,
            type: $type,
            sort: $sortColumn,
            direction: $direction,
            perPage: $perPage
        );

        $data = $backups->through(fn ($backup) => new BackupResource($backup));

        return response()->json([
            'data' => $data->items(),
            'meta' => [
                'pagination' => [
                    'total' => $backups->total(),
                    'per_page' => $backups->perPage(),
                    'current_page' => $backups->currentPage(),
                    'last_page' => $backups->lastPage(),
                    'from' => $backups->firstItem(),
                    'to' => $backups->lastItem(),
                ],
            ],
            'message' => '',
            'errors' => null,
        ]);
    }

    public function store(StoreBackupRequest $request): JsonResponse
    {
        $this->authorize('create', Backup::class);

        $validated = $request->validated();
        $type = $validated['type'] ?? 'full';

        try {
            $backup = $this->createBackupAction->execute(
                type: $type,
                userId: $request->user()->id
            );

            return (new BackupResource($backup))
                ->response()
                ->setStatusCode(201)
                ->withHeaders([
                    'X-Message' => 'Backup creation initiated',
                ]);
        } catch (\Exception $e) {
            return response()->json([
                'data' => null,
                'message' => 'Failed to create backup',
                'errors' => [
                    'backup' => [$e->getMessage()],
                ],
            ], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        $backup = Backup::findOrFail($id);
        $this->authorize('view', $backup);

        $backup = $this->getBackupInfoAction->execute($backup);

        return response()->json([
            'data' => new BackupResource($backup),
            'meta' => (object) [],
            'message' => '',
            'errors' => null,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $backup = Backup::findOrFail($id);
        $this->authorize('delete', $backup);

        try {
            $this->deleteBackupAction->execute($backup);

            return response()->json([
                'data' => null,
                'message' => 'Backup deleted successfully',
                'errors' => null,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'data' => null,
                'message' => 'Failed to delete backup',
                'errors' => [
                    'backup' => [$e->getMessage()],
                ],
            ], 500);
        }
    }

    public function restore(RestoreBackupRequest $request, int $id): JsonResponse
    {
        $backup = Backup::findOrFail($id);
        $this->authorize('restore', $backup);

        $validated = $request->validated();

        try {
            $backup = $this->restoreBackupAction->execute(
                backup: $backup,
                confirmationToken: $validated['confirmation'],
                userId: $request->user()->id
            );

            return response()->json([
                'data' => new BackupResource($backup),
                'message' => 'Restore initiated. This may take several minutes.',
                'errors' => null,
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'data' => null,
                'message' => 'Restore failed',
                'errors' => [
                    'restore' => [$e->getMessage()],
                ],
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'data' => null,
                'message' => 'Restore failed',
                'errors' => [
                    'restore' => ['An error occurred during restore. Please check logs.'],
                ],
            ], 500);
        }
    }

    public function getConfirmationToken(int $id): JsonResponse
    {
        $backup = Backup::findOrFail($id);
        $this->authorize('restore', $backup);

        if ($backup->status !== 'completed') {
            return response()->json([
                'data' => null,
                'message' => 'Can only get confirmation token for completed backups',
                'errors' => null,
            ], 400);
        }

        $token = $this->restoreBackupAction->generateConfirmationToken($backup);

        return response()->json([
            'data' => [
                'confirmation_token' => $token,
                'backup_id' => $backup->id,
                'backup_name' => $backup->name,
            ],
            'message' => 'Use this token to confirm restore operation',
            'errors' => null,
        ]);
    }
}

