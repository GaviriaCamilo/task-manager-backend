# ⚙️ Task Manager Backend (Laravel API)

Este repositorio contiene el backend de la aplicación de gestión de tareas, construido con Laravel para ofrecer una API RESTful robusta y eficiente.

## 🚀 Características

- **API RESTful**: Endpoints bien definidos para la gestión de tareas.
- **CRUD Completo**: Permite crear, leer (incluyendo paginación), actualizar y eliminar tareas.
- **Base de Datos MySQL**: Utiliza MySQL para el almacenamiento persistente de datos.
- **Migraciones y Seeders**: Gestión de la estructura de la base de datos y población con datos de ejemplo.
- **Validación de Peticiones**: Asegura la integridad de los datos entrantes.
- **API Resources**: Formatea las respuestas JSON de manera consistente y optimizada.
- **Manejo de Errores**: Responde con códigos de estado HTTP apropiados y mensajes de error claros.
- **CORS Configurado**: Permite la comunicación segura con el frontend.
- **Paginación**: Soporte para paginación de las tareas en el listado.
- **Exportación a PDF**: Funcionalidad para exportar el listado de tareas a un documento PDF.

## 🛠 Tecnologías Utilizadas

- Laravel 12.x
- PHP 8.2+
- MySQL
- Composer
- Eloquent ORM
- Laravel API Resources
- barryvdh/laravel-dompdf: Para la generación de documentos PDF a partir de HTML.

## 📋 Requisitos del Sistema

- PHP >= 8.2 (con extensiones php-gd y php-mbstring habilitadas para la generación de PDF)
- Composer
- MySQL
- Git
- Un servidor web local compatible con PHP (ej. Apache, Nginx, Laravel Herd, XAMPP, Laragon).

## 🚀 Instalación y Configuración

Sigue estos pasos para poner en marcha el backend en tu entorno local:

### 1. Clonar el repositorio:

```bash
git clone https://github.com/GaviriaCamilo/task-manager-backend.git
cd task-manager-backend
```

### 2. Instalar dependencias de Composer:

```bash
composer install
```

### 3. Configurar el archivo de entorno:

Copia el archivo de ejemplo `.env.example` a `.env`:

```bash
cp .env.example .env
```

### 4. Generar la clave de la aplicación:

```bash
php artisan key:generate
```

### 5. Configurar la base de datos en .env:

Asegúrate de tener un servidor MySQL funcionando y una base de datos creada (ej. `taskmanager`). Luego, actualiza las credenciales en tu archivo `.env`:

```env
DB_DATABASE=taskmanager
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

### 6. Ejecutar migraciones y seeders:

Esto creará la tabla `tasks` y la poblará con datos de ejemplo.

```bash
php artisan migrate:fresh --seed
```

### 7. Configurar CORS:

Para permitir que tu frontend (que probablemente se ejecutará en un puerto diferente, como `http://localhost:5173`) se comunique con este backend, edita el archivo `config/cors.php` y añade la URL de tu frontend a `allowed_origins`:

```php
// config/cors.php
'allowed_origins' => [
    'http://localhost:5173', // Añade la URL de tu frontend aquí
    // ... otras configuraciones
],
```

Luego, limpia la caché de configuración:

```bash
php artisan config:clear
```

### 8. Iniciar el servidor de desarrollo de Laravel:

```bash
php artisan serve
```

El backend estará disponible en `http://localhost:8000`.

## 📁 Estructura del Proyecto

```
task-manager-backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/
│   │   │   │   └── TaskController.php   # Controla la lógica de las peticiones HTTP para las tareas
│   │   │   └── Controller.php           # Controlador base de Laravel
│   │   ├── Resources/
│   │   │   └── TaskResource.php         # Transforma los modelos de tareas en formatos JSON
│   │   └── Models/
│   │       ├── Task.php                 # Modelo Eloquent que representa la tabla 'tasks'
│   │       └── User.php                 # Modelo Eloquent de usuario (Laravel por defecto)
├── config/                              # Archivos de configuración de la aplicación
├── database/
│   ├── factories/                       # Factorías de modelos para generar datos de prueba
│   ├── migrations/
│   │   └── 2025_06_21_013208_create_tasks_table.php # Definición del esquema de la tabla tasks
│   └── seeders/
│       ├── DatabaseSeeder.php           # Seeder principal que ejecuta otros seeders
│       └── TaskSeeder.php               # Script para poblar la tabla tasks con datos de prueba
├── routes/
│   ├── api.php                          # Define todas las rutas de la API disponibles
│   └── web.php                          # Rutas web (estándar de Laravel)
├── .env.example                         # Archivo de ejemplo para configuración de entorno
├── composer.json                        # Define las dependencias de PHP y los scripts de Composer
└── README.md                            # Este archivo
```

## 🔌 Esquema de la Base de Datos (tasks table)

| Columna | Tipo | Descripción |
|---------|------|-------------|
| id | Primary key | Identificador único de la tarea |
| title | String | Título de la tarea |
| description | Text, nullable | Descripción detallada de la tarea |
| is_completed | Boolean, default false | Indica si la tarea está completada o pendiente |
| created_at | Timestamp | Fecha y hora de creación de la tarea |
| updated_at | Timestamp | Última fecha y hora de actualización |

## 🌐 Endpoints de la API

Todos los endpoints están prefijados con `/api`.

| Método | Endpoint | Descripción | Parámetros |
|--------|----------|-------------|------------|
| GET | `/tasks` | Obtener todas las tareas paginadas | `page` (int, opcional), `per_page` (int, opcional), `search` (string, opcional) |
| GET | `/tasks/export-pdf` | Exportar todas las tareas a PDF | `search` (string, opcional, para filtrar las tareas en el PDF) |
| POST | `/tasks` | Crear una nueva tarea | `title` (string, requerido), `description` (string, opcional), `is_completed` (boolean, opcional) |
| GET | `/tasks/{id}` | Obtener una tarea específica por ID | - |
| PUT | `/tasks/{id}` | Actualizar una tarea específica | `title` (string), `description` (string), `is_completed` (boolean) - todos opcionales |
| DELETE | `/tasks/{id}` | Eliminar una tarea específica | - |

## 📢 Ejemplos de uso con curl

```bash
# Obtener todas las tareas (primera página, 10 por página por defecto)
curl http://localhost:8000/api/tasks

# Obtener tareas paginadas (página 2, 5 por página)
curl "http://localhost:8000/api/tasks?page=2&per_page=5"

# Obtener tareas filtradas por búsqueda
curl "http://localhost:8000/api/tasks?search=revisar"

# Crear una nueva tarea
curl -X POST http://localhost:8000/api/tasks \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Mi nueva tarea desde curl",
    "description": "Descripción de la tarea creada con curl",
    "is_completed": false
  }'

# Actualizar el estado de completado de una tarea (ej. tarea con ID 1)
curl -X PUT http://localhost:8000/api/tasks/1 \
  -H "Content-Type: application/json" \
  -d '{"is_completed": true}'

# Actualizar el título y descripción de una tarea (ej. tarea con ID 1)
curl -X PUT http://localhost:8000/api/tasks/1 \
  -H "Content-Type: application/json" \
  -d '{"title": "Título de tarea 1 actualizado", "description": "Descripción modificada de la tarea 1"}'

# Eliminar una tarea (ej. tarea con ID 2)
curl -X DELETE http://localhost:8000/api/tasks/2

# Exportar todas las tareas a PDF
curl -o listado_tareas.pdf http://localhost:8000/api/tasks/export-pdf

# Exportar tareas filtradas a PDF
curl -o listado_tareas_filtradas.pdf "http://localhost:8000/api/tasks/export-pdf?search=documentar"
```

## ⚠ Solución de Problemas

### Error: "Connection refused"
**Causa**: El servidor MySQL o el servidor de Laravel no están ejecutándose.

**Solución**:
```bash
# Verifica que MySQL esté ejecutándose
sudo service mysql status # o equivalente en tu sistema
# Asegúrate de que el servidor Laravel esté ejecutándose
php artisan serve
```

### Error: "CORS policy: No 'Access-Control-Allow-Origin' header is present"
**Causa**: Tu navegador está bloqueando las solicitudes entre diferentes orígenes.

**Solución**: Asegúrate de haber configurado correctamente el archivo `config/cors.php` con la URL de tu frontend en `allowed_origins` y haber limpiado la caché de configuración (`php artisan config:clear`).

### Error: 404 Not Found al intentar acceder a /api/tasks/export-pdf
**Causa**: La ruta GET `/api/tasks/{id}` definida por apiResource está interceptando la solicitud `/api/tasks/export-pdf`.

**Solución**: En tu archivo `routes/api.php`, asegúrate de que la ruta específica para `export-pdf` esté definida antes de la declaración de `Route::apiResource('tasks', TaskController::class);`. Después de modificar, limpia la caché de rutas (`php artisan route:clear`).

### Error: PDF generado en blanco o con problemas de estilo
**Causa**: Problemas con la renderización de HTML/CSS por dompdf o falta de extensiones PHP.

**Solución**:
- Verifica que las extensiones PHP `php-gd` y `php-mbstring` estén instaladas y habilitadas.
- Asegúrate de que todo el CSS utilizado para el PDF esté en línea o dentro de la etiqueta `<style>`.

## 🔮 Testing

Puedes ejecutar los tests del backend con:

```bash
php artisan test
```

## 📦 Build para Producción

Para optimizar el backend para un entorno de producción:

```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 📄 Licencia

Este proyecto está bajo la Licencia MIT - consulta el archivo LICENSE para más detalles.

## 👤 Autor

**Camilo Gaviria**
- GitHub: [@GaviriaCamilo](https://github.com/GaviriaCamilo)
- Email: camilogav24@gmail.com

## 🔗 Enlaces

- **Repositorio del Frontend**: [Enlace al repositorio del frontend] (Añadir el enlace una vez creado)
- **Repositorio**: https://github.com/GaviriaCamilo/task-manager-backend
- **Demo en vivo**: (Próximamente)