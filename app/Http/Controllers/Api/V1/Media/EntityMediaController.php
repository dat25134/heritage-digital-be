<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Media;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Media\UploadMediaRequest;
use App\Support\EntityResolver;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class EntityMediaController extends Controller
{
    public function __construct(
        private readonly EntityResolver $resolver
    ) {
    }

    public function store(UploadMediaRequest $request, string $entity, int $id, string $collection): JsonResponse
    {
        $model = $this->resolver->resolve($entity, $id);
        $this->authorize('update', $model);

        $uploadedCount = 0;
        foreach ($request->file('files', []) as $file) {
            $model->addMedia($file)
                ->withCustomProperties([
                    'caption' => $request->string('caption')->toString(),
                    'credit' => $request->string('credit')->toString(),
                ])
                ->toMediaCollection($collection);
            $uploadedCount++;
        }

        return response()->json([
            'message' => 'Uploaded',
            'uploaded' => $uploadedCount,
        ]);
    }

    public function index(Request $request, string $entity, int $id): JsonResponse
    {
        $model = $this->resolver->resolve($entity, $id);
        $this->authorize('view', $model);

        $media = $model->media()->get()->map(function (Media $m) {
            return [
                'id' => $m->id,
                'collection' => $m->collection_name,
                'file_name' => $m->file_name,
                'mime' => $m->mime_type,
                'size' => $m->size,
                'url' => $m->getUrl(),
                'conversions' => [
                    'thumb' => $m->hasGeneratedConversion('thumb') ? $m->getUrl('thumb') : null,
                    'sm' => $m->hasGeneratedConversion('sm') ? $m->getUrl('sm') : null,
                    'md' => $m->hasGeneratedConversion('md') ? $m->getUrl('md') : null,
                    'lg' => $m->hasGeneratedConversion('lg') ? $m->getUrl('lg') : null,
                ],
                'custom_properties' => $m->custom_properties,
                'created_at' => optional($m->created_at)?->toISOString(),
                'updated_at' => optional($m->updated_at)?->toISOString(),
            ];
        });

        return response()->json(['data' => $media]);
    }

    public function destroy(Request $request, int $mediaId): JsonResponse
    {
        $media = Media::query()->findOrFail($mediaId);
        $this->authorize('delete', $media->model);

        $media->delete();

        return response()->json(['message' => 'Deleted']);
    }
}



