<?php
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Swagger Documentation - редирект на Swagger UI
Route::get('/docs', function () {
    //return redirect()->to('/spectrum/openapi.html');
});


// Protected routes (требуют авторизации)
Route::middleware('auth:sanctum')->group(function () {

});
