<?php
declare(strict_types=1);

namespace App\Domain\Topics\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Topic extends Model implements HasMedia
{
    use HasFactory;
    use SoftDeletes;
    use InteractsWithMedia;

    protected $table = 'topics';

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images');
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
}


