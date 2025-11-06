<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Posts;

use App\Domain\Posts\Models\Post;
use App\Domain\Posts\Resources\PostResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Posts\PublishPostRequest;
use App\Http\Requests\Posts\StorePostRequest;
use App\Http\Requests\Posts\UpdatePostRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Post::class);

        $query = Post::query()->with(['author']);

        if ($status = $request->string('status')->toString()) {
            $query->where('status', $status);
        }

        if ($authorId = (int) $request->integer('author_id')) {
            $query->where('author_id', $authorId);
        }

        if ($q = $request->string('q')->toString()) {
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', '%' . $q . '%')
                    ->orWhere('excerpt', 'like', '%' . $q . '%');
            });
        }

        if ($dateFrom = $request->string('date_from')->toString()) {
            $query->whereDate('published_at', '>=', $dateFrom);
        }
        if ($dateTo = $request->string('date_to')->toString()) {
            $query->whereDate('published_at', '<=', $dateTo);
        }

        $sort = $request->string('sort')->toString() ?: '-published_at';
        $this->applySort($query, $sort);

        $perPage = max(1, (int) $request->integer('per_page', 15));
        $paginator = $query->paginate($perPage);

        $data = $paginator->getCollection()->map(fn ($post) => PostResource::make($post));

        return response()->json([
            'data' => $data,
            'meta' => [
                'pagination' => [
                    'current_page' => $paginator->currentPage(),
                    'per_page' => $paginator->perPage(),
                    'total' => $paginator->total(),
                    'last_page' => $paginator->lastPage(),
                ],
            ],
            'message' => '',
            'errors' => null,
        ]);
    }

    public function store(StorePostRequest $request): JsonResponse
    {
        $this->authorize('create', Post::class);

        $data = $request->validated();

        if (empty($data['slug'])) {
            $data['slug'] = $this->generateUniqueSlug($data['title']);
        }

        $post = new Post();
        $post->fill($data);
        if (($data['status'] ?? 'draft') === 'published' && empty($data['published_at'])) {
            $post->published_at = now();
        }
        if (empty($post->author_id)) {
            $post->author_id = $request->user()?->id;
        }
        $post->save();

        if (function_exists('activity')) {
            activity('posts')->performedOn($post)->withProperties([
                'attributes' => $post->getAttributes(),
            ])->log('created');
        }

        return response()->json([
            'data' => PostResource::make($post),
            'meta' => (object) [],
            'message' => 'Post created',
            'errors' => null,
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $post = Post::with(['author'])->findOrFail($id);
        $this->authorize('view', $post);

        return response()->json([
            'data' => PostResource::make($post),
            'meta' => (object) [],
            'message' => '',
            'errors' => null,
        ]);
    }

    public function update(UpdatePostRequest $request, int $id): JsonResponse
    {
        $post = Post::findOrFail($id);
        $this->authorize('update', $post);

        $data = $request->validated();
        if (!empty($data['title']) && empty($data['slug'])) {
            $data['slug'] = $this->generateUniqueSlug($data['title'], $post->id);
        }

        $post->fill($data);
        if (($data['status'] ?? $post->status) === 'published' && $post->published_at === null) {
            $post->published_at = now();
        }
        if (($data['status'] ?? $post->status) === 'draft') {
            // Only nullify if explicitly set to draft
            if (array_key_exists('status', $data)) {
                $post->published_at = null;
            }
        }
        $post->save();

        if (function_exists('activity')) {
            activity('posts')->performedOn($post)->withProperties([
                'attributes' => $post->getAttributes(),
            ])->log('updated');
        }

        return response()->json([
            'data' => PostResource::make($post),
            'meta' => (object) [],
            'message' => 'Post updated',
            'errors' => null,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $post = Post::findOrFail($id);
        $this->authorize('delete', $post);
        $post->delete();

        if (function_exists('activity')) {
            activity('posts')->performedOn($post)->log('deleted');
        }

        return response()->json([
            'data' => (object) [],
            'meta' => (object) [],
            'message' => 'Post deleted',
            'errors' => null,
        ]);
    }

    public function publish(PublishPostRequest $request, int $id): JsonResponse
    {
        $post = Post::findOrFail($id);
        $this->authorize('publish', $post);

        $publish = (bool) $request->validated()['publish'];
        if ($publish) {
            $post->status = 'published';
            if ($post->published_at === null) {
                $post->published_at = now();
            }
        } else {
            $post->status = 'draft';
            $post->published_at = null;
        }
        $post->save();

        if (function_exists('activity')) {
            activity('posts')->performedOn($post)->withProperties([
                'publish' => $publish,
            ])->log($publish ? 'published' : 'unpublished');
        }

        return response()->json([
            'data' => PostResource::make($post),
            'meta' => (object) [],
            'message' => $publish ? 'Post published' : 'Post unpublished',
            'errors' => null,
        ]);
    }

    private function applySort($query, string $sort): void
    {
        $direction = str_starts_with($sort, '-') ? 'desc' : 'asc';
        $column = ltrim($sort, '-');

        $allowed = ['published_at', 'created_at', 'title'];
        if (!in_array($column, $allowed, true)) {
            $column = 'published_at';
            $direction = 'desc';
        }

        $query->orderBy($column, $direction);
    }

    private function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 1;
        while (Post::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base . '-' . $i;
            $i++;
        }
        return $slug;
    }
}


