<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Resources\TaskResource;
use Barryvdh\DomPDF\Facade\Pdf; 

class TaskController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        // Inicia la consulta base para las tareas, ordenadas por ID descendente
        $query = Task::orderBy('id', 'desc');

        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where('title', 'like', '%' . $searchTerm . '%')
                ->orWhere('description', 'like', '%' . $searchTerm . '%');
        }

        // Paginación
        $perPage = $request->input('per_page', 10); 
        $tasks = $query->paginate($perPage);

        // Retorna los datos paginados usando el recurso TaskResource
        return response()->json([
            'data' => TaskResource::collection($tasks->items()),
            'current_page' => $tasks->currentPage(),
            'per_page' => $tasks->perPage(),
            'total' => $tasks->total(),
            'last_page' => $tasks->lastPage(),
            'from' => $tasks->firstItem(),
            'to' => $tasks->lastItem(),
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
            'title' => 'sometimes|string|max:50',
            'description' => 'nullable|string',
            'is_completed' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación de los datos',
                'errors' => $validator->errors(),
            ], 400);
        }

        // Usar fill() y save() para una actualización más flexible, solo actualiza los campos presentes en el request
        $task->fill($request->only(['title', 'description', 'is_completed']));
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
    
    //Salvedad: Usé este método para exportar las tareas en formato PDF ya que como es API no debería tener vista HTML.
    public function exportPdf(Request $request)
    {
        $query = Task::query();

        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where('title', 'like', "%{$searchTerm}%")
                    ->orWhere('description', 'like', "%{$searchTerm}%");
        }

        $tasks = $query->orderBy('id', 'asc')->get();

        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Listado de Tareas</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 30px;
                    font-size: 10pt;
                }
                h1 {
                    color: #333;
                    text-align: center;
                    margin-bottom: 20px;
                    font-size: 18pt;
                }
                p {
                    margin-bottom: 10px;
                    font-size: 10pt;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 20px;
                }
                th, td {
                    border: 1px solid #ddd;
                    padding: 8px;
                    text-align: left;
                    vertical-align: top;
                }
                th {
                    background-color: #f2f2f2;
                    color: #555;
                    font-weight: bold;
                }
                .completed {
                    color: green;
                    font-weight: bold;
                }
                .pending {
                    color: orange;
                    font-weight: bold;
                }
                .description-cell {
                    max-width: 250px;
                    word-wrap: break-word;
                }
                /* Pie de página (DomPDF lo posicionará) */
                .footer {
                    position: fixed;
                    bottom: 0;
                    width: 100%;
                    text-align: center;
                    font-size: 8pt;
                    color: #777;
                }
            </style>
        </head>
        <body>
            <h1>Listado Completo de Tareas del Gestor</h1>
            <p>Fecha de Generación: ' . now()->format('d/m/Y H:i:s') . '</p>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Descripción</th>
                        <th>Estado</th>
                        <th>Creada</th>
                        <th>Actualizada</th>
                    </tr>
                </thead>
                <tbody>';
                    // Iterar sobre las tareas para construir las filas de la tabla
                    foreach ($tasks as $task) {
                        $statusClass = $task->is_completed ? 'completed' : 'pending';
                        $statusText = $task->is_completed ? 'Completada' : 'Pendiente';
                        $description = htmlspecialchars($task->description ?? 'N/A');

                        $html .= '
                                <tr>
                                    <td>' . $task->id . '</td>
                                    <td>' . htmlspecialchars($task->title) . '</td>
                                    <td class="description-cell">' . $description . '</td>
                                    <td class="' . $statusClass . '">' . $statusText . '</td>
                                    <td>' . $task->created_at->format('d/m/Y H:i') . '</td>
                                    <td>' . $task->updated_at->format('d/m/Y H:i') . '</td>
                                </tr>';
                        }
                        $html .= '
                </tbody>
            </table>

            <div class="footer">
                Generado por el Sistema de Gestión de Tareas de Camilo Gaviria
            </div>
        </body>
        </html>';

        // 3. Generar el PDF a partir de la cadena HTML
        $pdf = Pdf::loadHtml($html);

        // 4. Devolver el PDF para descarga
        return $pdf->download('listado_tareas_' . now()->format('Ymd_His') . '.pdf');
    }
}