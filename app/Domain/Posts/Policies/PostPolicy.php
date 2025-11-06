<?php
declare(strict_types=1);

namespace App\Domain\Posts\Policies;

use App\Domain\Posts\Models\Post;
use App\Models\User;

class PostPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('posts.read');
    }

    public function view(User $user, Post $post): bool
    {
        return $user->can('posts.read');
    }

    public function create(User $user): bool
    {
        return $user->can('posts.create');
    }

    public function update(User $user, Post $post): bool
    {
        return $user->can('posts.update');
    }

    public function delete(User $user, Post $post): bool
    {
        return $user->can('posts.delete');
    }

    public function restore(User $user, Post $post): bool
    {
        return $user->can('posts.update');
    }

    public function forceDelete(User $user, Post $post): bool
    {
        return $user->can('posts.delete');
    }

    public function publish(User $user, Post $post): bool
    {
        return $user->can('posts.publish');
    }
}


