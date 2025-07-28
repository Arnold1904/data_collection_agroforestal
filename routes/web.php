
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

Route::resource('posts', PostController::class)->middleware(['auth', 'verified'])->names('posts');
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
    ->middleware(['auth'])
    ->name('mapa.actores');

// CRUD de trabajos (admin y estudiante)
Route::resource('trabajos', RecoleccionDeDatosController::class)
    ->middleware(['auth', 'role:admin,estudiante']);

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
