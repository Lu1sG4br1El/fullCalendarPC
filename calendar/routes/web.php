<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\fullCalendar;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('full-Calendar', [fullCalendar::class, 'index']);
Route::get('/eventos', [fullCalendar::class, 'getEvents']);
Route::get('/obtener-usuarios-materias', [fullCalendar::class, 'getUsuariosMaterias']);
Route::get('/obtener-usuarios', [fullCalendar::class, 'obtenerUsuarios']);
Route::get('/obtener-materias', [fullCalendar::class, 'obtenerMaterias']);
Route::post('/guardar-carga', [fullCalendar::class, 'guardarCarga']);
