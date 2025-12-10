<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servicio;
use Illuminate\Support\Facades\DB;

class ServicioController extends Controller
{
    /**
     * Display a listing of services.
     */
    public function index()
    {
        return Servicio::all();
    }

    /**
     * Display service by type.
     */
    public function porTipo($tipo)
    {
        $servicios = Servicio::where('tipo', $tipo)->get();
        return response()->json($servicios, 200);
    }

    /**
     * Store a newly created service in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'tipo' => 'required|in:consulta,vacuna,bano,cirugia',
            'duracion' => 'required|integer|min:15',
            'imagen' => 'nullable|string',
        ]);

        $validated['slug'] = \Illuminate\Support\Str::slug($validated['nombre']);

        $servicio = Servicio::create($validated);

        return response()->json($servicio, 201);
    }

    /**
     * Display the specified service.
     */
    public function show(string $id)
    {
        $servicio = Servicio::findOrFail($id);
        return response()->json($servicio, 200);
    }

    /**
     * Update the specified service in storage.
     */
    public function update(Request $request, string $id)
    {
        $servicio = Servicio::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'descripcion' => 'sometimes|required|string',
            'precio' => 'sometimes|required|numeric|min:0',
            'tipo' => 'sometimes|required|in:consulta,vacuna,bano,cirugia',
            'duracion' => 'sometimes|required|integer|min:15',
            'imagen' => 'nullable|string',
        ]);

        if (isset($validated['nombre'])) {
            $validated['slug'] = \Illuminate\Support\Str::slug($validated['nombre']);
        }

        $servicio->update($validated);

        return response()->json($servicio, 200);
    }

    /**
     * Remove the specified service from storage.
     */
    public function destroy(string $id)
    {
        $servicio = Servicio::findOrFail($id);
        $servicio->delete();

        return response()->json(null, 204);
    }
}
