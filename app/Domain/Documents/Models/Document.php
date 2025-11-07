<?php

declare(strict_types=1);

namespace App\Domain\Documents\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Document extends Model implements HasMedia
{
    use HasFactory;
    use SoftDeletes;
    use InteractsWithMedia;

    protected $table = 'documents';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'status',
        'published_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $model): void {
            if (empty($model->slug)) {
                $model->slug = static::generateUniqueSlug($model->title);
            }
        });

        static::updating(function (self $model): void {
            if ($model->isDirty('title') && !$model->isDirty('slug')) {
                // Keep slug stable by default; uncomment to auto-update slug on title change
                // $model->slug = static::generateUniqueSlug($model->title, $model->id);
            }
        });
    }

    public static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 1;
        while (static::query()
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->where('slug', $slug)
            ->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }

    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }

    // Scopes
    public function scopeSearch($query, ?string $q)
    {
        if ($q === null || $q === '') {
            return $query;
        }
        return $query->where(function ($sub) use ($q) {
            $sub->where('title', 'like', '%' . $q . '%')
                ->orWhere('description', 'like', '%' . $q . '%');
        });
    }

    public function scopeStatus($query, ?string $status)
    {
        if ($status) {
            $query->where('status', $status);
        }
        return $query;
    }

    public function scopePublishedBetween($query, ?string $from, ?string $to)
    {
        if ($from) {
            $query->where('published_at', '>=', $from);
        }
        if ($to) {
            $query->where('published_at', '<=', $to);
        }
        return $query;
    }

    public function scopeCreatedBetween($query, ?string $from, ?string $to)
    {
        if ($from) {
            $query->where('created_at', '>=', $from);
        }
        if ($to) {
            $query->where('created_at', '<=', $to);
        }
        return $query;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('pdf')->singleFile();
        $this->addMediaCollection('cover')->singleFile();
        $this->addMediaCollection('attachments');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(200)
            ->height(200)
            ->nonQueued();

        $this->addMediaConversion('sm')
            ->width(640)
            ->nonQueued();
    }
}


