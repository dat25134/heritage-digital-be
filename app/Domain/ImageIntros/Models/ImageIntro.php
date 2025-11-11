<?php declare(strict_types=1);

namespace App\Domain\ImageIntros\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ImageIntro extends Model implements HasMedia
{
    use HasFactory;
    use SoftDeletes;
    use InteractsWithMedia;

    protected $table = 'image_intros';

    protected $fillable = [
        'title',
        'category',
        'slug',
        'summary',
        'content_html',
        'status',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')->singleFile();
        $this->addMediaCollection('thumb')->singleFile();
        $this->addMediaCollection('avatar')->singleFile();
        $this->addMediaCollection('images');
        $this->addMediaCollection('videos');
        $this->addMediaCollection('audio');
        $this->addMediaCollection('documents');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit('crop', 200, 200)
            ->queued();

        $this->addMediaConversion('sm')->width(640)->queued();
        $this->addMediaConversion('md')->width(1280)->queued();
        $this->addMediaConversion('lg')->width(1920)->queued();
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeSearch($query, ?string $q)
    {
        if (!$q) {
            return $query;
        }
        return $query->where(function ($sub) use ($q) {
            $sub->where('title', 'like', "%{$q}%")
                ->orWhere('summary', 'like', "%{$q}%");
        });
    }

    public function scopeStatus($query, ?string $status)
    {
        if (!$status) {
            return $query;
        }
        return $query->where('status', $status);
    }

    // Relations
    public function videos()
    {
        return $this->hasMany(\App\Domain\Videos\Models\Video::class, 'image_intro_id');
    }

    public function documents()
    {
        return $this->hasMany(\App\Domain\Documents\Models\Document::class, 'image_intro_id');
    }

    public function books()
    {
        return $this->hasMany(\App\Domain\Books\Models\Book::class, 'image_intro_id');
    }

    public function papers()
    {
        return $this->hasMany(\App\Domain\ResearchPapers\Models\ResearchPaper::class, 'image_intro_id');
    }
}

