<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TodoController;

// Endpoint login user
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

// Endpoint yang memerlukan autentikasi
Route::middleware('auth:api')->group(function () {
    
    // Endpoint logout user
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

    // Endpoint untuk mengambil data user yang sedang login
    Route::get('/user', function (Request $request) {
        return response()->json([
            'status_code' => 200,
            'message' => 'User sedang login',
            'data' => $request->user(),
        ]);
    })->name('auth.user');

    // Endpoint untuk pencarian todo berdasarkan judul atau kategori
    Route::get('/todos/search', [TodoController::class, 'search'])->name('todos.search');

    // Endpoint resource todos (CRUD)
    Route::apiResource('todos', TodoController::class)->names([
        'index'   => 'todos.index',    // GET todos → ambil semua
        'store'   => 'todos.store',    // POST todos → tambah baru
        'update'  => 'todos.update',   // PUT todos/{id} → perbarui
        'destroy' => 'todos.destroy',  // DELETE todos/{id} → hapus
        'show'    => 'todos.show',     // GET todos/{id} → detail (jika ada)
    ]);
});
