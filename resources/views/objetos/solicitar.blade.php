<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitar objeto | Achados e Perdidos</title>
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
            min-height: 100vh;
            margin: 0;
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
            transition: background-color 150ms ease, transform 150ms ease;
        }

        a:hover,
        button:not(:disabled):hover {
            background: #e6f3fc;
            transform: scale(1.1);
        }

        a:focus-visible,
        input:focus-visible,
        button:focus-visible {
            outline: 3px solid #f5c542;
            outline-offset: 3px;
        }

        header {
            border-bottom: 3px solid #65b5e8;
            background: #0b3a68;
            color: white;
            padding: 1.5rem max(1.25rem, calc((100% - 900px) / 2));
        }

        header nav {
            display: flex;
            justify-content: flex-end;
        }

        header h1 {
            margin: 1.5rem 0 0.5rem;
            font-size: clamp(2rem, 5vw, 2.75rem);
        }

        header p {
            margin: 0;
            color: #e2f1fc;
            line-height: 1.5;
        }

        main {
            width: min(100% - 2.5rem, 760px);
            margin: 2rem auto;
        }

        .item-card {
            overflow: hidden;
            border: 1px solid #c8d8e8;
            border-radius: 0.5rem;
            background: white;
            box-shadow: 0 3px 12px rgb(11 58 104 / 8%);
        }

        .item-card img,
        .no-photo {
            display: grid;
            width: 100%;
            max-height: 400px;
            min-height: 180px;
            place-items: center;
            background: #e8f2fa;
            color: #456985;
            object-fit: contain;
        }

        .item-content {
            padding: clamp(1.25rem, 4vw, 2rem);
        }

        .category {
            color: #1769aa;
            font-size: 0.9rem;
            font-weight: 700;
        }

        .item-content h2 {
            margin: 0.5rem 0 1rem;
            color: #0b3a68;
        }

        .details {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem 1rem;
            color: #526a80;
        }

        .description {
            margin-top: 1.25rem;
            line-height: 1.6;
            overflow-wrap: anywhere;
        }

        .notice {
            margin-top: 1.5rem;
            border-left: 4px solid #1769aa;
            background: #e8f2fa;
            padding: 1rem;
            line-height: 1.5;
        }

        .request-form {
            margin-top: 2rem;
            border-top: 1px solid #c8d8e8;
            padding-top: 1.5rem;
        }

        .request-form h3 {
            margin: 0 0 1rem;
            color: #0b3a68;
        }

        .fields {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
        }

        .field {
            display: grid;
            gap: 0.45rem;
        }

        .field label {
            font-weight: 700;
        }

        .field input {
            width: 100%;
            border: 1px solid #9bb9d2;
            border-radius: 0.4rem;
            background: white;
            color: #18324b;
            font: inherit;
            padding: 0.75rem;
        }

        .error {
            color: #a12622;
            font-size: 0.9rem;
        }

        .submit-button {
            margin-top: 1.25rem;
            background: #0b3a68;
            color: white;
        }

        .submit-button:hover {
            border-color: #06477a;
            background: #06477a;
            color: white;
        }

        @media (max-width: 560px) {
            .fields {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header>
        <nav aria-label="Navegação principal">
            <a href="{{ route('objetos.index') }}">Voltar ao mural</a>
        </nav>
        <h1>Solicitar objeto</h1>
        <p>Confira os dados do item que você deseja requerer.</p>
    </header>

    <main>
        <article class="item-card">
            @if ($objeto->foto)
                <img src="{{ Illuminate\Support\Facades\Storage::disk('public')->url($objeto->foto) }}" alt="Foto de {{ $objeto->titulo }}">
            @else
                <div class="no-photo">Sem foto</div>
            @endif

            <div class="item-content">
                <span class="category">{{ $objeto->categoria->nome }}</span>
                <h2>{{ $objeto->titulo }}</h2>
                <div class="details">
                    <span>Local: {{ $objeto->local }}</span>
                    <span>Turno: {{ $objeto->turno }}</span>
                    <span>Status: {{ ucfirst($objeto->status) }}</span>
                </div>
                <p class="description">{{ $objeto->descricao ?: 'Sem descrição informada.' }}</p>
                @if (session('sucesso'))
                    <p class="notice" role="status">{{ session('sucesso') }}</p>
                @elseif (session('erro'))
                    <p class="notice" role="alert">{{ session('erro') }}</p>
                @else
                    <p class="notice">
                        Seus dados serão usados pela Secretaria para verificar e acompanhar esta solicitação. O CPF será armazenado de forma criptografada.
                    </p>
                @endif

                <form class="request-form" action="{{ route('objetos.solicitacoes.store', $objeto) }}" method="POST">
                    @csrf
                    <h3>Seus dados</h3>
                    <div class="fields">
                        <div class="field">
                            <label for="nome">Nome *</label>
                            <input id="nome" name="nome" type="text" autocomplete="name" maxlength="255" required>
                            @error('nome')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="field">
                            <label for="cpf">CPF *</label>
                            <input id="cpf" name="cpf" type="text" inputmode="numeric" autocomplete="off" placeholder="000.000.000-00" maxlength="14" required>
                            @error('cpf')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="field">
                            <label for="email">E-mail *</label>
                            <input id="email" name="email" type="email" autocomplete="email" maxlength="255" required>
                            @error('email')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="field">
                            <label for="telefone">Telefone *</label>
                            <input id="telefone" name="telefone" type="tel" autocomplete="tel" maxlength="30" required>
                            @error('telefone')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <button class="submit-button" type="submit">Enviar requerimento</button>
                </form>
            </div>
        </article>
    </main>
</body>
</html>
