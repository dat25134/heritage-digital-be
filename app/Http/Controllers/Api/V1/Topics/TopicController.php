<?php declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Topics;

use App\Domain\Topics\Models\Topic;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Topics\AttachMediaToTopicRequest;
use App\Http\Requests\Api\V1\Topics\StoreTopicRequest;
use App\Http\Requests\Api\V1\Topics\UpdateTopicMediaOrderRequest;
use App\Http\Requests\Api\V1\Topics\UpdateTopicRequest;
use App\Http\Resources\Api\V1\Topics\TopicResource;
use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class TopicController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Topic::class);

        $query = Topic::query()
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = $request->string('q')->toString();
                $q->where(function ($sub) use ($term) {
                    $sub->where('name', 'like', "%{$term}%")
                        ->orWhere('slug', 'like', "%{$term}%");
                });
            });

        $sort = $request->string('sort')->toString();
        if ($sort) {
            $direction = str_starts_with($sort, '-') ? 'desc' : 'asc';
            $column = ltrim($sort, '-');
            $query->orderBy($column, $direction);
        } else {
            $query->orderByDesc('id');
        }

        $topics = $query->paginate($request->integer('per_page', 15));

        return TopicResource::collection($topics)->additional([
            'message' => '',
            'errors' => null,
        ]);
    }

    public function store(StoreTopicRequest $request)
    {
        $this->authorize('create', Topic::class);

        $data = $request->validated();
        if (empty($data['slug'])) {
            $data['slug'] = Topic::uniqueSlug($data['name']);
        }

        $topic = Topic::query()->create($data);

        return (new TopicResource($topic))
            ->additional(['message' => 'Created', 'errors' => null]);
    }

    public function show(int $id)
    {
        $topic = Topic::query()->findOrFail($id);
        $this->authorize('view', $topic);

        return (new TopicResource($topic))
            ->additional(['message' => '', 'errors' => null]);
    }

    public function update(UpdateTopicRequest $request, int $id)
    {
        $topic = Topic::query()->findOrFail($id);
        $this->authorize('update', $topic);

        $topic->fill($request->validated());
        $topic->save();

        return (new TopicResource($topic))
            ->additional(['message' => 'Updated', 'errors' => null]);
    }

    public function destroy(int $id)
    {
        $topic = Topic::query()->findOrFail($id);
        $this->authorize('delete', $topic);
        $topic->delete();

        return response()->json(['data' => null, 'message' => 'Deleted', 'errors' => null]);
    }

    // Media operations
    public function attachMedia(AttachMediaToTopicRequest $request, int $id)
    {
        $topic = Topic::query()->findOrFail($id);
        $this->authorize('update', $topic);

        $mediaIds = $request->validated('media_ids');

        DB::transaction(function () use ($id, $mediaIds) {
            // Determine current max sort_order
            $max = (int) DB::table('topic_media_orders')
                ->where('topic_id', $id)
                ->max('sort_order');

            $inserts = [];
            foreach ($mediaIds as $offset => $mediaId) {
                $inserts[] = [
                    'topic_id' => $id,
                    'media_id' => $mediaId,
                    'sort_order' => $max + $offset + 1,
                ];
            }

            if (!empty($inserts)) {
                DB::table('topic_media_orders')->upsert(
                    $inserts,
                    ['topic_id', 'media_id'],
                    ['sort_order']
                );
            }
        });

        return response()->json(['data' => null, 'message' => 'Media attached', 'errors' => null]);
    }

    public function detachMedia(AttachMediaToTopicRequest $request, int $id)
    {
        $topic = Topic::query()->findOrFail($id);
        $this->authorize('update', $topic);

        $mediaIds = $request->validated('media_ids');

        DB::table('topic_media_orders')
            ->where('topic_id', $id)
            ->whereIn('media_id', $mediaIds)
            ->delete();

        return response()->json(['data' => null, 'message' => 'Media detached', 'errors' => null]);
    }

    public function listMedia(Request $request, int $id)
    {
        $topic = Topic::query()->findOrFail($id);
        $this->authorize('view', $topic);

        $mediaQuery = Media::query()
            ->join('topic_media_orders as tmo', 'tmo.media_id', '=', 'media.id')
            ->where('tmo.topic_id', $id)
            ->orderBy('tmo.sort_order')
            ->select('media.*');

        $media = $mediaQuery->paginate($request->integer('per_page', 50));

        return response()->json([
            'data' => $media->items(),
            'meta' => [
                'pagination' => [
                    'total' => $media->total(),
                    'per_page' => $media->perPage(),
                    'current_page' => $media->currentPage(),
                    'last_page' => $media->lastPage(),
                ],
            ],
            'message' => '',
            'errors' => null,
        ]);
    }

    public function updateMediaOrder(UpdateTopicMediaOrderRequest $request, int $id)
    {
        $topic = Topic::query()->findOrFail($id);
        $this->authorize('update', $topic);

        $orderedIds = $request->validated('media_ids');

        // Validate all provided media_ids belong to this topic
        $existingIds = DB::table('topic_media_orders')
            ->where('topic_id', $id)
            ->whereIn('media_id', $orderedIds)
            ->pluck('media_id')
            ->all();

        $diff = array_values(array_diff($orderedIds, $existingIds));
        if (!empty($diff)) {
            return response()->json([
                'data' => null,
                'message' => 'Some media do not belong to this topic',
                'errors' => ['media_ids' => $diff],
            ], 422);
        }

        DB::transaction(function () use ($id, $orderedIds) {
            foreach ($orderedIds as $index => $mediaId) {
                DB::table('topic_media_orders')
                    ->where('topic_id', $id)
                    ->where('media_id', $mediaId)
                    ->update(['sort_order' => $index + 1]);
            }
        });

        return response()->json(['data' => null, 'message' => 'Order updated', 'errors' => null]);
    }
}


