<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Borrowing;

class BorrowingPolicy
{
    /**
     * Permite devolução apenas para admin e bibliotecário.
     */
    public function return(User $user, Borrowing $borrowing)
    {
        return in_array($user->role, ['admin', 'bibliotecario']);
    }
}
