
<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RecoleccionDeDatosController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\RolSectorAgropecuarioController;
use App\Http\Controllers\ProfesorController;
use App\Http\Controllers\VisitanteController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectDataTableController;

Route::resource('posts', PostController::class)->middleware(['auth', 'verified'])->names('posts');

// Rutas específicas primero (sin parámetros)
Route::middleware(['auth', 'verified', 'role:admin,profesor'])->group(function () {
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
});

// Rutas de proyectos con filtrado por roles
Route::middleware(['auth', 'verified', 'role:admin,profesor,estudiante'])->group(function () {
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
});

Route::middleware(['auth', 'verified', 'role:admin,profesor'])->group(function () {
    Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
});

// Rutas para tablas dinámicas - Visualización para todos los roles autorizados
Route::middleware(['auth', 'verified', 'role:admin,profesor,estudiante'])->group(function () {
    Route::get('/projects/{project}/data-table', [ProjectDataTableController::class, 'show'])->name('projects.data-table');
});

// Rutas para tablas dinámicas - Gestión para admin, profesor y estudiante
Route::middleware(['auth', 'verified', 'role:admin,profesor,estudiante'])->group(function () {
    Route::post('/projects/{project}/data-table/columns', [ProjectDataTableController::class, 'addColumn'])->name('projects.data-table.add-column');
    Route::delete('/projects/{project}/data-table/columns/{column}', [ProjectDataTableController::class, 'removeColumn'])->name('projects.data-table.remove-column');
    Route::put('/projects/{project}/data-table/columns/{column}/options', [ProjectDataTableController::class, 'updateColumnOptions'])->name('projects.data-table.update-column-options');
    
    // Gestión de datos
    Route::get('/projects/{project}/data-table/manage-data', [ProjectDataTableController::class, 'manageData'])->name('projects.data-table.manage-data');
    Route::post('/projects/{project}/data-table/records', [ProjectDataTableController::class, 'storeRecord'])->name('projects.data-table.store-record');
    Route::get('/projects/{project}/data-table/records/{record}/edit', [ProjectDataTableController::class, 'editRecord'])->name('projects.data-table.edit-record');
    Route::put('/projects/{project}/data-table/records/{record}', [ProjectDataTableController::class, 'updateRecord'])->name('projects.data-table.update-record');
    Route::delete('/projects/{project}/data-table/records/{record}', [ProjectDataTableController::class, 'deleteRecord'])->name('projects.data-table.delete-record');
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () { // esta ruta es para el perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit'); 
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update'); 
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::resource('users', UserController::class)
    ->middleware(['auth', 'role:admin'])
    ->names('users'); // Solo admin


// Mapa de actores (debe ir antes del resource para evitar conflicto de parámetros)
Route::get('/mapa-actores', [RecoleccionDeDatosController::class, 'mapaActores'])
    ->middleware(['auth', 'role:admin,profesor,estudiante'])
    ->name('mapa.actores');

// CRUD de trabajos/gestión de usuarios (solo admin)
Route::resource('trabajos', RecoleccionDeDatosController::class)
    ->middleware(['auth', 'role:admin']);

// Ruta para profesor (solo profesor)
Route::get('/profesor', [ProfesorController::class, 'index'])
    ->middleware(['auth', 'role:profesor'])
    ->name('profesor.index');

// Ruta para visitante (solo visitante)
Route::get('/visitante', [VisitanteController::class, 'index'])
    ->middleware(['auth', 'role:visitante'])
    ->name('visitante.index');

// CRUD de Categoría
Route::resource('categoria', CategoriaController::class)
    ->middleware(['auth', 'role:admin'])
    ->names('categoria')
    ->parameters(['categoria' => 'categoria']);

// CRUD de Rol Sector Agropecuario
Route::resource('rol', RolSectorAgropecuarioController::class)
    ->middleware(['auth', 'role:admin'])
    ->names('rol');


require __DIR__.'/auth.php';
