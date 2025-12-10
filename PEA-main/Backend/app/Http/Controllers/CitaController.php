<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cita;
use App\Models\Servicio;
use App\Models\Mascota;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CitaController extends Controller
{
    /**
     * Get all appointments (admin only).
     */
    public function index()
    {
        $citas = Cita::with('user', 'mascota', 'servicio', 'veterinario')
                     ->orderBy('fecha', 'desc')
                     ->orderBy('hora', 'desc')
                     ->get();
        return response()->json($citas, 200);
    }

    /**
     * Get user's appointments.
     */
    public function misCitas()
    {
        $usuario = Auth::user();
        $citas = $usuario->citas()->with('mascota', 'servicio', 'veterinario')
                         ->orderBy('fecha', 'desc')
                         ->get();
        return response()->json($citas, 200);
    }

    /**
     * Store a newly created appointment.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'mascota_id' => 'required|exists:mascotas,id',
            'servicio_id' => 'required|exists:servicios,id',
            'fecha' => 'required|date|after:today',
            'hora' => 'required|date_format:H:i',
            'observaciones' => 'nullable|string',
        ]);

        // Verificar que la mascota pertenece al usuario autenticado
        $mascota = Mascota::where('user_id', Auth::id())
                          ->findOrFail($validated['mascota_id']);

        // Verificar disponibilidad de horario (8am - 5pm)
        $hora = $validated['hora'];
        $horaInicio = '08:00';
        $horaFin = '17:00';

        if ($hora < $horaInicio || $hora >= $horaFin) {
            return response()->json([
                'message' => 'Las citas solo se pueden agendar entre las 8:00 AM y las 5:00 PM'
            ], 422);
        }

        // Verificar que no hay conflicto de horarios
        $citaExistente = Cita::where('fecha', $validated['fecha'])
                              ->where('hora', $validated['hora'])
                              ->where('estado', '!=', 'rechazada')
                              ->exists();

        if ($citaExistente) {
            return response()->json([
                'message' => 'Este horario no está disponible. Por favor, elige otro.'
            ], 422);
        }

        $validated['user_id'] = Auth::id();
        $validated['estado'] = 'pendiente';

        $cita = Cita::create($validated);
        $cita->load('mascota', 'servicio');

        return response()->json($cita, 201);
    }

    /**
     * Display the specified appointment.
     */
    public function show(string $id)
    {
        $cita = Cita::with('user', 'mascota', 'servicio', 'veterinario')
                    ->findOrFail($id);

        // Verificar que el usuario sea dueño de la cita o sea admin
        if ($cita->user_id !== Auth::id() && Auth::user()->rol !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        return response()->json($cita, 200);
    }

    /**
     * Accept appointment (admin only).
     */
    public function aceptar(Request $request, string $id)
    {
        $validated = $request->validate([
            'veterinario_id' => 'required|exists:veterinarios,id',
        ]);

        $cita = Cita::findOrFail($id);
        $cita->update([
            'estado' => 'confirmada',
            'veterinario_id' => $validated['veterinario_id'],
        ]);

        $cita->load('mascota', 'servicio', 'veterinario');

        return response()->json([
            'message' => 'Cita confirmada exitosamente',
            'cita' => $cita
        ], 200);
    }

    /**
     * Reject appointment (admin only).
     */
    public function rechazar(Request $request, string $id)
    {
        $validated = $request->validate([
            'razon_rechazo' => 'required|string',
        ]);

        $cita = Cita::findOrFail($id);
        $cita->update([
            'estado' => 'rechazada',
            'razon_rechazo' => $validated['razon_rechazo'],
        ]);

        $cita->load('mascota', 'servicio');

        return response()->json([
            'message' => 'Cita rechazada',
            'cita' => $cita
        ], 200);
    }

    /**
     * Mark appointment as completed (admin only).
     */
    public function completar(string $id)
    {
        $cita = Cita::findOrFail($id);
        $cita->update(['estado' => 'completada']);

        $cita->load('mascota', 'servicio', 'veterinario');

        return response()->json([
            'message' => 'Cita marcada como completada',
            'cita' => $cita
        ], 200);
    }

    /**
     * Get available hours for a specific date.
     */
    public function horariosDisponibles(Request $request)
    {
        $validated = $request->validate([
            'fecha' => 'required|date|after:today',
        ]);

        $fecha = $validated['fecha'];
        
        // Generar horas disponibles (cada 30 minutos)
        $horasDisponibles = [];
        $horaInicio = new Carbon("08:00");
        $horaFin = new Carbon("17:00");

        while ($horaInicio < $horaFin) {
            $hora = $horaInicio->format('H:i');
            
            // Verificar si hay cita en esa hora
            $citaExistente = Cita::where('fecha', $fecha)
                                 ->where('hora', $hora)
                                 ->where('estado', '!=', 'rechazada')
                                 ->exists();

            if (!$citaExistente) {
                $horasDisponibles[] = $hora;
            }

            $horaInicio->addMinutes(30);
        }

        return response()->json(['horas_disponibles' => $horasDisponibles], 200);
    }

    /**
     * Update appointment.
     */
    public function update(Request $request, string $id)
    {
        $cita = Cita::findOrFail($id);

        // Solo el dueño puede actualizar si la cita está pendiente
        if ($cita->user_id !== Auth::id() || $cita->estado !== 'pendiente') {
            return response()->json(['message' => 'No se puede actualizar esta cita'], 403);
        }

        $validated = $request->validate([
            'fecha' => 'sometimes|required|date|after:today',
            'hora' => 'sometimes|required|date_format:H:i',
            'observaciones' => 'nullable|string',
        ]);

        if (isset($validated['hora'])) {
            $hora = $validated['hora'];
            if ($hora < '08:00' || $hora >= '17:00') {
                return response()->json([
                    'message' => 'Las citas solo se pueden agendar entre las 8:00 AM y las 5:00 PM'
                ], 422);
            }
        }

        $cita->update($validated);
        $cita->load('mascota', 'servicio');

        return response()->json($cita, 200);
    }

    /**
     * Cancel appointment.
     */
    public function cancelar(string $id)
    {
        $cita = Cita::findOrFail($id);

        if ($cita->user_id !== Auth::id()) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        if ($cita->estado !== 'pendiente') {
            return response()->json(['message' => 'Solo se pueden cancelar citas pendientes'], 422);
        }

        $cita->delete();

        return response()->json(null, 204);
    }
}
