<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);
Route::get('/register/verify', [RegisterController::class, 'verify'])->name('register.verify');

Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/profile', [ProfileController::class, 'index'])->name('profile');

Route::get('/book-cards-demo', function () {
    $books = [
        [
            'id' => 'BK001',
            'title' => 'The Hobbit',
            'author' => 'J.R.R. Tolkien',
            'category' => 'Fantasy',
            'status' => 'available',
            'rating' => 4.7,
            'rating_count' => 18,
            'owner_id' => 'USR001',
            'owner_name' => 'Aisha Rahman',
            'owner_avatar' => 'aisha.jpg',
            'hall' => '1',
            'cover_image' => '',
        ],
        [
            'id' => 'BK002',
            'title' => 'Clean Code',
            'author' => 'Robert C. Martin',
            'category' => 'Programming',
            'status' => 'borrowed',
            'rating' => 4.4,
            'rating_count' => 12,
            'owner_id' => 'USR002',
            'owner_name' => 'Nabil Hasan',
            'owner_avatar' => '',
            'hall' => '3',
            'cover_image' => '',
        ],
    ];

    return view('demo.book-cards', compact('books'));
});
