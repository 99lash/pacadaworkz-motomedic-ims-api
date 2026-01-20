<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DummyController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-permissions', function () {
    try {
        \File::put(storage_path('test.txt'), 'ok');
        return 'Storage writable!';
    } catch (\Exception $e) {
        return 'Error: '.$e->getMessage();
    }
});


Route::get('/api/dummy', [DummyController::class, 'dummy']);
