<?php

declare(strict_types=1);

namespace App\Domain\Documents\Policies;

use App\Domain\Documents\Models\Document;
use App\Models\User;

class DocumentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('documents.read');
    }

    public function view(User $user, Document $document): bool
    {
        return $user->can('documents.read');
    }

    public function create(User $user): bool
    {
        return $user->can('documents.create');
    }

    public function update(User $user, Document $document): bool
    {
        return $user->can('documents.update');
    }

    public function delete(User $user, Document $document): bool
    {
        return $user->can('documents.delete');
    }

    public function restore(User $user, Document $document): bool
    {
        return $user->can('documents.restore');
    }

    public function forceDelete(User $user, Document $document): bool
    {
        return $user->can('documents.forceDelete');
    }

    public function publish(User $user, Document $document): bool
    {
        return $user->can('documents.publish');
    }
}


