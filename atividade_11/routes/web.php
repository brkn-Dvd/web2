<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FineController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



// Rotas autenticadas
Route::middleware(['auth'])->group(function () {



    // Rotas de empréstimo
    Route::post('/books/{book}/borrow', [BorrowingController::class, 'store'])
        ->name('books.borrow')
        ->middleware('can:borrow,book');

    Route::get('/users/{user}/borrowings', [BorrowingController::class, 'userBorrowings'])
        ->name('users.borrowings')
        ->middleware('can:viewBorrowings,user');

    Route::patch('/borrowings/{borrowing}/return', [BorrowingController::class, 'returnBook'])
        ->name('borrowings.return')
        ->middleware('can:return,borrowing');


    // Rotas para clientes
    Route::middleware(['role:cliente'])->group(function () {
        // Rotas específicas para clientes (se necessário)
    });


    // Rotas para bibliotecários
    // Route::middleware(['role:bibliotecario'])->group(function () {
    Route::get('/books/create-id-number', [BookController::class, 'createWithId'])->name('books.create.id');
    Route::post('/books/create-id-number', [BookController::class, 'storeWithId'])->name('books.store.id');

    Route::get('/books/create-select', [BookController::class, 'createWithSelect'])->name('books.create.select');
    Route::post('/books/create-select', [BookController::class, 'storeWithSelect'])->name('books.store.select');

    // Rotas para controle de multas (apenas staff)
    Route::middleware(['auth'])->group(function () {
        Route::get('/fines', [FineController::class, 'index'])->name('fines.index');
        Route::patch('/fines/{user}/clear-debit', [FineController::class, 'clearDebit'])->name('fines.clear-debit');
    });

    Route::resource('publishers', PublisherController::class)->except(['destroy']);
    Route::resource('authors', AuthorController::class)->except(['destroy']);
    Route::resource('categories', CategoryController::class)->except(['destroy']);
    // });


    Route::resource('users', UserController::class);
    // Rotas para administradores
    Route::prefix('admin')->middleware(['role:admin'])->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
        Route::put('/users/{user}/role', [UserController::class, 'updateRole'])->name('admin.users.updateRole');

        // Rotas completas para admin
        Route::resource('books', BookController::class)->except(['create', 'store']);
        Route::resource('publishers', PublisherController::class);
        Route::resource('authors', AuthorController::class);
        Route::resource('categories', CategoryController::class);
    });

    // Rota para capa do livro (acessível para autenticados)
    Route::get('/books/{book}/cover', [BookController::class, 'getCoverImage'])
        ->name('books.cover')
        ->middleware('can:view,book');
});

    // Rotas de recursos (com verificação individual nos controllers)
    Route::resource('users', UserController::class)->except(['create', 'store', 'destroy', 'index']);

    // Rotas públicas (acessíveis sem autenticação)
    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
