<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ObjetoController;
use App\Http\Controllers\SolicitacaoController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ObjetoController::class, 'index'])
    ->name('home');

Route::get('/objetos', [ObjetoController::class, 'index'])
    ->name('objetos.index');

Route::get('/objetos/{objeto}/solicitar', [ObjetoController::class, 'solicitar'])
    ->name('objetos.solicitar');

Route::post('/objetos/{objeto}/solicitar', [SolicitacaoController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('objetos.solicitacoes.store');

Route::middleware(['auth', 'secretaria'])->group(function () {
    Route::get('/solicitacoes', [SolicitacaoController::class, 'index'])
        ->name('solicitacoes.index');

    Route::patch('/solicitacoes/{solicitacao}/aprovar', [SolicitacaoController::class, 'approve'])
        ->name('solicitacoes.aprovar');

    Route::patch('/solicitacoes/{solicitacao}/recusar', [SolicitacaoController::class, 'reject'])
        ->name('solicitacoes.recusar');

    Route::get('/objetos/criar', [ObjetoController::class, 'create'])
        ->name('objetos.create');

    Route::post('/objetos', [ObjetoController::class, 'store'])
        ->name('objetos.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/sair', [LoginController::class, 'destroy'])
        ->name('logout');
});

Route::middleware('guest')->group(function () {
    Route::get('/entrar', [LoginController::class, 'create'])
        ->name('login');

    Route::post('/entrar', [LoginController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('login.store');
});
