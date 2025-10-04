@extends('layouts.app')

@section('title', 'Editar Produto')

@section('content')
<div class="form-card">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Editar Produto</h5>
            <a href="{{ route('produtos.index') }}" class="btn btn-sm btn-secondary">Voltar</a>
        </div>

        <div class="card-body">
            @if($errors->any())
                <div id="flashErrors" class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @include('produtos._form', ['produto' => $produto])
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('inputImagem')?.addEventListener('change', function (e) {
        const file = e.target.files && e.target.files[0];
        const preview = document.getElementById('previewImagem');
        if (!file) { preview.src='#'; preview.classList.add('d-none'); return; }
        const reader = new FileReader();
        reader.onload = ev => { preview.src=ev.target.result; preview.classList.remove('d-none'); }
        reader.readAsDataURL(file);
    });

    setTimeout(() => { const e = document.getElementById('flashErrors'); if(e)e.style.display='none'; }, 3000);
</script>
@endpush
