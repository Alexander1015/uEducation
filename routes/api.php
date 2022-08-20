<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// Auth
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\LogoutController;
// Carousel
use App\Http\Controllers\Carousel\CarouselController;
use App\Http\Controllers\Carousel\ListController;
// Profile
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Profile\PasswordProfileController;
// User
use App\Http\Controllers\Users\UserController;
use App\Http\Controllers\Users\PasswordUserController;
use App\Http\Controllers\Users\StatusUserController;
// Student
use App\Http\Controllers\Students\StudentController;
use App\Http\Controllers\Students\PasswordStudentController;
use App\Http\Controllers\Students\StatusStudentController;
// Subjects
use App\Http\Controllers\Subjects\SubjectController;
use App\Http\Controllers\Subjects\StatusSubjectController;
use App\Http\Controllers\Subjects\TopicsController as GetTopicsController;
use App\Http\Controllers\Subjects\UsersController as GetUsersController;
use App\Http\Controllers\Subjects\StudentsController as GetStudentsController;
use App\Http\Controllers\Subjects\UsersAccessController as SetUsersAccessController;
// Tags
use App\Http\Controllers\Tags\TagController;
use App\Http\Controllers\Tags\StatusTagController;
//Topics
use App\Http\Controllers\Topics\TopicController;
use App\Http\Controllers\Topics\StatusTopicController;
use App\Http\Controllers\Topics\BodyController;
use App\Http\Controllers\Topics\GetTagsSubjectsController;
use App\Http\Controllers\Topics\UploadImageController;
//Public
use App\Http\Controllers\Public\GetDataController as PublicDataController;
use App\Http\Controllers\Public\GetTopicController as PublicTopicController;
use App\Http\Controllers\Public\GetCarouselController as PublicCarouselController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->get('/authenticated', function () {
    return true;
});

//Public
Route::resource("getdata", PublicDataController::class);
Route::resource("gettopic", PublicTopicController::class);
Route::resource("getcarousel", PublicCarouselController::class);

// Auth
Route::resource("auth", AuthController::class);
Route::resource('logout', LogoutController::class);
// Corousel
Route::resource('carousel', CarouselController::class)->middleware('auth');
Route::resource('carousel/list', ListController::class)->middleware('auth');
// Profile
Route::resource('profile', ProfileController::class)->middleware('auth');
Route::resource('profile/password', PasswordProfileController::class)->middleware('auth');
// Usuarios
Route::resource('user', UserController::class)->middleware('auth');
Route::resource('user/password', PasswordUserController::class)->middleware('auth');
Route::resource('user/status', StatusUserController::class)->middleware('auth');
//Estudiantes
Route::resource('student', StudentController::class)->middleware('auth');
Route::resource('student/password', PasswordStudentController::class)->middleware('auth');
Route::resource('student/status', StatusStudentController::class)->middleware('auth');
// Subjects
Route::resource('subject', SubjectController::class)->middleware('auth');
Route::resource('subject/status', StatusSubjectController::class)->middleware('auth');
Route::resource('subject/gettopics', GetTopicsController::class)->middleware('auth');
Route::resource('getusr', GetUsersController::class)->middleware('auth');
Route::resource('getstd', GetStudentsController::class)->middleware('auth');
Route::resource('setacs', SetUsersAccessController::class)->middleware('auth');
// Tags
Route::resource('tag', TagController::class)->middleware('auth');
Route::resource('tag/status', StatusTagController::class)->middleware('auth');
// Topics
Route::resource('topic', TopicController::class)->middleware('auth');
Route::resource('topic/status', StatusTopicController::class)->middleware('auth');
Route::resource('topic/body', BodyController::class)->middleware('auth');
Route::resource('getts', GetTagsSubjectsController::class)->middleware('auth');
Route::resource('topic/upload', UploadImageController::class)->middleware('auth');
