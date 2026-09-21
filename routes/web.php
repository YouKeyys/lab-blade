<?php

use Illuminate\Support\Facades\Route;

// Redirect root ke /overview agar otomatis terarah saat buka IP server
Route::redirect('/', '/overview');

// Halaman utama / Overview
Route::get('/overview', function () {
    return view('dashboard'); // Memanggil file overview.blade.php
});

// Halaman Login
Route::get('/login', function () {
    return view('login');
});
// UBAH BAGIAN INI:
Route::get('/admin', function () {
    return view('admin');
});

// Halaman Supervisor Dashboard (Akan kita buat nanti)
Route::get('/supervisor', function () {
    return view('supervisor');
});

// Halaman Device Management
Route::get('/devices', function () {
    return view('devices');
});

// Halaman Alert Management
Route::get('/alerts', function () {
    return view('alerts');
});

// Halaman Lab Management
Route::get('/labs', function () {
    return view('lab-detail');
});

// Halaman Graph
Route::get('/graph', function () {
    return view('graph');
});

// Halaman User Management
Route::get('/users', function () {
    return view('users');
});