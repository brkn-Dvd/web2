@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Adicionar Categoria</h1>

    <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nome</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
            @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">
            <i class="bi bi-save"></i> Salvar
        </button>
        <a href="{{ route('categories.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </form>
</div>
@endsection
<!--
    Este arquivo é parte do projeto de gerenciamento de categorias.
    O código acima é responsável por criar uma nova categoria.
    Ele inclui um formulário com um campo para o nome da categoria e botões para salvar ou voltar à lista de categorias.
    O código utiliza o Blade, o mecanismo de templates do Laravel, para renderizar a página.