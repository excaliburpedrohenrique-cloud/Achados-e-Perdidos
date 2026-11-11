<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitações | Achados e Perdidos</title>
    <style>
        :root {
            color-scheme: light;
            font-family: Arial, sans-serif;
            color: #18324b;
            background: #f2f7fc;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
        }

        header {
            border-bottom: 3px solid #65b5e8;
            background: #0b3a68;
            color: white;
            padding: 1.5rem max(1.25rem, calc((100% - 1100px) / 2));
        }

        .header-main,
        .header-actions,
        .actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
        }

        .header-actions {
            justify-content: flex-end;
            flex-wrap: wrap;
        }

        a,
        button {
            display: inline-block;
            border: 1px solid #1769aa;
            border-radius: 0.4rem;
            background: white;
            color: #07508a;
            font: inherit;
            font-weight: 700;
            padding: 0.65rem 0.9rem;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 150ms ease, color 150ms ease, transform 150ms ease;
        }

        a:hover,
        button:hover {
            background: #e6f3fc;
            color: #06365f;
            transform: scale(1.05);
        }

        header button {
            border-color: rgb(255 255 255 / 75%);
            background: transparent;
            color: white;
        }

        header button:hover {
            background: rgb(255 255 255 / 15%);
            color: white;
        }

        a:focus-visible,
        button:focus-visible {
            outline: 3px solid #f5c542;
            outline-offset: 3px;
        }

        header h1 {
            margin: 0 0 0.5rem;
            font-size: clamp(2rem, 5vw, 2.75rem);
        }

        header p {
            margin: 0;
            color: #e2f1fc;
            line-height: 1.5;
        }

        main {
            max-width: 1100px;
            margin: 2rem auto;
            padding: 0 1.25rem;
        }

        .section-heading {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .section-heading h2 {
            margin: 0;
        }

        .count,
        .muted {
            color: #526a80;
        }

        .alert {
            margin-bottom: 1rem;
            border-radius: 0.4rem;
            padding: 0.85rem 1rem;
        }

        .alert-success {
            border: 1px solid #a9d2b2;
            background: #edf8ef;
            color: #245c32;
        }

        .alert-error {
            border: 1px solid #e0b5b3;
            background: #fff0ef;
            color: #8f211d;
        }

        .request-list {
            display: grid;
            gap: 1rem;
        }

        .request-card,
        .empty {
            border: 1px solid #c8d8e8;
            border-radius: 0.5rem;
            background: white;
            box-shadow: 0 3px 12px rgb(11 58 104 / 8%);
        }

        .request-card {
            padding: clamp(1rem, 3vw, 1.5rem);
        }

        .request-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            border-bottom: 1px solid #dce7f0;
            padding-bottom: 1rem;
        }

        .request-header h3 {
            margin: 0 0 0.35rem;
            color: #0b3a68;
        }

        .request-header p,
        .contact-list p {
            margin: 0.35rem 0 0;
            line-height: 1.5;
            overflow-wrap: anywhere;
        }

        .status {
            flex: 0 0 auto;
            border-radius: 999px;
            background: #fff4d6;
            color: #775600;
            padding: 0.35rem 0.7rem;
            font-size: 0.85rem;
            font-weight: 700;
        }

        .status-approved {
            background: #e5f3ff;
            color: #12558a;
        }

        .status-rejected {
            background: #edf1f5;
            color: #536477;
        }

        .contact-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(min(100%, 220px), 1fr));
            gap: 0.5rem 1rem;
            padding: 1rem 0;
        }

        .contact-list strong {
            color: #0b3a68;
        }

        .actions {
            justify-content: flex-start;
            flex-wrap: wrap;
            border-top: 1px solid #dce7f0;
            padding-top: 1rem;
        }

        .actions form {
            margin: 0;
        }

        .approve-button {
            border-color: #1769aa;
            background: #0b3a68;
            color: white;
        }

        .approve-button:hover {
            border-color: #06477a;
            background: #06477a;
            color: white;
        }

        .reject-button {
            border-color: #9c3d38;
            color: #8f211d;
        }

        .empty {
            padding: 2.5rem 1rem;
            text-align: center;
        }

        .pagination {
            margin-top: 1.5rem;
        }

        @media (max-width: 600px) {
            .header-main,
            .request-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .header-actions {
                justify-content: flex-start;
            }

            .section-heading {
                align-items: flex-start;
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-main">
            <div>
                <h1>Solicitações</h1>
                <p>Revise os pedidos e gerencie a autorização de retirada dos objetos.</p>
            </div>
            <nav class="header-actions" aria-label="Navegação da Secretaria">
                <a href="{{ route('objetos.index') }}">Voltar ao mural</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit">Sair</button>
                </form>
            </nav>
        </div>
    </header>

    <main>
        @if (session('sucesso'))
            <p class="alert alert-success" role="status">{{ session('sucesso') }}</p>
        @endif
        @if (session('erro'))
            <p class="alert alert-error" role="alert">{{ session('erro') }}</p>
        @endif

        <section aria-labelledby="requests-heading">
            <div class="section-heading">
                <h2 id="requests-heading">Pedidos recebidos</h2>
                <span class="count">{{ $solicitacoes->total() }} {{ $solicitacoes->total() === 1 ? 'solicitação' : 'solicitações' }}</span>
            </div>

            @if ($solicitacoes->isEmpty())
                <div class="empty">
                    <h3>Ainda não há solicitações</h3>
                    <p class="muted">Os pedidos enviados pelo mural aparecerão aqui.</p>
                </div>
            @else
                <div class="request-list">
                    @foreach ($solicitacoes as $solicitacao)
                        <article class="request-card">
                            <div class="request-header">
                                <div>
                                    <h3>{{ $solicitacao->objeto->titulo }}</h3>
                                    <p>Solicitação recebida em {{ $solicitacao->created_at->format('d/m/Y H:i') }}</p>
                                    <p>Local: {{ $solicitacao->objeto->local }} · Turno: {{ $solicitacao->objeto->turno }}</p>
                                    @if ($solicitacao->objeto->status === 'devolvido')
                                        <p>Objeto retirado e removido do mural.</p>
                                    @endif
                                </div>
                                <span class="status {{ $solicitacao->status === 'aprovada' ? 'status-approved' : ($solicitacao->status === 'recusada' ? 'status-rejected' : '') }}">
                                    {{ ucfirst($solicitacao->status) }}
                                </span>
                            </div>

                            <div class="contact-list">
                                <p><strong>Nome:</strong> {{ $solicitacao->nome ?: 'Não informado' }}</p>
                                <p><strong>CPF:</strong> {{ $solicitacao->cpf ?: 'Não informado' }}</p>
                                <p><strong>E-mail:</strong> {{ $solicitacao->email ?: 'Não informado' }}</p>
                                <p><strong>Telefone:</strong> {{ $solicitacao->telefone ?: 'Não informado' }}</p>
                            </div>

                            @if ($solicitacao->status === 'pendente')
                                <div class="actions">
                                    @if ($solicitacao->objeto->status !== 'disponível')
                                        <span class="muted">Este objeto já foi retirado.</span>
                                    @else
                                        <form action="{{ route('solicitacoes.aprovar', $solicitacao) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button class="approve-button" type="submit">Aprovar retirada</button>
                                        </form>
                                    @endif
                                    <form action="{{ route('solicitacoes.recusar', $solicitacao) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button class="reject-button" type="submit">Recusar</button>
                                    </form>
                                </div>
                            @endif
                        </article>
                    @endforeach
                </div>

                <div class="pagination">
                    {{ $solicitacoes->links() }}
                </div>
            @endif
        </section>
    </main>
</body>
</html>
