<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Book;
use App\Models\Borrowing;
use App\Policies\UserPolicy;
use App\Policies\BookPolicy;
use App\Policies\BorrowingPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => UserPolicy::class,
        Book::class => BookPolicy::class,
        Borrowing::class => BorrowingPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}