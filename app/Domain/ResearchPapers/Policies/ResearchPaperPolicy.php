<?php

declare(strict_types=1);

namespace App\Domain\ResearchPapers\Policies;

use App\Domain\ResearchPapers\Models\ResearchPaper;
use App\Models\User;

class ResearchPaperPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('papers.read');
    }

    public function view(User $user, ResearchPaper $paper): bool
    {
        return $user->can('papers.read');
    }

    public function create(User $user): bool
    {
        return $user->can('papers.create');
    }

    public function update(User $user, ResearchPaper $paper): bool
    {
        return $user->can('papers.update');
    }

    public function delete(User $user, ResearchPaper $paper): bool
    {
        return $user->can('papers.delete');
    }

    public function restore(User $user, ResearchPaper $paper): bool
    {
        return $user->can('papers.update');
    }

    public function forceDelete(User $user, ResearchPaper $paper): bool
    {
        return $user->can('papers.delete');
    }

    public function publish(User $user, ResearchPaper $paper): bool
    {
        return $user->can('papers.publish');
    }
}


