<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', User::class); // Somente admin pode listar usuários
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }

    public function show(User $user)
    {
        $this->authorize('view', $user); // Cliente só pode ver próprio perfil
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user); // Bibliotecário pode editar apenas alguns campos
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
        ];

        // Admin pode alterar roles, outros não
        if (auth()->user()->role === 'admin') {
            $rules['role'] = ['required', Rule::in(['admin', 'bibliotecario', 'cliente'])];
        }

        $validated = $request->validate($rules);

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso.');
    }

    // Novo método específico para atualização de role (se preferir separado)
    public function updateRole(Request $request, User $user)
    {
        $this->authorize('updateRole', $user); // Somente admin

        $validated = $request->validate([
            'role' => ['required', Rule::in(['admin', 'bibliotecario', 'cliente'])]
        ]);

        $user->update(['role' => $validated['role']]);

        return back()->with('success', 'Perfil atualizado com sucesso.');
    }
}