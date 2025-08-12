@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Controle de Multas</h4>
                    <a href="{{ route('home') }}" class="btn btn-secondary">Voltar</a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="alert alert-info">
                        <h6><i class="bi bi-info-circle"></i> Como funciona o sistema de multas:</h6>
                        <ul class="mb-0">
                            <li>Livros devem ser devolvidos em até <strong>15 dias</strong> após o empréstimo</li>
                            <li>Multa de <strong>R$ 0,50</strong> por dia de atraso</li>
                            <li>Usuários com débito não podem fazer novos empréstimos</li>
                            <li>Após o pagamento, clique em "Confirmar Pagamento" para zerar o débito</li>
                        </ul>
                    </div>

                    @if($usersWithDebit->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nome do Usuário</th>
                                        <th>Email</th>
                                        <th>Débito Total</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($usersWithDebit as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                <span class="badge bg-danger">
                                                    R$ {{ number_format($user->debit, 2, ',', '.') }}
                                                </span>
                                            </td>
                                            <td>
                                                <form action="{{ route('fines.clear-debit', $user) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-success btn-sm" 
                                                            onclick="return confirm('Confirmar pagamento da multa de {{ $user->name }}?')">
                                                        <i class="fas fa-check"></i> Confirmar Pagamento
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle text-success" style="font-size: 3rem;"></i>
                            <h5 class="mt-3">Nenhum usuário com débito pendente</h5>
                            <p class="text-muted">Todos os usuários estão em dia com suas obrigações.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
