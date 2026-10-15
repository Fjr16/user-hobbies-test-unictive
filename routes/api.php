<?php

use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'second'])->group(function () {
    Route::post('users/register', "UserController@store");
    Route::post('users/login', "UserController@login");
    Route::post('users/destroy', "UserController@logout");
});