<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// Auth
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\LogoutController;
// Profile
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Profile\PasswordProfileController;
// User
use App\Http\Controllers\Users\UserController;
use App\Http\Controllers\Users\PasswordUserController;
use App\Http\Controllers\Users\StatusUserController;
// Subjects
use App\Http\Controllers\Subjects\SubjectController;
use App\Http\Controllers\Subjects\StatusSubjectController;
// Tags
use App\Http\Controllers\Tags\TagController;
use App\Http\Controllers\Tags\StatusTagController;
//Topics
use App\Http\Controllers\Topics\TopicController;
use App\Http\Controllers\Topics\StatusTopicController;
use App\Http\Controllers\Topics\BodyController;
use App\Http\Controllers\Topics\TagsController as TopicTagsController;
use App\Http\Controllers\Topics\SubjectsController as TopicSubjectsController;
use App\Http\Controllers\Topics\UploadImageController;
//SLug
use App\Http\Controllers\Slug\SlugController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->get('/authenticated', function () {
    return true;
});

// Auth
Route::resource("auth", AuthController::class);
Route::resource('logout', LogoutController::class);
// Profile
Route::resource('profile', ProfileController::class)->middleware('auth');
Route::resource('profile/password', PasswordProfileController::class)->middleware('auth');
// Usuarios
Route::resource('user', UserController::class)->middleware('auth');
Route::resource('user/password', PasswordUserController::class)->middleware('auth');
Route::resource('user/status', StatusUserController::class)->middleware('auth');
// Subjects
Route::resource('subject', SubjectController::class)->middleware('auth');
Route::resource('subject/status', StatusSubjectController::class)->middleware('auth');
// Tags
Route::resource('tag', TagController::class)->middleware('auth');
Route::resource('tag/status', StatusTagController::class)->middleware('auth');
// Topics
Route::resource('topic', TopicController::class)->middleware('auth');
Route::resource('topic/status', StatusTopicController::class)->middleware('auth');
Route::resource('topic/body', BodyController::class)->middleware('auth');
Route::resource('getsubjects', TopicSubjectsController::class)->middleware('auth');
Route::resource('gettags', TopicTagsController::class)->middleware('auth');
Route::resource('topic/upload', UploadImageController::class)->middleware('auth');
//Slug
Route::resource('slug', SlugController::class)->middleware('auth');
