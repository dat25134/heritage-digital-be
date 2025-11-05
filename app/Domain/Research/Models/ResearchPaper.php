<?php
declare(strict_types=1);

namespace App\Domain\Research\Models;

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

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('pdf')->singleFile();
        $this->addMediaCollection('cover')->singleFile();
        $this->addMediaCollection('attachments');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        // Chỉ áp dụng trên ảnh; PDF sẽ không có conversions
        $this->addMediaConversion('thumb')
            ->fit('crop', 200, 200)
            ->queued();

        $this->addMediaConversion('sm')->width(640)->queued();
        $this->addMediaConversion('md')->width(1280)->queued();
        $this->addMediaConversion('lg')->width(1920)->queued();
    }
}


