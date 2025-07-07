<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Prestataire;
use Illuminate\Http\Request;

class PrestataireController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Prestataire::latest()->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nom_commercial' => 'required|string|max:255',
            'raison_sociale' => 'required|string|max:255',
            'email' => 'required|email|unique:prestataires,email',
            'telephone' => 'required|string|max:20',
            'adresse' => 'nullable|string',
            'site_web' => 'nullable|url',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $prestataire = Prestataire::create($validatedData);

        return response()->json($prestataire, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Prestataire $prestataire)
    {
        return $prestataire;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Prestataire $prestataire)
    {
        $validatedData = $request->validate([
            'nom_commercial' => 'required|string|max:255',
            'raison_sociale' => 'required|string|max:255',
            'email' => 'required|email|unique:prestataires,email,' . $prestataire->id,
            'telephone' => 'required|string|max:20',
            'adresse' => 'nullable|string',
            'site_web' => 'nullable|url',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $prestataire->update($validatedData);

        return response()->json($prestataire);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Prestataire $prestataire)
    {
        $prestataire->delete();
        return response()->noContent();
    }
}
