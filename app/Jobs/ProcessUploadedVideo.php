<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Domain\Videos\Models\Video;
use App\Domain\Videos\Actions\ExtractVideoMetadataAction;
use App\Domain\Videos\Actions\GenerateVideoThumbnailAction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessUploadedVideo implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(private readonly int $videoId)
    {
    }

    public function handle(): void
    {
        $video = Video::find($this->videoId);
        if (!$video) {
            return;
        }

        try {
            (new ExtractVideoMetadataAction())->execute($video);
        } catch (\Throwable) {
            // ignore if ffmpeg or handlers are not available
        }

        try {
            (new GenerateVideoThumbnailAction())->execute($video);
        } catch (\Throwable) {
            // ignore if ffmpeg or handlers are not available
        }
    }
}


