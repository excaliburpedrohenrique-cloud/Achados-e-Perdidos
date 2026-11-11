<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar | Achados e Perdidos</title>
</head>
<body>
    <main>
        <h1>Acesso da Secretaria</h1>
        <p>Entre com a conta autorizada para cadastrar objetos.</p>

        <form action="{{ route('login.store') }}" method="POST">
            @csrf

            <div>
                <label for="email">E-mail</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="username" required autofocus>
                @error('email')
                    <p>{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password">Senha</label>
                <input id="password" name="password" type="password" autocomplete="current-password" required>
                @error('password')
                    <p>{{ $message }}</p>
                @enderror
            </div>

            <label>
                <input type="checkbox" name="remember" value="1">
                Manter conectado
            </label>

            <button type="submit">Entrar</button>
        </form>

        <p><a href="{{ route('objetos.index') }}">Voltar ao mural</a></p>
    </main>
</body>
</html>
