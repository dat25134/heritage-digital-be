<?php declare(strict_types=1);

namespace App\Domain\Topics\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Topic extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'topics';

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    protected static function booted(): void
    {
        static::creating(function (Topic $topic) {
            if (!$topic->slug) {
                $topic->slug = static::uniqueSlug($topic->name);
            }
        });

        static::updating(function (Topic $topic) {
            if ($topic->isDirty('name') && !$topic->isDirty('slug')) {
                // keep slug if not explicitly changed
            }
        });
    }

    public static function uniqueSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;
        while (static::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }
}
