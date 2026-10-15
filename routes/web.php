<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// 
Route::get('/',function(){
    return redirect('web/users/index');
});
Route::prefix('/web/users')->group(function(){
    Route::get('index', [UserController::class, 'index'])->name('users.index');
    Route::get('create', [UserController::class, 'create'])->name('users.create');
    Route::get('show/{id}', [UserController::class, 'show'])->name('users.show');
    Route::post('store', [UserController::class, 'store'])->name('users.store');
    Route::put('update/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('delete/{id}', [UserController::class, 'destroy'])->name('users.destroy');
});