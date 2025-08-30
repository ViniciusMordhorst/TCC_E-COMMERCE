<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header text-center">
                    <h4>Login</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                <div class="alert alert-danger" style="text-align: center;">
                    <ul>
                        @foreach ($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                        @endforeach
                    </ul>
                    </div>
                        @endif

                        @if(session('success'))
                    <div class="alert alert-success" style="text-align: center;">
                    {{ session('success') }}
                    </div>
                        @endif

                    <form action="{{ route('login.form') }}" method="POST">
                        @csrf
                
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="senha" class="form-label">Senha</label>
                            <input type="password" name="senha" class="form-control" required>
                        </div>
            

                        <button type="submit" class="btn btn-primary w-100">Acessar</button>
                    </form>

                    <p class="mt-3 text-center">
                        Não possui cadastro? <a href="{{ route('cadastro.form') }}">Faça cadastro</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
