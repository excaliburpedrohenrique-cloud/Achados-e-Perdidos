<?php

namespace App\Http\Controllers;

use App\Models\Objeto;
use App\Models\Solicitacao;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class SolicitacaoController extends Controller
{
    public function index(): View
    {
        $solicitacoes = Solicitacao::with('objeto')
            ->latest()
            ->paginate(15);

        return view('solicitacoes.index', compact('solicitacoes'));
    }

    public function approve(Solicitacao $solicitacao): RedirectResponse
    {
        $result = DB::transaction(function () use ($solicitacao): string {
            $lockedRequest = Solicitacao::query()
                ->lockForUpdate()
                ->findOrFail($solicitacao->id);
            $objeto = Objeto::query()
                ->lockForUpdate()
                ->findOrFail($lockedRequest->objeto_id);

            if ($lockedRequest->status !== 'pendente') {
                return 'not-pending';
            }

            if ($objeto->status !== 'disponível') {
                return 'object-unavailable';
            }

            $lockedRequest->update(['status' => 'aprovada']);
            $objeto->update(['status' => 'devolvido']);
            $objeto->solicitacoes()
                ->where('status', 'pendente')
                ->whereKeyNot($lockedRequest->id)
                ->update(['status' => 'recusada']);

            return 'approved';
        });

        if ($result === 'not-pending') {
            return back()->with('erro', 'Essa solicitação não está mais pendente.');
        }

        if ($result === 'object-unavailable') {
            return back()->with('erro', 'Esse objeto já foi retirado e não está mais disponível para aprovação.');
        }

        return back()->with('sucesso', 'Retirada aprovada. O objeto foi removido do mural e a solicitação aprovada foi mantida nesta página.');
    }

    public function reject(Solicitacao $solicitacao): RedirectResponse
    {
        $result = DB::transaction(function () use ($solicitacao): string {
            $lockedRequest = Solicitacao::query()
                ->lockForUpdate()
                ->findOrFail($solicitacao->id);

            if ($lockedRequest->status !== 'pendente') {
                return 'not-pending';
            }

            $lockedRequest->update(['status' => 'recusada']);

            return 'rejected';
        });

        if ($result === 'not-pending') {
            return back()->with('erro', 'Somente solicitações pendentes podem ser recusadas.');
        }

        return back()->with('sucesso', 'Solicitação recusada.');
    }

    public function store(Request $request, Objeto $objeto): RedirectResponse
    {
        abort_if($request->user()?->tipo === 'Secretaria', 403);
        abort_unless($objeto->status === 'disponível', 404);

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

        DB::transaction(function () use ($objeto, $dados, $cpf): void {
            $lockedObject = Objeto::query()
                ->lockForUpdate()
                ->findOrFail($objeto->id);

            abort_unless($lockedObject->status === 'disponível', 404);

            Solicitacao::create([
                'objeto_id' => $lockedObject->id,
                'nome' => $dados['nome'],
                'cpf' => $cpf,
                'email' => $dados['email'],
                'telefone' => $dados['telefone'],
            ]);
        });

        return redirect()
            ->route('objetos.solicitar', $objeto)
            ->with('sucesso', 'Solicitação enviada. A Secretaria poderá entrar em contato pelos dados informados.');
    }
}
