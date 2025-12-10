<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mascota;
use Illuminate\Support\Facades\Auth;

class MascotaController extends Controller
{
    /**
     * Display all pets of authenticated user.
     */
    public function index()
    {
        $usuario = Auth::user();
        return response()->json($usuario->mascotas, 200);
    }

    /**
     * Store a newly created pet in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'especie' => 'required|string|max:100',
            'raza' => 'nullable|string|max:100',
            'edad' => 'nullable|integer|min:0',
            'peso' => 'nullable|numeric|min:0',
            'descripcion' => 'nullable|string',
        ]);

        $mascota = Auth::user()->mascotas()->create($validated);

        return response()->json($mascota, 201);
    }

    /**
     * Display the specified pet.
     */
    public function show(string $id)
    {
        $mascota = Mascota::where('user_id', Auth::id())
                          ->findOrFail($id);
        return response()->json($mascota, 200);
    }

    /**
     * Update the specified pet in storage.
     */
    public function update(Request $request, string $id)
    {
        $mascota = Mascota::where('user_id', Auth::id())
                          ->findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'especie' => 'sometimes|required|string|max:100',
            'raza' => 'nullable|string|max:100',
            'edad' => 'nullable|integer|min:0',
            'peso' => 'nullable|numeric|min:0',
            'descripcion' => 'nullable|string',
        ]);

        $mascota->update($validated);

        return response()->json($mascota, 200);
    }

    /**
     * Remove the specified pet from storage.
     */
    public function destroy(string $id)
    {
        $mascota = Mascota::where('user_id', Auth::id())
                          ->findOrFail($id);
        $mascota->delete();

        return response()->json(null, 204);
    }
}
