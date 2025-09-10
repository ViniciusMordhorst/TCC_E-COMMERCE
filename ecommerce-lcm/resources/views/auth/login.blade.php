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
                    {{-- Mensagens de validação --}}
                    @if ($errors->any())
                        <div class="alert alert-danger text-center">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $erro)
                                    <li>{{ $erro }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Mensagens de sessão --}}
                    @if(session('success'))
                        <div class="alert alert-success text-center">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div id="flashError" class="alert alert-danger text-center">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(session('error1'))
                        <div id="flashError1" class="alert alert-danger text-center">
                            {{ session('error1') }}
                        </div>
                    @endif

                    <form action="{{ route('login.submit') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
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

<script>
    // Opcional: esconder flash de erro após 3s e recarregar para limpar flash
    setTimeout(() => {
        const fe = document.getElementById('flashError') || document.getElementById('flashError1');
        if (fe) {
            fe.style.display = 'none';
            // location.reload(); // descomente se quiser recarregar automaticamente
        }
    }, 3000);
</script>
</body>
</html>