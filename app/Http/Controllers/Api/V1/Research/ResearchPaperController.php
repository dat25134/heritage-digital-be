<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Research;

use App\Domain\ResearchPapers\Models\ResearchPaper;
use App\Domain\ResearchPapers\Resources\ResearchPaperResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Research\StoreResearchPaperRequest;
use App\Http\Requests\Api\V1\Research\UpdateResearchPaperRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Str;

class ResearchPaperController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = ResearchPaper::query()
            ->search($request->string('q')->toString())
            ->filterByAuthor($request->string('author')->toString())
            ->filterByYear($request->integer('year'))
            ->filterByDoi($request->string('doi')->toString());

        if ($introId = (int) $request->integer('image_intro_id')) {
            $query->where('image_intro_id', $introId);
        }

        if ($request->boolean('published')) {
            $query->published();
        }
        if ($status = $request->string('status')->toString()) {
            $query->where('status', $status);
        }

        // Sorting
        $sort = $request->string('sort')->toString() ?: '-published_at,-id';
        foreach (explode(',', $sort) as $sortField) {
            $sortField = trim($sortField);
            if ($sortField === '') {
                continue;
            }
            $direction = Str::startsWith($sortField, '-') ? 'desc' : 'asc';
            $column = ltrim($sortField, '-');
            if (in_array($column, ['id', 'year', 'published_at', 'created_at'], true)) {
                $query->orderBy($column, $direction);
            }
        }

        $perPage = (int) max(1, min(100, (int) $request->integer('per_page') ?: 15));
        $paginator = $query->with('media')->paginate($perPage)->appends($request->query());
        return ResearchPaperResource::collection($paginator);
    }

    public function store(StoreResearchPaperRequest $request): ResearchPaperResource
    {
        $data = $request->validated();
        // Ensure slug uniqueness or generate from title
        $data['slug'] = $data['slug'] ?? Str::slug($data['title']);
        $data['slug'] = $this->uniqueSlug($data['slug']);

        $paper = ResearchPaper::create($data);
        return new ResearchPaperResource($paper);
    }

    // Intro-scoped helpers
    public function indexByIntro(Request $request, int $imageIntroId)
    {
        $request->merge(['image_intro_id' => $imageIntroId]);
        return $this->index($request);
    }

    public function storeByIntro(StoreResearchPaperRequest $request, int $imageIntroId): ResearchPaperResource
    {
        $data = array_merge($request->validated(), ['image_intro_id' => $imageIntroId]);
        // Ensure slug uniqueness or generate from title
        $data['slug'] = $data['slug'] ?? Str::slug($data['title']);
        $data['slug'] = $this->uniqueSlug($data['slug']);
        $paper = ResearchPaper::create($data);
        return new ResearchPaperResource($paper);
    }

    public function show(string $idOrSlug): ResearchPaperResource
    {
        $paper = is_numeric($idOrSlug)
            ? ResearchPaper::with('media')->findOrFail((int) $idOrSlug)
            : ResearchPaper::with('media')->where('slug', $idOrSlug)->firstOrFail();

        return new ResearchPaperResource($paper);
    }

    public function update(UpdateResearchPaperRequest $request, int $id): ResearchPaperResource
    {
        $paper = ResearchPaper::findOrFail($id);
        $data = $request->validated();

        if (isset($data['slug']) && $data['slug'] !== $paper->slug) {
            $data['slug'] = $this->uniqueSlug($data['slug'], $paper->id);
        }

        $paper->update($data);
        return new ResearchPaperResource($paper);
    }

    public function destroy(int $id): JsonResponse
    {
        $paper = ResearchPaper::findOrFail($id);
        $paper->delete();
        return response()->json(['message' => 'deleted']);
    }

    public function publish(Request $request, int $id): ResearchPaperResource
    {
        $paper = ResearchPaper::findOrFail($id);
        $published = (bool) $request->boolean('published', true);
        if ($published) {
            $paper->status = 'published';
            if ($paper->published_at === null) {
                $paper->published_at = now();
            }
        } else {
            $paper->status = 'draft';
        }
        $paper->save();
        return new ResearchPaperResource($paper);
    }

    private function uniqueSlug(string $baseSlug, ?int $ignoreId = null): string
    {
        $slug = Str::slug($baseSlug);
        $original = $slug;
        $i = 1;
        while (ResearchPaper::where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $original . '-' . $i++;
        }
        return $slug;
    }
}


