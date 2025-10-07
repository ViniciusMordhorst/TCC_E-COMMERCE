<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;
use App\Models\Categoria;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ProdutoController extends Controller
{
    protected $authController;

    public function __construct()
    {
        $this->authController = new AuthController();
    }

    // ========================
    // Home - lista produtos para usuários comuns
    // ========================
    public function home()
    {
        $this->authController->checkAuth();
        $produtos = Produto::all();
        return view('home', compact('produtos'));
    }

    // ========================
    // CRUD produtos - apenas admin
    // ========================

    public function index()
    {
        $this->authController->checkAdmin();
        $produtos = Produto::with('categoria')->orderBy('nome')->get();
        return view('produtos.index', compact('produtos'));
    }

    public function create()
    {
        $this->authController->checkAdmin();
        $categorias = Categoria::orderBy('nome')->get();
        return view('produtos.cadastroproduto', compact('categorias'));
    }

    // Para exibir todos os produtos no catálogo
    public function catalogo()
    {
        $produtos = Produto::with('categoria')->orderBy('nome')->get();
            return view('produtos.catalogo', compact('produtos'));
    }


    public function store(Request $request)
    {
        $this->authController->checkAdmin();

        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'preco' => 'required|numeric',
            'estoque' => 'required|integer',
            'ref' => 'nullable|string|max:100',
            'cod' => 'nullable|string|max:100',
            'imagem' => 'nullable|image|min:2',
            'id_categoria' => 'nullable|integer|exists:categorias,id',
            'categoria_nova' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $id_categoria = $request->id_categoria;

            if ($request->filled('categoria_nova')) {
                $categoria = Categoria::firstOrCreate(['nome' => trim($request->categoria_nova)]);
                $id_categoria = $categoria->id;
            }

            $imagemPath = null;
            if ($request->hasFile('imagem')) {
                $imagemPath = $this->uploadToSupabase($request->file('imagem'));
            }

            Produto::create([
                'nome' => $request->nome,
                'descricao' => $request->descricao,
                'preco' => $request->preco,
                'estoque' => $request->estoque,
                'ref' => $request->ref,
                'cod' => $request->cod,
                'id_categoria' => $id_categoria,
                'imagem' => $imagemPath,
            ]);

            DB::commit();
            return redirect()->route('produtos.index')->with('success_produto', 'Produto cadastrado com sucesso!');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Erro ao cadastrar produto', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->withInput()->with('error_produto', 'Erro ao cadastrar produto. Veja os logs.');
        }
    }

    public function edit($id)
    {
        $this->authController->checkAdmin();
        $produto = Produto::findOrFail($id);
        $categorias = Categoria::orderBy('nome')->get();
        return view('produtos.edit', compact('produto', 'categorias'));
    }

    public function update(Request $request, $id)
    {
        $this->authController->checkAdmin();

        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'preco' => 'required|numeric',
            'estoque' => 'required|integer',
            'ref' => 'nullable|string|max:100',
            'cod' => 'nullable|string|max:100',
            'imagem' => 'nullable|image|min:2',
            'id_categoria' => 'nullable|integer|exists:categorias,id',
            'categoria_nova' => 'nullable|string|max:255',
        ]);

        $produto = Produto::findOrFail($id);

        DB::beginTransaction();
        try {
            $id_categoria = $request->id_categoria;

            if ($request->filled('categoria_nova')) {
                $categoria = Categoria::firstOrCreate(['nome' => trim($request->categoria_nova)]);
                $id_categoria = $categoria->id;
            }

            $imagemPath = $produto->imagem;
            if ($request->hasFile('imagem')) {
                $imagemPath = $this->uploadToSupabase($request->file('imagem'));
            }

            $produto->update([
                'nome' => $request->nome,
                'descricao' => $request->descricao,
                'preco' => $request->preco,
                'estoque' => $request->estoque,
                'ref' => $request->ref,
                'cod' => $request->cod,
                'id_categoria' => $id_categoria,
                'imagem' => $imagemPath,
            ]);

            DB::commit();
            return redirect()->route('produtos.index')->with('success_produto', 'Produto atualizado com sucesso!');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Erro ao atualizar produto', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->withInput()->with('error_produto', 'Erro ao atualizar produto. Veja os logs.');
        }
    }

    public function destroy($id)
    {
        $this->authController->checkAdmin();
        $produto = Produto::findOrFail($id);

        if ($produto->imagem) {
            $this->deleteFromSupabase($produto->imagem);
        }

        $produto->delete();
        return redirect()->route('produtos.index')->with('success_produto', 'Produto removido com sucesso!');
    }

    // ========================
    // Upload e delete Supabase
    // ========================
    private function uploadToSupabase($file)
    {
        $this->authController->checkAdmin();
        $supabaseUrl = rtrim(env('SUPABASE_URL'), '/');
        $supabaseKey = env('SUPABASE_KEY');
        $supabaseBucket = env('SUPABASE_BUCKET', 'produtos');

        $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
        $content = file_get_contents($file->getRealPath());

        $response = Http::withHeaders([
            'apikey' => $supabaseKey,
            'Authorization' => 'Bearer ' . $supabaseKey,
        ])->withBody($content, 'application/octet-stream')
          ->put("{$supabaseUrl}/storage/v1/object/{$supabaseBucket}/{$filename}");

        if (!$response->successful()) {
            Log::error('Falha ao enviar imagem para Supabase', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            throw new \RuntimeException('Falha ao enviar imagem.');
        }

        return "{$supabaseUrl}/storage/v1/object/public/{$supabaseBucket}/{$filename}";
    }

    private function deleteFromSupabase($url)
    {
        $this->authController->checkAdmin();
        $supabaseUrl = rtrim(env('SUPABASE_URL'), '/');
        $supabaseKey = env('SUPABASE_KEY');
        $supabaseBucket = env('SUPABASE_BUCKET', 'produtos');

        $path = ltrim(str_replace("/storage/v1/object/public/{$supabaseBucket}/", '', parse_url($url, PHP_URL_PATH)), '/');

        Http::withHeaders([
            'apikey' => $supabaseKey,
            'Authorization' => 'Bearer ' . $supabaseKey,
        ])->delete("{$supabaseUrl}/storage/v1/object/{$supabaseBucket}/{$path}");
    }
}
