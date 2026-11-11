<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Objeto;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RuntimeException;

class ObjetoController extends Controller
{
    public function index(): View
    {
        $objetos = Objeto::with('categoria')
            ->latest()
            ->paginate(12);

        return view('objetos.index', compact('objetos'));
    }

    public function solicitar(Objeto $objeto): View
    {
        $objeto->load('categoria');

        return view('objetos.solicitar', compact('objeto'));
    }

    public function create()
    {
        $categorias = Categoria::orderBy('nome')->get();

        return view('objetos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'categoria_id' => ['required', 'exists:categorias,id'],
            'titulo' => ['required', 'string', 'max:255'],
            'local' => ['required', 'string', 'max:255'],
            'turno' => ['required', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        if ($request->hasFile('foto')) {
            $caminhoFoto = $request->file('foto')->store('objetos', 'public');

            if ($caminhoFoto === false) {
                throw new RuntimeException('Não foi possível salvar a foto do objeto.');
            }

            $dados['foto'] = $caminhoFoto;
        }

        Objeto::create($dados);

        return redirect()
            ->route('objetos.create')
            ->with('sucesso', 'Objeto cadastrado com sucesso!');
    }
}
