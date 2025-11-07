<?php

declare(strict_types=1);

namespace App\Domain\Videos\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Video extends Model implements HasMedia
{
    use HasFactory;
    use SoftDeletes;
    use InteractsWithMedia;

    protected $table = 'media_videos';

    protected $fillable = [
        'title',
        'description',
        'source_type',
        'file_path',
        'external_url',
        'duration_sec',
        'width',
        'height',
        'size_bytes',
        'mime',
        'status',
        'published_at',
        'sort_order',
        'image_intro_id',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'duration_sec' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
        'size_bytes' => 'integer',
        'sort_order' => 'integer',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('thumbnail')->singleFile();
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

    public function imageIntro()
    {
        return $this->belongsTo(\App\Domain\ImageIntros\Models\ImageIntro::class, 'image_intro_id');
    }
}


