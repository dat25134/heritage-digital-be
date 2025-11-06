<?php

declare(strict_types=1);

namespace App\Domain\Videos\Policies;

use App\Domain\Videos\Models\Video;
use App\Models\User;

class VideoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('videos.read');
    }

    public function view(User $user, Video $video): bool
    {
        return $user->can('videos.read');
    }

    public function create(User $user): bool
    {
        return $user->can('videos.create');
    }

    public function update(User $user, Video $video): bool
    {
        return $user->can('videos.update');
    }

    public function delete(User $user, Video $video): bool
    {
        return $user->can('videos.delete');
    }
}


