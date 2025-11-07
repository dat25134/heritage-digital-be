<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Videos;

use App\Domain\Videos\Models\Video;
use App\Http\Controllers\Controller;
use App\Http\Requests\Videos\UpdateVideoOrderRequest;
use App\Http\Requests\Videos\UpdateVideoRequest;
use App\Http\Resources\Videos\VideoCollection;
use App\Http\Resources\Videos\VideoResource;
use App\Jobs\ProcessUploadedVideo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\File;

class VideoController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Video::class);

        $query = Video::query();

        if ($introId = (int) $request->integer('image_intro_id')) {
            $query->where('image_intro_id', $introId);
        }

        if ($status = $request->string('status')->toString()) {
            $query->where('status', $status);
        }

        if ($sourceType = $request->string('source_type')->toString()) {
            $query->where('source_type', $sourceType);
        }

        if ($q = $request->string('q')->toString()) {
            $query->where('title', 'like', '%' . $q . '%');
        }

        $sort = $request->string('sort')->toString() ?: '-published_at';
        $this->applySort($query, $sort);

        $perPage = max(1, (int) $request->integer('per_page', 15));
        $paginator = $query->paginate($perPage);

        $collection = new VideoCollection($paginator);
        return response()->json(array_merge($collection->toArray($request), [
            'meta' => $collection->with($request)['meta'],
            'message' => '',
            'errors' => null,
        ]));
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', Video::class);

        $sourceType = $request->input('source_type');

        if ($sourceType === 'upload') {
            $maxSizeMb = (int) (config('media.video_max_mb', 512));
            $validated = $request->validate([
                'title' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'source_type' => ['required', 'in:upload'],
                'video' => ['required', File::types(['video/*'])->max($maxSizeMb * 1024)],
                'status' => ['nullable', 'in:draft,published,archived'],
                'published_at' => ['nullable', 'date'],
            ]);

            $file = $request->file('video');
            $now = now();
            $dir = 'videos/' . $now->format('Y') . '/' . $now->format('m');
            $filename = Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs($dir, $filename, 'public');

            $video = new Video();
            $video->fill([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'source_type' => 'upload',
                'file_path' => $path,
                'mime' => $file->getMimeType() ?: null,
                'size_bytes' => $file->getSize() ?: null,
                'status' => $validated['status'] ?? 'draft',
                'published_at' => $validated['published_at'] ?? null,
                'image_intro_id' => (int) $request->integer('image_intro_id') ?: null,
            ]);
            $video->save();

            dispatch(new ProcessUploadedVideo($video->id));

            return response()->json([
                'data' => VideoResource::make($video),
                'meta' => (object) [],
                'message' => 'Video created (upload)'
                , 'errors' => null,
            ], 201);
        }

        if ($sourceType === 'external') {
            $maxThumbMb = (int) (config('media.thumbnail_max_mb', 10));
            $validated = $request->validate([
                'title' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'source_type' => ['required', 'in:external'],
                'external_url' => ['required', 'url'],
                'thumbnail' => ['sometimes', 'image', 'mimes:jpg,jpeg,png,webp', 'max:' . ($maxThumbMb * 1024)],
                'status' => ['nullable', 'in:draft,published,archived'],
                'published_at' => ['nullable', 'date'],
            ]);

            $video = new Video();
            $video->fill([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'source_type' => 'external',
                'external_url' => $validated['external_url'],
                'status' => $validated['status'] ?? 'draft',
                'published_at' => $validated['published_at'] ?? null,
                'image_intro_id' => (int) $request->integer('image_intro_id') ?: null,
            ]);
            $video->save();

            if ($request->hasFile('thumbnail')) {
                $video->addMediaFromRequest('thumbnail')->toMediaCollection('thumbnail');
            }

            return response()->json([
                'data' => VideoResource::make($video),
                'meta' => (object) [],
                'message' => 'Video created (external)',
                'errors' => null,
            ], 201);
        }

        return response()->json([
            'data' => (object) [],
            'meta' => (object) [],
            'message' => 'Invalid source_type',
            'errors' => [ 'source_type' => ['source_type must be upload or external'] ],
        ], 422);
    }

    // Intro-scoped helpers
    public function indexByIntro(Request $request, int $imageIntroId): JsonResponse
    {
        $request->merge(['image_intro_id' => $imageIntroId]);
        return $this->index($request);
    }

    public function storeByIntro(Request $request, int $imageIntroId): JsonResponse
    {
        $request->merge(['image_intro_id' => $imageIntroId]);
        return $this->store($request);
    }

    public function show(int $id): JsonResponse
    {
        $video = Video::findOrFail($id);
        $this->authorize('view', $video);

        return response()->json([
            'data' => VideoResource::make($video),
            'meta' => (object) [],
            'message' => '',
            'errors' => null,
        ]);
    }

    public function update(UpdateVideoRequest $request, int $id): JsonResponse
    {
        $video = Video::findOrFail($id);
        $this->authorize('update', $video);

        $video->fill($request->validated());
        $video->save();

        return response()->json([
            'data' => VideoResource::make($video),
            'meta' => (object) [],
            'message' => 'Video updated',
            'errors' => null,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $video = Video::findOrFail($id);
        $this->authorize('delete', $video);
        $video->delete();

        return response()->json([
            'data' => (object) [],
            'meta' => (object) [],
            'message' => 'Video deleted',
            'errors' => null,
        ]);
    }

    public function publish(int $id): JsonResponse
    {
        $video = Video::findOrFail($id);
        $this->authorize('update', $video);

        $video->status = 'published';
        if ($video->published_at === null) {
            $video->published_at = now();
        }
        $video->save();

        return response()->json([
            'data' => VideoResource::make($video),
            'meta' => (object) [],
            'message' => 'Video published',
            'errors' => null,
        ]);
    }

    public function uploadThumbnail(Request $request, int $id): JsonResponse
    {
        $video = Video::findOrFail($id);
        $this->authorize('update', $video);

        $maxThumbMb = (int) (config('media.thumbnail_max_mb', 10));
        $request->validate([
            'thumbnail' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:' . ($maxThumbMb * 1024)],
        ]);

        $video->clearMediaCollection('thumbnail');
        $video->addMediaFromRequest('thumbnail')->toMediaCollection('thumbnail');

        return response()->json([
            'data' => VideoResource::make($video),
            'meta' => (object) [],
            'message' => 'Thumbnail updated',
            'errors' => null,
        ]);
    }

    public function updateOrder(UpdateVideoOrderRequest $request): JsonResponse
    {
        $orders = $request->validated()['orders'];
        $ids = collect($orders)->pluck('id')->all();
        $videos = Video::whereIn('id', $ids)->get()->keyBy('id');

        foreach ($orders as $row) {
            if (isset($videos[$row['id']])) {
                $videos[$row['id']]->sort_order = (int) $row['sort_order'];
                $videos[$row['id']]->save();
            }
        }

        return response()->json([
            'data' => (object) [],
            'meta' => (object) [],
            'message' => 'Order updated',
            'errors' => null,
        ]);
    }

    private function applySort($query, string $sort): void
    {
        // sort string like: -published_at, created_at, title
        $direction = str_starts_with($sort, '-') ? 'desc' : 'asc';
        $column = ltrim($sort, '-');

        $allowed = ['published_at', 'created_at', 'title', 'sort_order'];
        if (!in_array($column, $allowed, true)) {
            $column = 'published_at';
            $direction = 'desc';
        }

        $query->orderBy($column, $direction);
    }
}


