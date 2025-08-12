@extends('layouts.app')

@section('content')
    <div class="container">
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <h1 class="my-4">Detalhes do Livro</h1>

        <div class="card mb-4">
            <div class="card-header">
                <strong>Título:</strong> {{ $book->title }}
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="book-info">
                            <p><strong>Autor:</strong>
                                <a href="{{ route('authors.show', $book->author->id) }}">
                                    {{ $book->author->name }}
                                </a>
                            </p>
                            <p><strong>Editora:</strong>
                                <a href="{{ route('publishers.show', $book->publisher->id) }}">
                                    {{ $book->publisher->name }}
                                </a>
                            </p>
                            <p><strong>Categoria:</strong>
                                <a href="{{ route('categories.show', $book->category->id) }}">
                                    {{ $book->category->name }}
                                </a>
                            </p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="book-cover-container text-center">
                            @if($book->cover_image)
                                <img src="{{ $book->cover_image_url }}" alt="Capa do livro" class="img-fluid rounded shadow"
                                    style="max-height: 300px;">
                            @else
                                <img src="{{ asset('images/default-book-cover.jpg') }}" alt="Sem capa"
                                    class="img-fluid rounded shadow" style="max-height: 300px;">
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <a href="{{ route('books.index') }}" class="btn btn-secondary mb-4">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>


        <!-- Formulário para Empréstimos -->
        @if(Auth::user()->isStaff())
            <div class="card mb-4">
                <div class="card-header">Registrar Empréstimo</div>
                <div class="card-body">
                    <form action="{{ route('books.borrow', $book) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="user_id" class="form-label">Usuário</label>
                            <select class="form-select" id="user_id" name="user_id" required>
                                <option value="" selected>Selecione um usuário</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success">Registrar Empréstimo</button>
                    </form>
                </div>
            </div>
        @endif

        <!-- Histórico de Empréstimos -->
        <div class="card">
            <div class="card-header">Histórico de Empréstimos</div>
            <div class="card-body">
                @if($book->users->isEmpty())
                    <p>Nenhum empréstimo registrado.</p>
                @else
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Usuário</th>
                                <th>Data de Empréstimo</th>
                                <th>Data de Devolução</th>
                                @if(Auth::user()->isStaff())
                                    <th>Débito</th>
                                @endif
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($book->users as $user)
                                <tr>
                                    <td>
                                        <a href="{{ route('users.show', $user->id) }}">
                                            {{ $user->name }}
                                        </a>
                                    </td>
                                    <td>{{ $user->pivot->borrowed_at }}</td>
                                    <td>{{ $user->pivot->returned_at ?? 'Em Aberto' }}</td>
                                    @if(Auth::user()->isStaff())
                                        <td>
                                            @if($user->debit > 0)
                                                <span class="badge bg-danger">
                                                    R$ {{ number_format($user->debit, 2, ',', '.') }}
                                                </span>
                                            @else
                                                <span class="badge bg-success">Em dia</span>
                                            @endif
                                        </td>
                                    @endif
                                    <td>
                                        @if(is_null($user->pivot->returned_at) && Auth::user()->isStaff())
                                            <form action="{{ route('borrowings.return', $user->pivot->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button class="btn btn-warning btn-sm">Devolver</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

@endsection