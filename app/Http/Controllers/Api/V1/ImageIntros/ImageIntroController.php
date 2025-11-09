<?php declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\ImageIntros;

use App\Domain\ImageIntros\Models\ImageIntro;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ImageIntros\PublishImageIntroRequest;
use App\Http\Requests\Api\V1\ImageIntros\StoreImageIntroRequest;
use App\Http\Requests\Api\V1\ImageIntros\UpdateImageIntroRequest;
use App\Http\Resources\Api\V1\ImageIntros\ImageIntroResource;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ImageIntroController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', ImageIntro::class);

        $query = ImageIntro::query()
            ->with([
                'videos',
                'documents.media', // Eager load media to avoid N+1 queries
                'books.media', // Eager load media to avoid N+1 queries
                'papers',
            ])
            ->when($request->boolean('published', null), fn ($q, $published) => $published ? $q->published() : $q)
            ->search($request->string('q')->toString())
            ->status($request->string('status')->toString());

        // sort
        $sort = $request->string('sort')->toString();
        $order = $request->string('order')->toString();
        
        if ($sort) {
            $column = ltrim($sort, '-');
            // Use order parameter if provided, otherwise use sort prefix
            if ($order !== null && in_array(strtolower($order), ['asc', 'desc'], true)) {
                $direction = strtolower($order);
            } else {
                $direction = str_starts_with($sort, '-') ? 'desc' : 'asc';
            }
            $query->orderBy($column, $direction);
        } else {
            // If no sort specified, use order parameter or default to desc
            if ($order !== null && in_array(strtolower($order), ['asc', 'desc'], true)) {
                $direction = strtolower($order);
                $query->orderBy('published_at', $direction)->orderBy('id', $direction);
            } else {
                $query->orderByDesc('published_at')->orderByDesc('id');
            }
        }

        $intros = $query->paginate($request->integer('per_page', 15));

        return ImageIntroResource::collection($intros)->additional([
            'message' => '',
            'errors' => null,
        ]);
    }

    public function store(StoreImageIntroRequest $request)
    {
        $this->authorize('create', ImageIntro::class);

        $data = $request->validated();
        $intro = ImageIntro::query()->create($data);
        $intro->load(['videos', 'documents', 'books', 'papers']);

        return (new ImageIntroResource($intro))
            ->additional(['message' => 'Created', 'errors' => null]);
    }

    public function show(int $id)
    {
        $intro = ImageIntro::query()->with(['videos', 'documents', 'books', 'papers'])->findOrFail($id);
        $this->authorize('view', $intro);

        return (new ImageIntroResource($intro))
            ->additional(['message' => '', 'errors' => null]);
    }

    public function update(UpdateImageIntroRequest $request, int $id)
    {
        $intro = ImageIntro::query()->with(['videos', 'documents', 'books', 'papers'])->findOrFail($id);
        $this->authorize('update', $intro);

        $intro->fill($request->validated());
        $intro->save();
        $intro->load(['videos', 'documents', 'books', 'papers']);

        return (new ImageIntroResource($intro))
            ->additional(['message' => 'Updated', 'errors' => null]);
    }

    public function destroy(int $id)
    {
        $intro = ImageIntro::query()->findOrFail($id);
        $this->authorize('delete', $intro);
        $intro->delete();

        return response()->json(['data' => null, 'message' => 'Deleted', 'errors' => null]);
    }

    public function publish(PublishImageIntroRequest $request, int $id)
    {
        $intro = ImageIntro::query()->with(['videos', 'documents', 'books', 'papers'])->findOrFail($id);
        $this->authorize('update', $intro);

        $status = $request->string('status')->toString();
        $intro->status = $status;
        if ($status === 'published') {
            $intro->published_at = $request->date('published_at') ?? now();
        } else {
            $intro->published_at = null;
        }
        $intro->save();
        $intro->load(['videos', 'documents', 'books', 'papers']);

        return (new ImageIntroResource($intro))
            ->additional(['message' => 'Publish status updated', 'errors' => null]);
    }
}


