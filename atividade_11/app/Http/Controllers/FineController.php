<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Borrowing;
use Carbon\Carbon;

class FineController extends Controller
{
    /**
     * Exibe a lista de usuários com débito
     */
    public function index()
    {
        if (!auth()->user()->isStaff()) {
            abort(403, 'Acesso negado. Apenas bibliotecários e administradores podem acessar o controle de multas.');
        }

        $usersWithDebit = User::where('debit', '>', 0)
            ->orderBy('debit', 'desc')
            ->get();

        return view('fines.index', compact('usersWithDebit'));
    }

    /**
     * Zera o débito de um usuário após pagamento
     */
    public function clearDebit(User $user)
    {
        if (!auth()->user()->isStaff()) {
            abort(403, 'Acesso negado. Apenas bibliotecários e administradores podem zerar débitos.');
        }

        $user->update(['debit' => 0.00]);

        return redirect()->route('fines.index')
            ->with('success', "Débito de {$user->name} foi zerado com sucesso.");
    }

    /**
     * Calcula multa por atraso na devolução
     */
    public static function calculateFine($borrowedAt, $returnedAt = null)
    {
        $returnDate = $returnedAt ? Carbon::parse($returnedAt) : Carbon::now();
        $borrowedDate = Carbon::parse($borrowedAt);
        $dueDate = $borrowedDate->copy()->addDays(15);
        
        if ($returnDate->gt($dueDate)) {
            $daysLate = $returnDate->diffInDays($dueDate);
            return $daysLate * 0.50; // R$ 0,50 por dia de atraso
        }
        
        return 0.00;
    }

    /**
     * Aplica multa ao usuário
     */
    public static function applyFine(User $user, $fineAmount)
    {
        $user->increment('debit', $fineAmount);
    }

    /**
     * Verifica se usuário pode fazer empréstimo
     */
    public static function canBorrow(User $user)
    {
        return $user->debit <= 0;
    }
}
