<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Book;
use App\Models\Borrowing;
use App\Http\Controllers\FineController;



class BorrowingController extends Controller
{
    public function store(Request $request, Book $book)
    {
        if (!auth()->user()->isStaff()) {
            abort(403, 'Acesso negado. Apenas bibliotecários e administradores podem registrar empréstimos.');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::find($request->user_id);

        // Verifica se o usuário possui débito pendente
        if (!FineController::canBorrow($user)) {
            return redirect()->route('books.show', $book)
                ->with('error', 'Usuário possui débito pendente de R$ ' . number_format($user->debit, 2, ',', '.') . '. É necessário pagar a multa antes de realizar novos empréstimos.');
        }

        // Verifica se já existe um empréstimo em aberto para este livro
        $emAberto = Borrowing::where('book_id', $book->id)
            ->whereNull('returned_at')
            ->exists();

        if ($emAberto) {
            return redirect()->route('books.show', $book)
                ->with('error', 'Este livro já está emprestado e ainda não foi devolvido.');
        }

        // Verifica se o usuário já possui 5 livros emprestados simultaneamente
        $emprestimosAbertos = Borrowing::where('user_id', $request->user_id)
            ->whereNull('returned_at')
            ->count();

        if ($emprestimosAbertos >= 5) {
            return redirect()->route('books.show', $book)
                ->with('error', 'O usuário já atingiu o limite de 5 livros emprestados simultaneamente.');
        }

        Borrowing::create([
            'user_id' => $request->user_id,
            'book_id' => $book->id,
            'borrowed_at' => now(),
        ]);

        return redirect()->route('books.show', $book)->with('success', 'Empréstimo registrado com sucesso.');
    }

    public function returnBook(Borrowing $borrowing)
    {
        if (!auth()->user()->isStaff()) {
            abort(403, 'Acesso negado. Apenas bibliotecários e administradores podem registrar devoluções.');
        }

        $borrowing->update([
            'returned_at' => now(),
        ]);

        // Calcula e aplica multa se houver atraso
        $fineAmount = FineController::calculateFine($borrowing->borrowed_at, now());
        
        if ($fineAmount > 0) {
            FineController::applyFine($borrowing->user, $fineAmount);
            $message = "Devolução registrada com sucesso. Multa aplicada: R$ " . number_format($fineAmount, 2, ',', '.');
        } else {
            $message = "Devolução registrada com sucesso.";
        }

        return redirect()->route('books.show', $borrowing->book_id)->with('success', $message);
    }

    public function userBorrowings(User $user)
    {
        $borrowings = $user->books()->withPivot('borrowed_at', 'returned_at')->get();

        return view('users.borrowings', compact('user', 'borrowings'));
    }
}
