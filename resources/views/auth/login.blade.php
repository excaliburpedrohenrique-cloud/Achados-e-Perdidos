<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar | Achados e Perdidos</title>
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
            background: #fff;
            color: #07508a;
            font: inherit;
            font-weight: 700;
            padding: 0.7rem 1rem;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 150ms ease, color 150ms ease, border-color 150ms ease, transform 150ms ease;
        }

        a:hover,
        button:hover {
            border-color: #06477a;
            background: #e6f3fc;
            color: #06365f;
            transform: scale(1.05);
        }

        a:focus-visible,
        button:focus-visible,
        input:focus-visible {
            outline: 3px solid #f5c542;
            outline-offset: 3px;
        }

        header {
            border-bottom: 3px solid #65b5e8;
            background: #0b3a68;
            color: white;
            padding: 1.5rem max(1.25rem, calc((100% - 1100px) / 2));
        }

        .header-main {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
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
            width: min(100% - 2.5rem, 540px);
            margin: 2rem auto;
        }

        .login-card {
            border: 1px solid #c8d8e8;
            border-radius: 0.5rem;
            background: white;
            padding: clamp(1.25rem, 4vw, 2rem);
            box-shadow: 0 3px 12px rgb(11 58 104 / 8%);
        }

        .login-card h2 {
            margin: 0 0 0.5rem;
            color: #0b3a68;
        }

        .intro {
            margin: 0 0 1.5rem;
            color: #526a80;
            line-height: 1.5;
        }

        .fields {
            display: grid;
            gap: 1rem;
        }

        .field {
            display: grid;
            gap: 0.45rem;
        }

        .field label {
            font-weight: 700;
        }

        .field input[type="email"],
        .field input[type="password"] {
            width: 100%;
            border: 1px solid #9bb9d2;
            border-radius: 0.4rem;
            background: white;
            color: #18324b;
            font: inherit;
            padding: 0.75rem;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 1rem;
            color: #526a80;
        }

        .remember input {
            width: 1rem;
            height: 1rem;
            accent-color: #0b3a68;
        }

        .error {
            margin: 0;
            color: #a12622;
            font-size: 0.9rem;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }

        .primary-button {
            border-color: #0b3a68;
            background: #0b3a68;
            color: white;
        }

        .primary-button:hover {
            border-color: #06477a;
            background: #06477a;
            color: white;
        }

        @media (max-width: 560px) {
            .header-main {
                align-items: flex-start;
                flex-direction: column;
                gap: 1rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-main">
            <div>
                <h1>Acesso da Secretaria</h1>
                <p>Entre para cadastrar objetos e gerenciar solicitações.</p>
            </div>
            <a href="{{ route('objetos.index') }}">Voltar ao mural</a>
        </div>
    </header>

    <main>
        <section class="login-card" aria-labelledby="login-heading">
            <h2 id="login-heading">Entrar</h2>
            <p class="intro">Use o e-mail e a senha da conta autorizada.</p>

            <form action="{{ route('login.store') }}" method="POST">
                @csrf

                <div class="fields">
                    <div class="field">
                        <label for="email">E-mail</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="username" required autofocus>
                        @error('email')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="password">Senha</label>
                        <input id="password" name="password" type="password" autocomplete="current-password" required>
                        @error('password')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <label class="remember">
                    <input type="checkbox" name="remember" value="1">
                    Manter conectado
                </label>

                <div class="actions">
                    <button class="primary-button" type="submit">Entrar</button>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
