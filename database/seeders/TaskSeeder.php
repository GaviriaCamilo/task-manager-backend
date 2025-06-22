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
            [
                'title' => 'Planificar Sprint Siguiente',
                'description' => 'Definir objetivos y tareas para el próximo sprint de desarrollo.',
                'is_completed' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Diseñar base de datos',
                'description' => 'Crear el esquema de la base de datos para el nuevo módulo de usuarios.',
                'is_completed' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Configurar CI/CD',
                'description' => 'Implementar integración y entrega continua con GitHub Actions.',
                'is_completed' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Investigar nueva tecnología',
                'description' => 'Explorar WebSockets para notificaciones en tiempo real.',
                'is_completed' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Preparar presentación cliente',
                'description' => 'Crear diapositivas para la demostración del prototipo.',
                'is_completed' => true, 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Revisar logs de producción',
                'description' => 'Monitorear y analizar los logs del servidor en busca de errores.',
                'is_completed' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Optimizar consultas SQL',
                'description' => 'Mejorar el rendimiento de las consultas lentas en el panel de administración.',
                'is_completed' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Crear tickets de soporte',
                'description' => 'Registrar problemas reportados por los usuarios en Jira.',
                'is_completed' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Actualizar README.md',
                'description' => 'Añadir instrucciones de despliegue y configuración para nuevos desarrolladores.',
                'is_completed' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
