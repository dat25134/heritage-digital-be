<?php

declare(strict_types=1);

namespace App\Domain\Videos\Actions;

use App\Domain\Videos\Models\Video;

class GenerateVideoThumbnailAction
{
    public function execute(Video $video): void
    {
        if ($video->source_type !== 'upload' || empty($video->file_path)) {
            return;
        }

        // Fallback no-op; integrate php-ffmpeg here to extract a frame and store as thumbnail
        // Example (pseudo): extract frame to temp file then $video->addMedia($temp)->toMediaCollection('thumbnail');
    }
}


