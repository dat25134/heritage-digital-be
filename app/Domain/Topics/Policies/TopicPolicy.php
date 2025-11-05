<?php declare(strict_types=1);

namespace App\Domain\Topics\Policies;

use App\Domain\Topics\Models\Topic;
use App\Models\User;

class TopicPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('topics.read');
    }

    public function view(User $user, Topic $topic): bool
    {
        return $user->can('topics.read');
    }

    public function create(User $user): bool
    {
        return $user->can('topics.create');
    }

    public function update(User $user, Topic $topic): bool
    {
        return $user->can('topics.update');
    }

    public function delete(User $user, Topic $topic): bool
    {
        return $user->can('topics.delete');
    }

    public function restore(User $user, Topic $topic): bool
    {
        return $user->can('topics.update');
    }

    public function forceDelete(User $user, Topic $topic): bool
    {
        return $user->can('topics.delete');
    }
}


