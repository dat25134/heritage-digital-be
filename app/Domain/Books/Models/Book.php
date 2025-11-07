<?php

declare(strict_types=1);

namespace App\Domain\Books\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Book extends Model implements HasMedia
{
    use HasFactory;
    use SoftDeletes;
    use InteractsWithMedia;

    protected $table = 'books';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'author',
        'publisher',
        'published_year',
        'isbn',
        'page_count',
        'status',
        'published_at',
        'created_by',
        'updated_by',
        'image_intro_id',
    ];

    protected $casts = [
        'published_year' => 'integer',
        'page_count' => 'integer',
        'published_at' => 'datetime',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')->singleFile();
        $this->addMediaCollection('ebook')->singleFile();
        $this->addMediaCollection('attachments');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 200, 200)->queued();
        $this->addMediaConversion('sm')->width(640)->queued();
        $this->addMediaConversion('md')->width(1280)->queued();
        $this->addMediaConversion('lg')->width(1920)->queued();
    }

    public function scopeSearch(Builder $query, ?string $q): Builder
    {
        if (!$q) {
            return $query;
        }
        $like = '%' . str_replace('%', '\\%', $q) . '%';
        return $query->where(function (Builder $sub) use ($like): void {
            $sub->where('title', 'like', $like)
                ->orWhere('description', 'like', $like)
                ->orWhere('author', 'like', $like)
                ->orWhere('publisher', 'like', $like)
                ->orWhere('isbn', 'like', $like);
        });
    }

    public function scopeFilterByAuthor(Builder $query, ?string $author): Builder
    {
        if (!$author) {
            return $query;
        }
        $like = '%' . str_replace('%', '\\%', $author) . '%';
        return $query->where('author', 'like', $like);
    }

    public function scopeFilterByPublisher(Builder $query, ?string $publisher): Builder
    {
        if (!$publisher) {
            return $query;
        }
        $like = '%' . str_replace('%', '\\%', $publisher) . '%';
        return $query->where('publisher', 'like', $like);
    }

    public function scopeFilterByYear(Builder $query, ?int $year): Builder
    {
        return $year ? $query->where('published_year', $year) : $query;
    }

    public function scopeFilterByIsbn(Builder $query, ?string $isbn): Builder
    {
        return $isbn ? $query->whereRaw('lower(isbn) = lower(?)', [$isbn]) : $query;
    }

    public function scopeFilterByStatus(Builder $query, ?string $status): Builder
    {
        return $status ? $query->where('status', $status) : $query;
    }

    public function imageIntro()
    {
        return $this->belongsTo(\App\Domain\ImageIntros\Models\ImageIntro::class, 'image_intro_id');
    }
}



