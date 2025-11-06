<?php

declare(strict_types=1);

namespace App\Domain\Videos\Actions;

use App\Domain\Videos\Models\Video;

class ExtractVideoMetadataAction
{
    public function execute(Video $video): void
    {
        if ($video->source_type !== 'upload' || empty($video->file_path)) {
            return;
        }

        // Fallback no-op; integrate php-ffmpeg here if available
        // Example (pseudo):
        // $ffprobe = \FFMpeg\FFProbe::create();
        // $path = Storage::disk('public')->path($video->file_path);
        // $duration = (int) $ffprobe->format($path)->get('duration');
        // $streams = $ffprobe->streams($path)->videos()->first();
        // $video->duration_sec = $duration; $video->width = $streams->get('width'); ...
        // $video->save();
    }
}


