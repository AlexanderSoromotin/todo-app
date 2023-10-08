<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    // Получение списка задач пользователя
    public function index(Request $request) {
        $query = Task::where('user_id', Auth::user()->id);

        // Количество задач на одной странице ответа
        $itemsOnPage = 5;

        // Поля, по которым можно сортировать список
        $sortableFields = ['created_at', 'updated_at', 'due_date', 'is_completed'];

        // Формирование правила сортировки
        $sortType = ["created_at", "desc"];
        if (!empty($request->input('sort_by'))) {
            // Разделение строки типа "created_at:desc" по знаку ":" для формирования правила сортировки
            $sortTypeTmp = explode(":", $request->input('sort_by'));

            // Валидация правила сортировки
            if (in_array($sortTypeTmp[0], $sortableFields) and in_array($sortTypeTmp[1], ['asc', 'desc'])) {
                $sortType = $sortTypeTmp;
            }
        }

        // Применение сортировки
        $query->orderBy($sortType[0], $sortType[1]);

        // Фильтр по статусу is_completed
        if ($request->has('is_completed')) {
            $query->where('is_completed', $request->input('is_completed'));
        }

        // Фильтр по дате создания created_at
        if ($request->has('created_at')) {
            $query->whereDate('created_at', $request->input('created_at'));
        }

        // Фильтр по дате выполнения due_date
        if ($request->has('due_date')) {
            $query->whereDate('due_date', $request->input('due_date'));
        }

        // Получение списка задач
        $tasks = $query->paginate($itemsOnPage);

        // Скрытие ненужных полей из ответа
        $tasks = $tasks->each(function ($task) {
            $task->makeHidden(['deleted_at']);
        });

        return response()->json(['tasks' => $tasks]);
    }

    public function show($id) {
        // Получите список всех заметок (или задач) текущего пользователя
        $task = Auth::user()->tasks()->find($id);

        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        $task->makeHidden(['deleted_at']);

        return response()->json(['task' => $task]);
    }

    // Создание задачи
    public function store(Request $request) {
        // Валидация данных
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'due_date' => 'nullable|date_format:Y-m-d H:i:s',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $task = [
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'user_id' => Auth::user()->id,
        ];

        if (empty($task["due_date"])) {
            unset($task["due_date"]);
        }

        // Создание задачи
        $newTask = Task::create($task);

        return response()->json(['message' => 'Task created successfully', 'task' => $newTask], 201);
    }

    // Редактирование задачи
    public function update(Request $request, $id) {
        $task = Task::find($id);

        // Проверка существования задачи
        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        // Проверка прав пользователя к управлению задачей
        if ($task->user_id !== auth()->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Валидация данных для редактирования
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'due_date' => 'required|date_format:Y-m-d H:i:s',
            'is_completed' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Обновление данных задачи
        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'is_completed' => $request->is_completed,
        ]);

        return response()->json(['message' => 'Task updated successfully', 'task' => $task]);
    }

    // Удаление задачи
    public function destroy($id) {
        // Поиск задачи по идентификатору
        $task = Task::find($id);

        // Проверка существования задачи
        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        // Проверка прав пользователя к управлению задачей
        if ($task->user_id !== auth()->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Удаление задачи
        $task->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }

    // Отметка задачи, как выполненной
    public function markAsCompleted($id) {
        // Поиск задачи по идентификатору
        $task = Task::find($id);

        // Проверка существования задачи
        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        // Проверка прав пользователя к управлению задачей
        if ($task->user_id !== auth()->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Изменение статуса
        $task->update([
            "is_completed" => 1
        ]);

        // Скрываем лишние поля
        $task->makeHidden(['deleted_at']);

        return response()->json(['message' => 'Task marked as completed successfully', 'task' => $task]);
    }

    // Отметка задачи, как не выполненной
    public function markAsIncomplete($id) {
        // Поиск задачи по идентификатору
        $task = Task::find($id);

        // Проверка существования задачи
        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        // Проверка прав пользователя к управлению задачей
        if ($task->user_id !== auth()->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Изменение статуса
        $task->update([
            "is_completed" => 0
        ]);

        // Скрываем лишние поля
        $task->makeHidden(['deleted_at']);

        return response()->json(['message' => 'Task marked as incomplete successfully', 'task' => $task]);
    }
}
