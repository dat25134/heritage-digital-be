<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Domain\Documents\Actions\CreateDocumentAction;
use App\Domain\Documents\Actions\DeleteDocumentAction;
use App\Domain\Documents\Actions\PublishDocumentAction;
use App\Domain\Documents\Actions\UpdateDocumentAction;
use App\Domain\Documents\Models\Document;
use App\Domain\Documents\Resources\DocumentCollection;
use App\Domain\Documents\Resources\DocumentResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Documents\DocumentIndexRequest;
use App\Http\Requests\Api\V1\Documents\PublishDocumentRequest;
use App\Http\Requests\Api\V1\Documents\StoreDocumentRequest;
use App\Http\Requests\Api\V1\Documents\UpdateDocumentRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index(DocumentIndexRequest $request): DocumentCollection
    {
        $validated = $request->validated();
        $sort = $validated['sort'] ?? 'published_at';
        $order = $validated['order'] ?? 'desc';

        $query = Document::query()
            ->search($validated['q'] ?? null)
            ->status($validated['status'] ?? null)
            ->publishedBetween($validated['published_from'] ?? null, $validated['published_to'] ?? null)
            ->createdBetween($validated['created_from'] ?? null, $validated['created_to'] ?? null);

        if (!empty($validated['image_intro_id'])) {
            $query->where('image_intro_id', (int) $validated['image_intro_id']);
        }

        if (!empty($validated['only_trashed'])) {
            $query->onlyTrashed();
        } elseif (!empty($validated['with_trashed'])) {
            $query->withTrashed();
        }

        $query->orderBy($sort, $order)->orderBy('created_at', 'desc');

        $perPage = (int)($validated['per_page'] ?? 15);
        $documents = $query->with('media')->paginate($perPage)->through(fn ($doc) => new DocumentResource($doc));

        return new DocumentCollection($documents);
    }

    public function show(Document $document): DocumentResource
    {
        $this->authorize('view', $document);
        return new DocumentResource($document);
    }

    public function store(StoreDocumentRequest $request, CreateDocumentAction $action): JsonResponse
    {
        $document = $action->handle($request->validated(), $request->user()->id);
        return (new DocumentResource($document))
            ->response()
            ->setStatusCode(201);
    }

    // Intro-scoped helpers
    public function indexByIntro(DocumentIndexRequest $request, int $imageIntroId): DocumentCollection
    {
        $request->merge(['image_intro_id' => $imageIntroId]);
        return $this->index($request);
    }

    public function storeByIntro(StoreDocumentRequest $request, int $imageIntroId, CreateDocumentAction $action): JsonResponse
    {
        $data = array_merge($request->validated(), ['image_intro_id' => $imageIntroId]);
        $document = $action->handle($data, $request->user()->id);
        return (new DocumentResource($document))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateDocumentRequest $request, Document $document, UpdateDocumentAction $action): DocumentResource
    {
        $this->authorize('update', $document);
        $document = $action->handle($document, $request->validated(), $request->user()->id);
        return new DocumentResource($document);
    }

    public function destroy(Document $document, DeleteDocumentAction $action): JsonResponse
    {
        $this->authorize('delete', $document);
        $action->handle($document);
        return response()->json([], 204);
    }

    public function publish(PublishDocumentRequest $request, Document $document, PublishDocumentAction $action): DocumentResource
    {
        $this->authorize('publish', $document);
        $publishedAt = $request->validated()['published_at'] ?? null;
        $document = $action->publish($document, $publishedAt ? now()->parse($publishedAt) : null);
        return new DocumentResource($document);
    }

    public function unpublish(Request $request, Document $document, PublishDocumentAction $action): DocumentResource
    {
        $this->authorize('publish', $document);
        $document = $action->unpublish($document);
        return new DocumentResource($document);
    }
}


