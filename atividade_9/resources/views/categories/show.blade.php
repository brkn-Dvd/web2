@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Detalhes da Categoria</h1>

    <div class="card">
        <div class="card-header">
            Categoria: {{ $category->name }}
        </div>
        <div class="card-body">
            <p><strong>ID:</strong> {{ $category->id }}</p>
            <p><strong>Nome:</strong> {{ $category->name }}</p>
        </div>
    </div>

    <a href="{{ route('categories.index') }}" class="btn btn-secondary mt-3">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
</div>
@endsection

<!--
    Este arquivo é parte do projeto de gerenciamento de categorias.
    O código acima é responsável por exibir os detalhes de uma categoria específica.
    Ele inclui informações como ID e nome da categoria, além de um botão para voltar à lista de categorias.
    O código utiliza o Blade, o mecanismo de templates do Laravel, para renderizar a página.