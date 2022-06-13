<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->get('/authenticated', function () {
    return true;
});

// Login
Route::resource('auth', App\Http\Controllers\AuthController::class);
Route::resource('logout', App\Http\Controllers\LogoutController::class);
// Usuarios
Route::resource('user', App\Http\Controllers\UserController::class)->middleware('auth');
Route::resource('user/password', App\Http\Controllers\PasswordUserController::class)->middleware('auth');
Route::resource('user/status', App\Http\Controllers\StatusUserController::class)->middleware('auth');
// Perfil
Route::resource('profile', App\Http\Controllers\ProfileController::class)->middleware('auth');
Route::resource('profile/password', App\Http\Controllers\PasswordProfileController::class)->middleware('auth');
// Cursos o materias
Route::resource('subject', App\Http\Controllers\SubjectController::class)->middleware('auth');
Route::resource('subject/status', App\Http\Controllers\StatusSubjectController::class)->middleware('auth');
// Tags
Route::resource('tag', App\Http\Controllers\TagController::class)->middleware('auth');
Route::resource('tag/status', App\Http\Controllers\StatusTagController::class)->middleware('auth');
// Temas
Route::resource('topic', App\Http\Controllers\TopicController::class)->middleware('auth');
Route::resource('topic/status', App\Http\Controllers\StatusTopicController::class)->middleware('auth');