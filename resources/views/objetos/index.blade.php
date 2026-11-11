<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mural de objetos | Achados e Perdidos</title>
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
            background: #0b3a68;
            color: white;
            padding: 1.5rem max(1.25rem, calc((100% - 1100px) / 2));
            border-bottom: 3px solid #65b5e8;
        }

        .header-main {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
        }

        header nav {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            flex: 0 0 auto;
            gap: 1rem;
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
            border-color: #06477a;
            background: #e6f3fc;
            color: #06365f;
            transform: scale(1.1);
        }

        a:focus-visible,
        button:focus-visible {
            outline: 3px solid #f5c542;
            outline-offset: 3px;
        }

        header h1 {
            margin: 0 0 0.5rem;
            font-size: clamp(2rem, 5vw, 3rem);
        }

        header p {
            margin: 0;
            color: #e2f1fc;
        }

        .button {
            border-color: #fff;
            background: #fff;
            color: #0b3a68;
        }

        .button:hover {
            border-color: #d8ecfb;
            background: #d8ecfb;
            color: #082c4e;
        }

        header button {
            border-color: rgb(255 255 255 / 75%);
            background: transparent;
            color: #fff;
        }

        header button:hover {
            border-color: #fff;
            background: rgb(255 255 255 / 15%);
            color: #fff;
        }

        header nav > div {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.5rem;
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

        .count, .muted {
            color: #526a80;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(min(100%, 300px), 1fr));
            gap: 1rem;
        }

        .card {
            overflow: hidden;
            border: 1px solid #c8d8e8;
            border-radius: 0.5rem;
            background: white;
            box-shadow: 0 3px 12px rgb(11 58 104 / 8%);
        }

        .card-content {
            padding: 1rem;
        }

        .card-content h3 {
            margin: 0.35rem 0 0.75rem;
            font-size: 1.2rem;
        }

        .category {
            color: #1769aa;
            font-size: 0.85rem;
            font-weight: 700;
        }

        .card-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
        }

        .request-button {
            flex: 0 0 auto;
            padding: 0.45rem 0.65rem;
            font-size: 0.85rem;
        }

        .details {
            display: flex;
            flex-wrap: wrap;
            gap: 0.4rem 0.8rem;
            color: #526a80;
            font-size: 0.9rem;
        }

        .item-info {
            margin-top: 1rem;
            overflow: hidden;
            border: 1px solid #c8d8e8;
            border-radius: 0.4rem;
        }

        .image-box {
            display: grid;
            min-height: 180px;
            place-items: center;
            padding: 0.75rem;
            background: #e8f2fa;
        }

        .image-preview {
            position: relative;
            display: inline-grid;
            max-width: 100%;
        }

        .image-preview-button {
            position: absolute;
            right: 0.5rem;
            bottom: 0.5rem;
            display: grid;
            width: 2.5rem;
            height: 2.5rem;
            place-items: center;
            border: 1px solid white;
            border-radius: 50%;
            background: #0b3a68;
            color: white;
            padding: 0;
            box-shadow: 0 2px 8px rgb(0 0 0 / 25%);
            cursor: pointer;
        }

        .image-preview-button:hover {
            border-color: white;
            background: #0b3a68;
            color: white;
            transform: scale(1.1);
        }

        .image-preview-button:focus-visible {
            outline: 3px solid #1769aa;
            outline-offset: 4px;
        }

        .card-image {
            display: block;
            width: auto;
            max-width: 100%;
            height: auto;
            max-height: 220px;
            object-fit: contain;
        }

        .image-preview-button svg {
            width: 1.25rem;
            height: 1.25rem;
        }

        .image-dialog {
            width: min(92vw, 1000px);
            max-width: none;
            max-height: 92vh;
            border: 1px solid #c8d8e8;
            border-radius: 0.6rem;
            background: #fff;
            padding: 1rem;
            box-shadow: 0 12px 40px rgb(0 0 0 / 35%);
        }

        .image-dialog::backdrop {
            background: rgb(7 24 40 / 78%);
        }

        .image-dialog-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 0.75rem;
        }

        .image-dialog-title {
            margin: 0;
            color: #0b3a68;
            font-size: 1.1rem;
        }

        .image-dialog-close {
            flex: 0 0 auto;
        }

        .image-dialog-image {
            display: block;
            width: 100%;
            max-height: calc(92vh - 6rem);
            object-fit: contain;
        }

        .placeholder {
            color: #456985;
            font-size: 0.95rem;
            font-weight: 700;
            text-align: center;
        }

        .description-box {
            min-width: 0;
            padding: 0.85rem;
            background: white;
            border-top: 1px solid #c8d8e8;
        }

        .description-box h4 {
            margin: 0 0 0.5rem;
            color: #0b3a68;
            font-size: 0.95rem;
        }

        .description {
            margin: 0;
            line-height: 1.5;
            overflow-wrap: anywhere;
        }

        .status {
            display: inline-block;
            margin-top: 1rem;
            border-radius: 999px;
            background: #e5f3ff;
            color: #12558a;
            padding: 0.3rem 0.65rem;
            font-size: 0.8rem;
            font-weight: 700;
        }

        .status-returned {
            background: #edf1f5;
            color: #536477;
        }

        .empty {
            border: 1px dashed #9bb9d2;
            border-radius: 0.5rem;
            background: white;
            padding: 2.5rem 1rem;
            text-align: center;
        }

        .empty h3 {
            margin-top: 0;
        }

        .pagination {
            margin-top: 1.5rem;
        }

        .pagination nav > div:first-child {
            display: none;
        }

        .pagination nav > div:last-child > div {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.4rem;
        }

        .pagination span[aria-current="page"] span {
            display: inline-block;
            border: 1px solid #0b3a68;
            border-radius: 0.4rem;
            background: #0b3a68;
            color: white;
            padding: 0.65rem 0.9rem;
        }

        .pagination span[aria-disabled="true"] span,
        .pagination span[aria-disabled="true"] {
            display: inline-block;
            border: 1px solid #c8d8e8;
            border-radius: 0.4rem;
            background: #edf3f8;
            color: #75899c;
            padding: 0.65rem 0.9rem;
        }

        @media (max-width: 560px) {
            .header-main {
                align-items: flex-start;
                flex-direction: column;
                gap: 1rem;
            }

            header nav {
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
                <h1>Mural de objetos</h1>
                <p>Encontre ou divulgue objetos perdidos na escola.</p>
            </div>
            <nav aria-label="Navegação principal">
                <div>
                    @auth
                        <form action="{{ route('logout') }}" method="POST" style="display: inline">
                            @csrf
                            <button type="submit">Sair</button>
                        </form>
                    @else
                        <a class="button" href="{{ route('login') }}">Acesso da Secretaria</a>
                    @endauth
                    @if (auth()->user()?->tipo === 'Secretaria')
                        <a class="button" href="{{ route('objetos.create') }}">Cadastrar objeto</a>
                    @endif
                </div>
            </nav>
        </div>
    </header>

    <main>
        <section aria-labelledby="items-heading">
            <div class="section-heading">
                <h2 id="items-heading">Objetos publicados</h2>
                <span class="count">{{ $objetos->total() }} {{ $objetos->total() === 1 ? 'objeto' : 'objetos' }}</span>
            </div>

            @if ($objetos->isEmpty())
                <div class="empty">
                    <h3>Ainda não há objetos no mural</h3>
                    <p class="muted">Quando um objeto for cadastrado, ele aparecerá aqui.</p>
                    @if (auth()->user()?->tipo === 'Secretaria')
                        <a class="button" href="{{ route('objetos.create') }}">Cadastrar o primeiro objeto</a>
                    @else
                        <p><a href="{{ route('login') }}">Acesso da Secretaria</a></p>
                    @endif
                </div>
            @else
                <div class="grid">
                    @foreach ($objetos as $objeto)
                        <article class="card">
                            <div class="card-content">
                                <div class="card-heading">
                                    <span class="category">{{ $objeto->categoria->nome }}</span>
                                    @if (auth()->user()?->tipo !== 'Secretaria' && $objeto->status === 'disponível')
                                        <a class="request-button" href="{{ route('objetos.solicitar', $objeto) }}">Solicitar</a>
                                    @endif
                                </div>
                                <h3>{{ $objeto->titulo }}</h3>
                                <div class="details">
                                    <span>{{ $objeto->local }}</span>
                                    <span>Turno: {{ $objeto->turno }}</span>
                                </div>

                                <div class="item-info">
                                    <div class="image-box">
                                        @if ($objeto->foto)
                                            <span class="image-preview">
                                                <img class="card-image" src="{{ Illuminate\Support\Facades\Storage::disk('public')->url($objeto->foto) }}" alt="">
                                                <button
                                                    class="image-preview-button"
                                                    type="button"
                                                    data-image="{{ Illuminate\Support\Facades\Storage::disk('public')->url($objeto->foto) }}"
                                                    data-alt="Foto ampliada de {{ $objeto->titulo }}"
                                                    aria-label="Ampliar foto de {{ $objeto->titulo }}"
                                                    aria-haspopup="dialog"
                                                    aria-controls="image-dialog"
                                                >
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                                        <circle cx="10.8" cy="10.8" r="6.8"></circle>
                                                        <path d="m16 16 5 5"></path>
                                                    </svg>
                                                </button>
                                            </span>
                                        @else
                                            <span class="placeholder">Sem foto</span>
                                        @endif
                                    </div>
                                    <div class="description-box">
                                        <h4>Descrição</h4>
                                        <p class="description">{{ $objeto->descricao ?: 'Sem descrição informada.' }}</p>
                                    </div>
                                </div>

                                <span class="status {{ $objeto->status === 'devolvido' ? 'status-returned' : '' }}">
                                    {{ ucfirst($objeto->status) }}
                                </span>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="pagination">
                    {{ $objetos->links() }}
                </div>
            @endif
        </section>
    </main>

    <dialog class="image-dialog" id="image-dialog" aria-labelledby="image-dialog-title">
        <div class="image-dialog-header">
            <h2 class="image-dialog-title" id="image-dialog-title">Foto do objeto</h2>
            <button class="image-dialog-close" type="button" aria-label="Fechar imagem ampliada">Fechar</button>
        </div>
        <img class="image-dialog-image" src="" alt="">
    </dialog>

    <script>
        const imageDialog = document.querySelector('#image-dialog');
        const expandedImage = imageDialog.querySelector('.image-dialog-image');

        document.querySelectorAll('.image-preview-button').forEach((button) => {
            button.addEventListener('click', () => {
                expandedImage.src = button.dataset.image;
                expandedImage.alt = button.dataset.alt;
                imageDialog.showModal();
            });
        });

        imageDialog.querySelector('.image-dialog-close').addEventListener('click', () => {
            imageDialog.close();
        });

        imageDialog.addEventListener('click', (event) => {
            if (event.target === imageDialog) {
                imageDialog.close();
            }
        });

        imageDialog.addEventListener('close', () => {
            expandedImage.src = '';
            expandedImage.alt = '';
        });
    </script>
</body>
</html>
