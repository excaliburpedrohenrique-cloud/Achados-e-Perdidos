<?php

namespace App\Http\Controllers;

use App\Models\Objeto;
use App\Models\Solicitacao;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SolicitacaoController extends Controller
{
    public function store(Request $request, Objeto $objeto): RedirectResponse
    {
        abort_unless($objeto->status === 'disponível', 404);
        abort_if($request->user()?->tipo === 'Secretaria', 403);

        $validator = Validator::make($request->all(), [
            'nome' => ['required', 'string', 'max:255'],
            'cpf' => ['required', 'string', 'max:14'],
            'email' => ['required', 'email', 'max:255'],
            'telefone' => ['required', 'string', 'max:30'],
        ]);

        $validator->after(function ($validator) use ($request): void {
            $cpfInformado = $request->input('cpf');

            if (is_string($cpfInformado) && strlen(preg_replace('/\D/', '', $cpfInformado)) !== 11) {
                $validator->errors()->add('cpf', 'Informe um CPF com 11 dígitos.');
            }
        });

        if ($validator->fails()) {
            return redirect()
                ->route('objetos.solicitar', $objeto)
                ->withErrors($validator);
        }

        $dados = $validator->validated();
        $cpf = preg_replace('/\D/', '', $dados['cpf']);

        Solicitacao::create([
            'objeto_id' => $objeto->id,
            'nome' => $dados['nome'],
            'cpf' => $cpf,
            'email' => $dados['email'],
            'telefone' => $dados['telefone'],
        ]);

        return redirect()
            ->route('objetos.solicitar', $objeto)
            ->with('sucesso', 'Solicitação enviada. A Secretaria poderá entrar em contato pelos dados informados.');
    }
}
