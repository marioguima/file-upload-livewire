<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\User\UploadPhoto;

Route::get('/upload', UploadPhoto::class);
Route::get('/file-upload', function() {
    return view('file-upload');
});

Route::get('/', function () {
    return view('welcome');
});
