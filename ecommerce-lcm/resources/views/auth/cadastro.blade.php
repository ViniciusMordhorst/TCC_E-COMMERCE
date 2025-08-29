<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header text-center">
                    <h4>Cadastro de Usuário</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                
                    <form action="{{ route('cadastro') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" name="nome" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="senha" class="form-label">Senha</label>
                            <input type="password" name="senha" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="senha_confirmation" class="form-label">Confirmar Senha</label>
                            <input type="password" name="senha_confirmation" class="form-control" required>
                        </div>

              

                        <div class="mb-3">
                            <label for="cpf" class="form-label">CPF (opcional)</label>
                            <input type="text" name="cpf" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="telefone" class="form-label">Telefone (opcional)</label>
                            <input type="text" name="telefone" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Cadastrar</button>
                    </form>

                    <p class="mt-3 text-center">
                        Já tem conta? <a href="{{ route('login.form') }}">Faça login</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
