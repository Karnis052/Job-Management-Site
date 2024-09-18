<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get("/users/", [UserController::class,"index"])->name("users.index");
Route::get("/users/{id}", [UserController::class,"getOne"])->name("users.getUserById");
Route::post("/users/", [UserController::class,"create"])->name("users.create");
Route::put("/users/{id}", [UserController::class,"update"])->name("users.update");
Route::delete("/users/{id}", [UserController::class,"delele"])->name('users.delete');


Route::get("/posts/", [PostController::class,"index"])->name("posts.index");
Route::get("/posts/{id}", [PostController::class,"getOne"])->name("posts.getPostById");
Route::post("/posts/", [PostController::class,"create"])->name("posts.create");
Route::put("/posts/{id}", [PostController::class,"update"])->name("posts.update");
Route::delete("/posts/{id}", [PostController::class, "delete"])->name("posts.delete");

Route::post("/login/", [UserController::class,"login"])->name("users.login");
Route::post("/logout/", [UserController::class,"logout"])->name("users.logout");
Route::get("/user/", [UserController::class,"user"])->name("users.getUser");


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
