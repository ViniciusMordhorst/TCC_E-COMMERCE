<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Produto</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .image-preview { max-width: 240px; max-height: 240px; object-fit: contain; border: 1px solid #e9e9e9; padding: 6px; border-radius: 6px; }
        .form-card { max-width: 900px; margin: 24px auto; }
    </style>
</head>
<body class="bg-light">
<div class="container form-card">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Cadastrar Produto</h5>
            <a href="{{ route('produtos.index') }}" class="btn btn-sm btn-secondary">Voltar</a>
        </div>

        <div class="card-body">
            {{-- Mensagens --}}
            @if(session('success_produto'))
                <div id="flashSuccess" class="alert alert-success">{{ session('success_produto') }}</div>
            @endif

            @if($errors->any())
                <div id="flashErrors" class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('produtos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nome <span class="text-danger">*</span></label>
                        <input type="text" name="nome" class="form-control" value="{{ old('nome') }}" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Preço (R$) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="preco" class="form-control" value="{{ old('preco') }}" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Estoque <span class="text-danger">*</span></label>
                        <input type="number" name="estoque" class="form-control" value="{{ old('estoque', 0) }}" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Descrição</label>
                        <textarea name="descricao" class="form-control" rows="4">{{ old('descricao') }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Categoria</label>
                        <select name="id_categoria" class="form-control">
                            <option value="">-- Selecione --</option>
                            @foreach($categorias ?? [] as $cat)
                                <option value="{{ $cat->id }}" {{ old('id_categoria') == $cat->id ? 'selected' : '' }}>{{ $cat->nome }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted d-block mt-1">Ou informe abaixo para criar uma nova categoria</small>
                        <input type="text" name="categoria_nova" class="form-control mt-2" placeholder="Nova categoria (opcional)" value="{{ old('categoria_nova') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Ref</label>
                        <input type="text" name="ref" class="form-control" value="{{ old('ref') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Código</label>
                        <input type="text" name="cod" class="form-control" value="{{ old('cod') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Imagem</label>
                        <input type="file" name="imagem" accept="image/*" class="form-control" id="inputImagem">
                    </div>

                    <div class="col-md-6 d-flex align-items-center">
                        <div>
                            <small class="text-muted">Pré-visualização:</small>
                            <div class="mt-2">
                                <img id="previewImagem" src="#" alt="Preview" class="image-preview d-none">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Salvar Produto</button>
                    <a href="{{ route('produtos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // preview da imagem selecionada
    document.getElementById('inputImagem')?.addEventListener('change', function (e) {
        const file = e.target.files && e.target.files[0];
        const preview = document.getElementById('previewImagem');
        if (!file) {
            preview.src = '#';
            preview.classList.add('d-none');
            return;
        }
        const reader = new FileReader();
        reader.onload = function (ev) {
            preview.src = ev.target.result;
            preview.classList.remove('d-none');
        }
        reader.readAsDataURL(file);
    });

    // esconde flashes após 3s
    setTimeout(() => {
        const s = document.getElementById('flashSuccess');
        if (s) s.style.display = 'none';
        const e = document.getElementById('flashErrors');
        if (e) e.style.display = 'none';
    }, 3000);
</script>
</body>
</html>
