<?php

declare(strict_types=1);

namespace App\Domain\Books\Policies;

use App\Domain\Books\Models\Book;
use App\Models\User;

class BookPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('books.read');
    }

    public function view(User $user, Book $book): bool
    {
        return $user->can('books.read');
    }

    public function create(User $user): bool
    {
        return $user->can('books.create');
    }

    public function update(User $user, Book $book): bool
    {
        return $user->can('books.update');
    }

    public function delete(User $user, Book $book): bool
    {
        return $user->can('books.delete');
    }

    public function publish(User $user, Book $book): bool
    {
        return $user->can('books.publish');
    }
}



