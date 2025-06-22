<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Task;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Task::insert([
            [
                'title' => 'Revisar pull requests',
                'description' => 'Analizar y comentar los PR pendientes en el repositorio principal.',
                'is_completed' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Actualizar dependencias',
                'description' => 'Ejecutar composer update y npm update en los proyectos activos.',
                'is_completed' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Escribir pruebas unitarias',
                'description' => 'Agregar tests para los nuevos endpoints de la API.',
                'is_completed' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Documentar endpoints',
                'description' => 'Actualizar la documentación de la API REST en Postman.',
                'is_completed' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Refactorizar código legacy',
                'description' => 'Mejorar la legibilidad y eficiencia del módulo de autenticación.',
                'is_completed' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Reunión diaria de equipo',
                'description' => 'Participar en el daily standup para compartir avances y bloqueos.',
                'is_completed' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
