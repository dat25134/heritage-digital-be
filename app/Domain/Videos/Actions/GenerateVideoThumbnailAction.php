<?php

declare(strict_types=1);

namespace App\Domain\Videos\Actions;

use App\Domain\Videos\Models\Video;
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use FFMpeg\Coordinate\TimeCode;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class GenerateVideoThumbnailAction
{
    public function execute(Video $video): void
    {
        if ($video->source_type !== 'upload' || empty($video->file_path)) {
            return;
        }

        // Skip if thumbnail already exists
        if ($video->getFirstMedia('thumbnail')) {
            return;
        }

        try {
            $ffmpegPath = config('media-library.ffmpeg_path', '/usr/bin/ffmpeg');
            $ffprobePath = config('media-library.ffprobe_path', '/usr/bin/ffprobe');

            // Check if FFmpeg binaries exist
            if (!file_exists($ffmpegPath) || !file_exists($ffprobePath)) {
                Log::warning('FFmpeg binaries not found', [
                    'ffmpeg_path' => $ffmpegPath,
                    'ffprobe_path' => $ffprobePath,
                    'video_id' => $video->id,
                ]);
                return;
            }

            // Get full path to video file
            $videoPath = Storage::disk('public')->path($video->file_path);

            if (!file_exists($videoPath)) {
                Log::warning('Video file not found', [
                    'video_path' => $videoPath,
                    'video_id' => $video->id,
                ]);
                return;
            }

            // Initialize FFMpeg and FFProbe
            $ffmpeg = FFMpeg::create([
                'ffmpeg.binaries' => $ffmpegPath,
                'ffprobe.binaries' => $ffprobePath,
                'timeout' => 3600,
                'ffmpeg.threads' => 12,
            ]);

            $ffprobe = FFProbe::create([
                'ffprobe.binaries' => $ffprobePath,
            ]);

            // Get video duration from format
            $duration = (float) $ffprobe->format($videoPath)->get('duration');
            
            // Validate duration and set frame time (default to 1 second or middle of video)
            if ($duration <= 0) {
                $frameTime = 1.0;
            } else {
                $frameTime = $duration > 1 ? 1.0 : ($duration / 2);
            }

            // Open video file
            $videoFile = $ffmpeg->open($videoPath);

            // Create temporary file for thumbnail
            $tempThumbnail = tempnam(sys_get_temp_dir(), 'video_thumb_') . '.jpg';

            // Extract frame at specified time
            $videoFile
                ->frame(TimeCode::fromSeconds($frameTime))
                ->save($tempThumbnail);

            // Add thumbnail to media collection
            if (file_exists($tempThumbnail)) {
                $video->addMedia($tempThumbnail)
                    ->usingName('Thumbnail for ' . $video->title)
                    ->toMediaCollection('thumbnail');

                // Clean up temporary file
                @unlink($tempThumbnail);

                Log::info('Video thumbnail generated successfully', [
                    'video_id' => $video->id,
                    'frame_time' => $frameTime,
                ]);
            }
        } catch (Throwable $e) {
            Log::error('Failed to generate video thumbnail', [
                'video_id' => $video->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Clean up temporary file if it exists
            if (isset($tempThumbnail) && file_exists($tempThumbnail)) {
                @unlink($tempThumbnail);
            }
        }
    }
}


