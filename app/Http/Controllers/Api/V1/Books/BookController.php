<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Books;

use App\Domain\Books\Models\Book;
use App\Domain\Books\Resources\BookCollection;
use App\Domain\Books\Resources\BookResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Books\BookIndexRequest;
use App\Http\Requests\Api\V1\Books\StoreBookRequest;
use App\Http\Requests\Api\V1\Books\UpdateBookRequest;
use App\Http\Requests\Api\V1\Books\UploadBookMediaRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class BookController extends Controller
{
    public function index(BookIndexRequest $request): BookCollection
    {
        $validated = $request->validated();
        $sort = $validated['sort'] ?? 'created_at';
        $order = $validated['order'] ?? 'desc';

        $query = Book::query()
            ->search($validated['q'] ?? null)
            ->filterByAuthor($validated['author'] ?? null)
            ->filterByPublisher($validated['publisher'] ?? null)
            ->filterByYear(isset($validated['year']) ? (int)$validated['year'] : null)
            ->filterByIsbn($validated['isbn'] ?? null)
            ->filterByStatus($validated['status'] ?? null)
            ->orderBy($sort, $order)
            ->orderBy('id', 'desc');

        $perPage = (int)($validated['per_page'] ?? 15);
        $books = $query->paginate($perPage)->through(fn ($b) => new BookResource($b));

        return new BookCollection($books);
    }

    public function show(Book $book): BookResource
    {
        $this->authorize('view', $book);
        return new BookResource($book);
    }

    public function store(StoreBookRequest $request): JsonResponse
    {
        $data = $request->validated();
        if (empty($data['slug'])) {
            $data['slug'] = str($data['title'])->slug()->toString();
        }
        $data['created_by'] = $request->user()->id;
        $book = Book::create($data);
        return (new BookResource($book))->response()->setStatusCode(201);
    }

    public function update(UpdateBookRequest $request, Book $book): BookResource
    {
        $this->authorize('update', $book);
        $data = $request->validated();
        if (array_key_exists('slug', $data) && empty($data['slug']) && !empty($data['title'])) {
            $data['slug'] = str($data['title'])->slug()->toString();
        }
        $data['updated_by'] = $request->user()->id;
        $book->update($data);
        return new BookResource($book->refresh());
    }

    public function destroy(Book $book): JsonResponse
    {
        $this->authorize('delete', $book);
        $book->delete();
        return response()->json([], 204);
    }

    public function publish(Request $request, Book $book): BookResource
    {
        $this->authorize('publish', $book);
        $status = $request->input('status');
        if ($status === 'published') {
            $book->status = 'published';
            $book->published_at = now();
        } elseif ($status === 'draft' || $status === 'archived') {
            $book->status = $status;
            if ($status !== 'published') {
                $book->published_at = null;
            }
        }
        $book->save();
        return new BookResource($book);
    }

    public function uploadMedia(UploadBookMediaRequest $request, Book $book, string $collection): BookResource
    {
        $this->authorize('update', $book);

        $file = $request->file('file');
        if ($collection === 'cover' || $collection === 'ebook') {
            $book->clearMediaCollection($collection);
        }
        $book->addMedia($file)->toMediaCollection($collection);

        return new BookResource($book->refresh());
    }

    public function listMedia(Book $book): JsonResponse
    {
        $this->authorize('view', $book);
        return response()->json([
            'data' => [
                'cover' => optional($book->getFirstMedia('cover'))?->toArray(),
                'ebook' => optional($book->getFirstMedia('ebook'))?->toArray(),
                'attachments' => $book->getMedia('attachments')->map->toArray(),
            ],
        ]);
    }

    public function deleteMedia(int $mediaId): JsonResponse
    {
        $media = Media::query()->findOrFail($mediaId);
        /** @var \Spatie\MediaLibrary\HasMedia $model */
        $model = $media->model;
        if ($model instanceof Book) {
            $this->authorize('update', $model);
        }
        $media->delete();
        return response()->json([], 204);
    }
}



