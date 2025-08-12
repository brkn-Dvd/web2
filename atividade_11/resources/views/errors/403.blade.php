@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0 text-danger">
                        <i class="bi bi-exclamation-triangle"></i> Acesso Negado
                    </h4>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="bi bi-shield-x text-danger" style="font-size: 4rem;"></i>
                    </div>
                    
                    <h5 class="text-danger mb-3">Você não tem permissão para acessar esta funcionalidade</h5>
                    
                    <p class="text-muted mb-4">
                        {{ $exception->getMessage() ?: 'Esta área é restrita a bibliotecários e administradores.' }}
                    </p>
                    
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('books.index') }}" class="btn btn-primary">
                            <i class="bi bi-book"></i> Ver Livros
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-secondary">
                            <i class="bi bi-house"></i> Página Inicial
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
