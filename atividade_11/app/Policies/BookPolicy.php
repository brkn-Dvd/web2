<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Book;

class BookPolicy
{
    /**
     * Determine if the user can borrow the book.
     */
    public function borrow(User $user, Book $book)
    {
        // Permite para admin, bibliotecario e cliente
        return in_array($user->role, ['admin', 'bibliotecario', 'cliente']);
    }
}
