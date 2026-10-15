<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// 
Route::get('/',function(){
    return view('pages.users.index');
});
Route::get('users/index', [UserController::class, 'index'])->name('users.index');
Route::get('users/create', [UserController::class, 'create'])->name('users.create');
Route::post('users/store', [UserController::class, 'store'])->name('users.store');
Route::put('users/update/{id}', [UserController::class, 'update'])->name('users.update');
Route::delete('users/delete/{id}', [UserController::class, 'destroy'])->name('users.destroy');
