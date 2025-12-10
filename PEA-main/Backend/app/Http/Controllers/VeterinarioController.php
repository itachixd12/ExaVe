<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Veterinario;
use App\Models\HorarioVeterinario;

class VeterinarioController extends Controller
{
    /**
     * Display a listing of veterinarians.
     */
    public function index()
    {
        $veterinarios = Veterinario::with('horarios')->get();
        return response()->json($veterinarios, 200);
    }

    /**
     * Store a newly created veterinarian.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:veterinarios',
            'telefono' => 'nullable|string',
            'especialidad' => 'nullable|string',
            'licencia' => 'required|string|unique:veterinarios',
            'horarios' => 'nullable|array',
            'horarios.*.dia_semana' => 'integer|between:0,6',
            'horarios.*.hora_inicio' => 'date_format:H:i',
            'horarios.*.hora_fin' => 'date_format:H:i',
        ]);

        $horarios = $validated['horarios'] ?? [];
        unset($validated['horarios']);

        $veterinario = Veterinario::create($validated);

        if (!empty($horarios)) {
            foreach ($horarios as $horario) {
                HorarioVeterinario::create([
                    'veterinario_id' => $veterinario->id,
                    'dia_semana' => $horario['dia_semana'],
                    'hora_inicio' => $horario['hora_inicio'],
                    'hora_fin' => $horario['hora_fin'],
                    'es_activo' => true,
                ]);
            }
        }

        $veterinario->load('horarios');

        return response()->json($veterinario, 201);
    }

    /**
     * Display the specified veterinarian.
     */
    public function show(string $id)
    {
        $veterinario = Veterinario::with('horarios')->findOrFail($id);
        return response()->json($veterinario, 200);
    }

    /**
     * Update the specified veterinarian.
     */
    public function update(Request $request, string $id)
    {
        $veterinario = Veterinario::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:veterinarios,email,' . $id,
            'telefono' => 'nullable|string',
            'especialidad' => 'nullable|string',
            'licencia' => 'sometimes|required|string|unique:veterinarios,licencia,' . $id,
        ]);

        $veterinario->update($validated);
        $veterinario->load('horarios');

        return response()->json($veterinario, 200);
    }

    /**
     * Remove the specified veterinarian.
     */
    public function destroy(string $id)
    {
        $veterinario = Veterinario::findOrFail($id);
        $veterinario->delete();

        return response()->json(null, 204);
    }
}
