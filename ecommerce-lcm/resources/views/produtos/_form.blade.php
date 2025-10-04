@php
    $isEdit = isset($produto);
@endphp

<form action="{{ $isEdit ? route('produtos.update', $produto->id) : route('produtos.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Nome <span class="text-danger">*</span></label>
            <input type="text" name="nome" class="form-control" value="{{ old('nome', $produto->nome ?? '') }}" required>
        </div>

        <div class="col-md-3">
            <label class="form-label">Preço (R$) <span class="text-danger">*</span></label>
            <input type="number" step="0.01" name="preco" class="form-control" value="{{ old('preco', $produto->preco ?? '') }}" required>
        </div>

        <div class="col-md-3">
            <label class="form-label">Estoque <span class="text-danger">*</span></label>
            <input type="number" name="estoque" class="form-control" value="{{ old('estoque', $produto->estoque ?? 0) }}" required>
        </div>

        <div class="col-12">
            <label class="form-label">Descrição</label>
            <textarea name="descricao" class="form-control" rows="4">{{ old('descricao', $produto->descricao ?? '') }}</textarea>
        </div>

        <div class="col-md-6">
            <label class="form-label">Categoria</label>
            <select name="id_categoria" class="form-control">
                <option value="">-- Selecione --</option>
                @foreach($categorias ?? [] as $cat)
                    <option value="{{ $cat->id }}" {{ old('id_categoria', $produto->id_categoria ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->nome }}</option>
                @endforeach
            </select>
            <small class="text-muted d-block mt-1">Ou informe abaixo para criar uma nova categoria</small>
            <input type="text" name="categoria_nova" class="form-control mt-2" placeholder="Nova categoria (opcional)" value="{{ old('categoria_nova') }}">
        </div>

        <div class="col-md-3">
            <label class="form-label">Ref</label>
            <input type="text" name="ref" class="form-control" value="{{ old('ref', $produto->ref ?? '') }}">
        </div>

        <div class="col-md-3">
            <label class="form-label">Código</label>
            <input type="text" name="cod" class="form-control" value="{{ old('cod', $produto->cod ?? '') }}">
        </div>

        <div class="col-md-6">
            <label class="form-label">Imagem</label>
            <input type="file" name="imagem" accept="image/*" class="form-control" id="inputImagem">
        </div>

        <div class="col-md-6 d-flex align-items-center">
            <div>
                <small class="text-muted">Pré-visualização:</small>
                <div class="mt-2">
                    <img id="previewImagem" src="{{ isset($produto->imagem) ? asset('storage/' . $produto->imagem) : '#' }}" alt="Preview" class="image-preview {{ isset($produto->imagem) ? '' : 'd-none' }}">
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Atualizar Produto' : 'Salvar Produto' }}</button>
        <a href="{{ route('produtos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
    </div>
</form>
