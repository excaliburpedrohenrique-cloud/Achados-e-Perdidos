<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar objeto | Achados e Perdidos</title>
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
            padding: 0.65rem 0.9rem;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 150ms ease, color 150ms ease, border-color 150ms ease, transform 150ms ease;
        }

        a:hover,
        button:hover {
            transform: scale(1.1);
        }

        a:focus-visible,
        button:focus-visible,
        input:focus-visible,
        select:focus-visible,
        textarea:focus-visible {
            outline: 3px solid #f5c542;
            outline-offset: 3px;
        }

        header {
            border-bottom: 5px solid #65b5e8;
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

        header nav {
            display: flex;
            flex: 0 0 auto;
        }

        header nav > a:first-child {
            border-color: rgb(255 255 255 / 75%);
            background: transparent;
            color: white;
        }

        header nav > a:first-child:hover {
            background: rgb(255 255 255 / 15%);
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
            width: min(100% - 2.5rem, 760px);
            margin: 2rem auto;
        }

        .form-card {
            border: 1px solid #c8d8e8;
            border-radius: 0.5rem;
            background: white;
            padding: clamp(1.25rem, 4vw, 2rem);
            box-shadow: 0 3px 12px rgb(11 58 104 / 8%);
        }

        .form-card h2 {
            margin: 0 0 0.5rem;
            color: #0b3a68;
        }

        .intro {
            margin: 0 0 1.5rem;
            color: #526a80;
            line-height: 1.5;
        }

        .success {
            margin-bottom: 1.25rem;
            border: 1px solid #a9d2b2;
            border-radius: 0.4rem;
            background: #edf8ef;
            color: #245c32;
            padding: 0.85rem 1rem;
        }

        .fields {
            display: grid;
            gap: 1.15rem;
        }

        .field {
            display: grid;
            gap: 0.45rem;
        }

        label {
            color: #18324b;
            font-weight: 700;
        }

        input,
        select,
        textarea {
            width: 100%;
            border: 1px solid #9bb9d2;
            border-radius: 0.4rem;
            background: white;
            color: #18324b;
            font: inherit;
            padding: 0.75rem;
        }

        input[type="file"] {
            padding: 0.55rem;
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        .hint {
            color: #526a80;
            font-size: 0.9rem;
            line-height: 1.45;
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
                <h1>Cadastrar objeto</h1>
                <p>Preencha as informações para publicar um item no mural.</p>
            </div>
            <nav aria-label="Navegação principal">
                <a href="{{ route('objetos.index') }}">Voltar ao mural</a>
            </nav>
        </div>
    </header>

    <main>
        <section class="form-card" aria-labelledby="form-heading">
            <h2 id="form-heading">Informações do objeto</h2>
            <p class="intro">Os campos marcados como obrigatórios precisam ser preenchidos.</p>

            @if (session('sucesso'))
                <p class="success" role="status">{{ session('sucesso') }}</p>
            @endif

            <form action="{{ route('objetos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="fields">
                    <div class="field">
                        <label for="titulo">Título *</label>
                        <input id="titulo" name="titulo" value="{{ old('titulo') }}" required>
                        @error('titulo')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="categoria_id">Categoria *</label>
                        <select id="categoria_id" name="categoria_id" required>
                            <option value="">Selecione uma categoria</option>
                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id }}" @selected(old('categoria_id') == $categoria->id)>
                                    {{ $categoria->nome }}
                                </option>
                            @endforeach
                        </select>
                        @error('categoria_id')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="local">Local *</label>
                        <input id="local" name="local" value="{{ old('local') }}" required>
                        @error('local')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="turno">Turno *</label>
                        <input id="turno" name="turno" value="{{ old('turno') }}" required>
                        @error('turno')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="descricao">Descrição</label>
                        <textarea id="descricao" name="descricao">{{ old('descricao') }}</textarea>
                        @error('descricao')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="foto">Foto do objeto (opcional)</label>
                        <input id="foto" name="foto" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                        <span class="hint">Formatos aceitos: JPG, PNG ou WEBP. Tamanho máximo: 5 MB.</span>
                        @error('foto')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="actions">
                    <button class="primary-button" type="submit">Cadastrar objeto</button>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
