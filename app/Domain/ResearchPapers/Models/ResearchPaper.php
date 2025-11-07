<?php

declare(strict_types=1);

namespace App\Domain\ResearchPapers\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ResearchPaper extends Model implements HasMedia
{
    use HasFactory;
    use SoftDeletes;
    use InteractsWithMedia;

    protected $table = 'research_papers';

    protected $fillable = [
        'title',
        'slug',
        'abstract',
        'content_html',
        'authors_json',
        'year',
        'journal',
        'doi',
        'status',
        'published_at',
        'image_intro_id',
    ];

    protected $casts = [
        'authors_json' => 'array',
        'year' => 'integer',
        'published_at' => 'datetime',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('pdf')->singleFile();
        $this->addMediaCollection('cover')->singleFile();
        $this->addMediaCollection('attachments');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 200, 200)->queued();
        $this->addMediaConversion('sm')->width(640)->queued();
        $this->addMediaConversion('md')->width(1280)->queued();
        $this->addMediaConversion('lg')->width(1920)->queued();
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopeSearch(Builder $query, ?string $q): Builder
    {
        if (!$q) {
            return $query;
        }
        $like = '%' . str_replace('%', '\\%', $q) . '%';
        return $query->where(function (Builder $sub) use ($like): void {
            $sub->where('title', 'like', $like)
                ->orWhere('abstract', 'like', $like)
                ->orWhere('content_html', 'like', $like)
                ->orWhere('journal', 'like', $like)
                ->orWhere('doi', 'like', $like);
        });
    }

    public function scopeFilterByYear(Builder $query, ?int $year): Builder
    {
        return $year ? $query->where('year', $year) : $query;
    }

    public function scopeFilterByAuthor(Builder $query, ?string $author): Builder
    {
        if (!$author) {
            return $query;
        }
        $like = '%' . str_replace('%', '\\%', $author) . '%';
        // Simple LIKE on JSON text; for MySQL JSON functions, replace with JSON_SEARCH if needed
        return $query->where('authors_json', 'like', $like);
    }

    public function scopeFilterByDoi(Builder $query, ?string $doi): Builder
    {
        return $doi ? $query->whereRaw('lower(doi) = lower(?)', [$doi]) : $query;
    }

    public function imageIntro()
    {
        return $this->belongsTo(\App\Domain\ImageIntros\Models\ImageIntro::class, 'image_intro_id');
    }
}


