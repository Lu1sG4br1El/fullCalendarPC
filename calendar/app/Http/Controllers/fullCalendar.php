<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carga;
use App\Models\Usuario;
use App\Models\Materia;
use App\Models\Carrera;


class fullCalendar extends Controller
{
    public function index(Request $request)
    {
        return view('full-Calendar');
    }

    public function getEvents(Request $request)
    {
        $start = $request->start;
        $end = $request->end;

        $cargas = Carga::with('materia.carrera', 'usuario')->whereBetween('inicio', [$start, $end])->get();

        $formattedEvents = [];

        foreach ($cargas as $carga) {
            $formattedEvents[] = [
                'title' => $carga->materia->nameMat,
                'start' => $carga->inicio,
                'end' => $carga->fin,
                'usuario' => $carga->usuario->nombre,
                'carrera' => $carga->materia->carrera->nameCarr,
            ];
        }

        return response()->json($formattedEvents);
    }


    public function getUsuariosMaterias()
    {
        $materias = Materia::with('carrera')->get();

        return response()->json([
            'materias' => $materias,
        ]);
    }

    public function obtenerUsuarios()
    {
        $usuarios = Usuario::all();

        return response()->json($usuarios);
    }

    public function obtenerMaterias()
    {
        $materias = Materia::with('carrera')->get();

        return response()->json($materias);
    }


    public function guardarCarga(Request $request)
    {
        // Obtener los datos del formulario
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $usuarioId = $request->usuarioId;
        $materiaId = $request->materiaId;

        // Guardar los datos en la base de datos
        $carga = new Carga();
        $carga->inicio = $startDate;
        $carga->fin = $endDate;
        $carga->usuario_id = $usuarioId;
        $carga->materia_id = $materiaId;
        $carga->save();

        // Respuesta de éxito
        return response()->json([
            'success' => true,
            'message' => 'Carga guardada correctamente.'
        ]);
    }

}
