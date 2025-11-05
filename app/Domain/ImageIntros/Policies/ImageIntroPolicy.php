<?php declare(strict_types=1);

namespace App\Domain\ImageIntros\Policies;

use App\Domain\ImageIntros\Models\ImageIntro;
use App\Models\User;

class ImageIntroPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('image-intros.read');
    }

    public function view(User $user, ImageIntro $intro): bool
    {
        return $user->can('image-intros.read') || $intro->status === 'published';
    }

    public function create(User $user): bool
    {
        return $user->can('image-intros.create');
    }

    public function update(User $user, ImageIntro $intro): bool
    {
        return $user->can('image-intros.update');
    }

    public function delete(User $user, ImageIntro $intro): bool
    {
        return $user->can('image-intros.delete');
    }

    public function restore(User $user, ImageIntro $intro): bool
    {
        return $user->can('image-intros.update');
    }

    public function forceDelete(User $user, ImageIntro $intro): bool
    {
        return $user->can('image-intros.delete');
    }
}


