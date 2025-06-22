<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Resources\TaskResource;

class TaskController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $tasks = Task::orderBy('id', 'desc')->get();
        
        return response()->json([
            'data' => TaskResource::collection($tasks),
            'total' => $tasks->count(),
        ], 200);
    }

    public function show($id): JsonResponse
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'message' => 'Tarea no encontrada',
            ], 404);
        }

        return response()->json(new TaskResource($task), 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:50',
            'description' => 'nullable|string',
            'is_completed' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación de los datos',
                'errors' => $validator->errors(),
            ], 400);
        }

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'is_completed' => $request->is_completed ?? false,
        ]);

        return response()->json([
            'message' => 'Tarea creada correctamente',
            'task' => new TaskResource($task),
        ], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'message' => 'Tarea no encontrada',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'is_completed' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación de los datos',
                'errors' => $validator->errors(),
            ], 400);
        }

        // Usar fill() y save() para una actualización más flexible
        $updateData = $request->only(['title', 'description', 'is_completed']);
        $task->fill($updateData);
        $task->save();

        return response()->json([
            'message' => 'Tarea actualizada correctamente',
            'task' => new TaskResource($task),
        ], 200);
    }

    public function destroy($id): JsonResponse
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'message' => 'Tarea no encontrada',
            ], 404);
        }

        $task->delete();

        return response()->json([
            'message' => 'Tarea eliminada correctamente',
        ], 200);
    }

    // Método específico para toggle del checkbox (opcional)
    public function toggle($id): JsonResponse
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'message' => 'Tarea no encontrada',
            ], 404);
        }

        $task->is_completed = !$task->is_completed;
        $task->save();

        return response()->json([
            'message' => 'Estado de tarea actualizado correctamente',
            'task' => new TaskResource($task),
        ], 200);
    }
}