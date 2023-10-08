<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
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

// Защищённые маршруты
Route::middleware(['auth:sanctum'])->group(function () {
    // Получение данных о пользователе
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Выход пользователя и удаление токена
    Route::post('/logout', [AuthController::class, 'logout']);

    // Создание задачи
    Route::post('/tasks', [TaskController::class, 'store']);

    // Получение списка задач
    Route::get('/tasks', [TaskController::class, 'index']);

    // Получение данных определённой задачи
    Route::get('/tasks/{id}', [TaskController::class, 'show']);

    // Редактирование задачи
    Route::put('/tasks/{id}', [TaskController::class, 'update']);

    // Отметка задачи "Выполнено"
    Route::put('/tasks/{id}/complete', [TaskController::class, 'markAsCompleted']);

    // Отметка задачи "Не выполнено"
    Route::put('/tasks/{id}/incomplete', [TaskController::class, 'markAsIncomplete']);

    // Удаление задачи
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);
});

// Регистрация нового пользователя
Route::post('/register', [AuthController::class, 'register']);

// Аутентификация пользователя и получение токена
Route::post('/login', [AuthController::class, 'login']);

// Возвращение ошибки о некорректности токена
Route::any('/errorToken', function () {
    return response()->json(['message' => 'Unauthorized: Invalid token'], 401);
})->name("login");


