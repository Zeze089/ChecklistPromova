<?php

use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HistoricoController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Redirecionar página inicial para dashboard ou login
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
});

// Rotas de autenticação
Auth::routes(['register' => false]); // Desabilita registro público

// Área protegida - Requer autenticação
Route::middleware('auth')->group(function () {

    // Dashboard (página inicial após login)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Redirecionar /home para dashboard
    Route::get('/home', function () {
        return redirect()->route('dashboard');
    });

    Route::get('/checklist/download/{id}', [ChecklistController::class, 'downloadPDF'])
    ->name('checklist.download')
    ->middleware('auth');

    Route::get('/lista', [ChecklistController::class, 'index'])->name('checklist');
    // create
    Route::get('/checklist', [ChecklistController::class, 'create'])->name('checklist.create');
    // store
    Route::post('/checklist/save-completed', [ChecklistController::class, 'saveCompleted'])
        ->name('checklist.save-completed')
        ->middleware('auth');
    // edit
    Route::get('/checklist/{id}/edit', [ChecklistController::class, 'edit'])
        ->name('checklist.edit')
        ->middleware('auth');
    // update
    Route::put('/checklist/{id}', [ChecklistController::class, 'update'])->name('checklist.update');
    // Histórico
    Route::get('/historico', [HistoricoController::class, 'historico'])->name('historico');
    Route::get('/historico/{checklist}', [HistoricoController::class, 'show'])->name('historico.show');

    // Registro (apenas para admins)
    Route::middleware('admin')->group(function () {
        Route::get('/register', function () {
            return view('auth.register');
        })->name('register');

        Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);
    });
});
