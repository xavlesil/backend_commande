<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Prestataire;
use App\Models\Prestation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class PrestataireController extends Controller
{
    /**
     * Affiche une liste paginée des prestataires.
     * Chaque prestataire est retourné avec son contact principal.
     */
    public function index(): JsonResponse
    {
        $prestataires = Prestataire::with('contactPrincipal')->latest()->paginate(10);
        
        // CORRECTION : On retourne la variable $prestataires, pas la constante Prestataires.
        return response()->json($prestataires);
    }

    /**
     * Enregistre un nouveau prestataire dans la base de données.
     * Les règles de validation sont alignées sur le schéma de la BDD.
     */
    public function store(Request $request): JsonResponse
    {
        // CORRECTION : Les clés de validation correspondent maintenant aux colonnes de la BDD.
        $validatedData = $request->validate([
            'nom_commercial' => 'required|string|max:255',
            'nom_officiel' => 'required|string|max:255',
            'nif' => 'required|string|max:255|unique:prestataires,nif',
            'email_generique' => 'nullable|email|max:255',
            'telephone_standard' => 'nullable|string|max:20',
            'adresse_siege' => 'nullable|string',
            'site_web' => 'nullable|url',
            'statut' => 'required|in:ACTIF,INACTIF,EN_VALIDATION,REFUSE', // Assurez-vous que ces valeurs correspondent à votre ENUM
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $prestataire = Prestataire::create($validatedData);

        return response()->json($prestataire, 201);
    }

    /**
     * Affiche un prestataire spécifique avec ses relations.
     * AMÉLIORATION : On charge les contacts pour une vue de détail complète.
     */
    public function show(Prestataire $prestataire): JsonResponse
    {
        // On charge les relations 'contacts' et 'competences' pour enrichir la réponse.
        $prestataire->load('contacts', 'competences');
        
        return response()->json($prestataire);
    }

    /**
     * Met à jour un prestataire existant.
     */
    public function update(Request $request, Prestataire $prestataire): JsonResponse
    {
        // CORRECTION : Les clés de validation et la règle 'unique' sont corrigées.
        $validatedData = $request->validate([
            'nom_commercial' => 'required|string|max:255',
            'nom_officiel' => 'required|string|max:255',
            'nif' => 'required|string|max:255|unique:prestataires,nif,' . $prestataire->id,
            'email_generique' => 'nullable|email|max:255',
            'telephone_standard' => 'nullable|string|max:20',
            'adresse_siege' => 'nullable|string',
            'site_web' => 'nullable|url',
            'statut' => 'required|in:ACTIF,INACTIF,EN_VALIDATION,REFUSE',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $prestataire->update($validatedData);

        return response()->json($prestataire);
    }

    /**
     * Supprime un prestataire de la base de données.
     */
    public function destroy(Prestataire $prestataire): Response
    {
        $prestataire->delete();
        
        return response()->noContent();
    }
    /**
     * Retourne les prestations du prestataire authentifié.
     */
    public function mesPrestations(Request $request)
    {
        $user = \Auth::user();

        // Récupère les prestations liées à ce prestataire
        $query = \App\Models\Prestataire::with(['ticket', 'appelOffre'])
            ->where('id_prestataire', $user->id);

        // Filtres
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('description', 'like', '%' . $request->search . '%')
                  ->orWhereHas('ticket', function($q2) use ($request) {
                      $q2->where('titre', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $prestations = $query->latest()->get();

        return response()->json($prestations);
    }
}
