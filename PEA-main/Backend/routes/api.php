<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\MascotaController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\VeterinarioController;

//rutas publicas
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Servicios veterinarios (públicos)
Route::get('/servicios', [ServicioController::class, 'index']);
Route::get('/servicios/{id}', [ServicioController::class, 'show']);
Route::get('/servicios/tipo/{tipo}', [ServicioController::class, 'porTipo']);

// Veterinarios (públicos)
Route::get('/veterinarios', [VeterinarioController::class, 'index']);
Route::get('/veterinarios/{id}', [VeterinarioController::class, 'show']);

// Horarios disponibles (público)
Route::get('/citas/horarios-disponibles', [CitaController::class, 'horariosDisponibles']);

//productos (legacy - mantener para compatibilidad)
Route::get('/productos', [ProductoController::class, 'index']);
Route::get('/productos/{id}', [ProductoController::class, 'show']);
Route::get('/categoria', [ProductoController::class, 'categoria']);

//rutas protegidas
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Mascotas del usuario
    Route::get('/mascotas', [MascotaController::class, 'index']);
    Route::post('/mascotas', [MascotaController::class, 'store']);
    Route::get('/mascotas/{id}', [MascotaController::class, 'show']);
    Route::put('/mascotas/{id}', [MascotaController::class, 'update']);
    Route::delete('/mascotas/{id}', [MascotaController::class, 'destroy']);

    // Citas del usuario
    Route::post('/citas', [CitaController::class, 'store']);
    Route::get('/citas/mis', [CitaController::class, 'misCitas']);
    Route::get('/citas/{id}', [CitaController::class, 'show']);
    Route::put('/citas/{id}', [CitaController::class, 'update']);
    Route::delete('/citas/{id}', [CitaController::class, 'cancelar']);

    // Admin: Gestión de citas
    Route::middleware('IsAdmin:admin')->group(function () {
        Route::get('/citas', [CitaController::class, 'index']);
        Route::post('/citas/{id}/aceptar', [CitaController::class, 'aceptar']);
        Route::post('/citas/{id}/rechazar', [CitaController::class, 'rechazar']);
        Route::post('/citas/{id}/completar', [CitaController::class, 'completar']);

        // Gestión de servicios
        Route::post('/servicios', [ServicioController::class, 'store']);
        Route::put('/servicios/{id}', [ServicioController::class, 'update']);
        Route::delete('/servicios/{id}', [ServicioController::class, 'destroy']);

        // Gestión de veterinarios
        Route::post('/veterinarios', [VeterinarioController::class, 'store']);
        Route::put('/veterinarios/{id}', [VeterinarioController::class, 'update']);
        Route::delete('/veterinarios/{id}', [VeterinarioController::class, 'destroy']);
    });

    // Legacy routes - mantener para compatibilidad
    Route::post('/categorias', [ProductoController::class, 'storeCategoria'])->middleware('IsAdmin:admin');
    Route::post('/productos', [ProductoController::class, 'store'])->middleware('IsAdmin:admin');
    Route::put('/productos/{id}', [ProductoController::class, 'update'])->middleware('IsAdmin:admin');
    Route::delete('/productos/{id}', [ProductoController::class, 'destroy'])->middleware('IsAdmin:admin');

    // Carrito
    Route::post('/carrito/agregar', [ProductoController::class, 'agregarAlCarrito']);
    Route::get('/carrito', [ProductoController::class, 'verCarrito']);
    Route::post('/carrito/actualizar', [ProductoController::class, 'actualizarCarrito']);
    Route::post('/carrito/eliminar', [ProductoController::class, 'eliminarDelCarrito']);

    // Pedidos
    Route::post('/pedidos', [ProductoController::class, 'crearPedido']);
    Route::get('/pedidos', [ProductoController::class, 'listarPedidos'])->middleware('IsAdmin:admin');
    Route::post('/pedidos/{id}/aceptar', [ProductoController::class, 'aceptarPedido'])->middleware('IsAdmin:admin');
    Route::get('/pedidos/mis', [ProductoController::class, 'misPedidos']);
    Route::delete('/pedidos/{id}', [ProductoController::class, 'eliminarPedido']);

    // Dirección del usuario autenticado
    Route::get('/usuario/direccion', [ProductoController::class, 'direccionUsuario']);
});

