<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppelOffre;
use App\Models\Prestataire;
use App\Models\TicketBesoin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AppelOffreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $appelsOffre = AppelOffre::with('tickets') // On charge les tickets liés
            ->latest() // Raccourci pour orderByDesc('created_at')
            ->paginate(15);

        return response()->json($appelsOffre);
    }

    /**
     * Calcule la distance entre deux points géographiques en kilomètres (formule de Haversine).
     *
     * @param float $latitudeFrom
     * @param float $longitudeFrom
     * @param float $latitudeTo
     * @param float $longitudeTo
     * @return float
     */
    protected function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo)
    {
        $earthRadius = 6371; // Rayon de la Terre en kilomètres

        // Convertir les degrés en radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $earthRadius * $angle;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::info('Début de la création d\'un appel d\'offres.');

        $validatedData = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'date_cloture' => 'required|date|after_or_equal:today',
            'ticket_ids' => 'nullable|array',
            'ticket_ids.*' => 'exists:tickets_besoin,id',
            'competence_ids' => 'nullable|array',
            'competence_ids.*' => 'exists:competences,id',
        ]);

        Log::info('Données validées:', $validatedData);

        try {
            $appelOffre = DB::transaction(function () use ($validatedData) {
                Log::info('Début de la transaction.');

                // 1. Créer l'appel d'offres
                $appelOffre = AppelOffre::create([
                    'titre' => $validatedData['titre'],
                    'description' => $validatedData['description'],
                    'date_cloture' => $validatedData['date_cloture'],
                    'statut' => 'EN_COURS', // ou 'OUVERT' selon votre convention
                    'id_gestionnaire' => Auth::id(),
                ]);

                Log::info('Appel d\'offres créé en base de données.', ['id' => $appelOffre->id]);

                // 2. Associer les tickets et mettre à jour leur statut
                if (!empty($validatedData['ticket_ids'])) {
                    Log::info('Association des tickets...', ['ids' => $validatedData['ticket_ids']]);
                    $appelOffre->tickets()->attach($validatedData['ticket_ids']);

                    TicketBesoin::whereIn('id', $validatedData['ticket_ids'])
                        ->update(['statut' => 'EN_APPEL_OFFRE']);
                    Log::info('Statut des tickets mis à jour.');
                }

                // 3. Associer les compétences
                if (!empty($validatedData['competence_ids'])) {
                    Log::info('Association des compétences...', ['ids' => $validatedData['competence_ids']]);
                    $appelOffre->competences()->attach($validatedData['competence_ids']);
                    Log::info('Compétences associées.');
                }


                Log::info('Fin de la transaction.');
                return $appelOffre;
            });

            return response()->json([
                'message' => 'Appel d\'offres créé avec succès.',
                'appel_offre' => $appelOffre->load('tickets', 'competences'),
            ], 201);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de l\'appel d\'offres:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Erreur lors de la création de l\'appel d\'offres.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(AppelOffre $appelOffre)
    {
        $appelOffre->load([
            'tickets',
            'competences',
            'prestataires',
            'soumissions.prestataire'
        ]);

        $camegLat = 6.1782;
        $camegLon = 1.2147;

        $appelOffre->prestataires->map(function ($prestataire) use ($camegLat, $camegLon) {
            if ($prestataire->latitude && $prestataire->longitude) {
                $prestataire->distance = $this->haversineGreatCircleDistance(
                    $camegLat,
                    $camegLon,
                    $prestataire->latitude,
                    $prestataire->longitude
                );
            } else {
                $prestataire->distance = null;
            }

            $prestataire->note_moyenne = rand(25, 50) / 10; // démo
            return $prestataire;
        });

        return $appelOffre;
    }


    /**
     * Suggère des prestataires pour un appel d'offres en fonction des compétences requises.
     */
    public function suggestPrestataires(AppelOffre $appelOffre)
    {
        // 1. Récupérer les IDs des compétences requises
        $competenceIds = $appelOffre->competences()->pluck('id');

        if ($competenceIds->isEmpty()) {
            return response()->json([]);
        }

        // 2. Trouver les prestataires qui possèdent au moins une de ces compétences
        $prestataires = Prestataire::whereHas('competences', function ($query) use ($competenceIds) {
            $query->whereIn('competences.id', $competenceIds);
        })
            ->with('competences')
            ->get();

        // 3. Calculer la distance et le score de pertinence
        $camegLat = 6.1782; // Coordonnées du siège CAMEG
        $camegLon = 1.2147;

        $prestataires = $prestataires->map(function ($prestataire) use ($camegLat, $camegLon, $competenceIds) {
            // Calculer la distance
            if ($prestataire->latitude && $prestataire->longitude) {
                $prestataire->distance_km = $this->haversineGreatCircleDistance($camegLat, $camegLon, $prestataire->latitude, $prestataire->longitude);
            } else {
                $prestataire->distance_km = 999; // Distance élevée si pas de coordonnées
            }

            // Calculer le score de pertinence (nombre de compétences correspondantes)
            $prestataireCompetenceIds = $prestataire->competences->pluck('id');
            $competencesCorrespondantes = $competenceIds->intersect($prestataireCompetenceIds);
            $prestataire->score_pertinence = $competencesCorrespondantes->count();
            $prestataire->pourcentage_competences = ($competencesCorrespondantes->count() / $competenceIds->count()) * 100;

            // TODO: Remplacer par un vrai calcul de moyenne des évaluations
            $prestataire->note_moyenne = rand(25, 50) / 10; // Note aléatoire entre 2.5 et 5 pour la démo

            return $prestataire;
        });

        // 4. Trier les prestataires par score de pertinence décroissant, puis par distance croissante
        $sortedPrestataires = $prestataires->sortBy([
            ['score_pertinence', 'desc'],
            ['distance_km', 'asc']
        ]);

        return response()->json($sortedPrestataires->values()->all());
    }
    /**
     * Invite des prestataires à un appel d'offres.
     *
     * @param Request $request
     * @param AppelOffre $appelOffre
     * @return \Illuminate\Http\JsonResponse
     */
    public function inviter(Request $request, AppelOffre $appelOffre)
    {
        $validatedData = $request->validate([
            'prestataire_ids' => 'required|array', // Changé de 'prestataires' à 'prestataire_ids'
            'prestataire_ids.*' => 'exists:prestataires,id',
        ]);

        try {
            DB::transaction(function () use ($appelOffre, $validatedData) {
                // Attacher les prestataires à l'appel d'offres
                $appelOffre->prestataires()->syncWithoutDetaching($validatedData['prestataire_ids']);

                // Mettre à jour le statut de l'appel d'offres
                $appelOffre->update(['statut' => 'EN_COURS']);
            });

            return response()->json([
                'message' => 'Prestataires invités avec succès et appel d\'offres mis à jour.'
            ], 200);

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'invitation des prestataires:', [
                'message' => $e->getMessage(),
                'appel_offre_id' => $appelOffre->id,
            ]);

            return response()->json([
                'message' => 'Une erreur est survenue lors de l\'invitation des prestataires.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function destroy(AppelOffre $appelOffre)
{
    try {
        $appelOffre->delete();
        return response()->json(['message' => 'Appel d\'offres supprimé.']);
    } catch (\Exception $e) {
        Log::error("Erreur suppression appel : " . $e->getMessage());
        return response()->json(['message' => 'Erreur serveur'], 500);
    }
}
    public function update(Request $request, AppelOffre $appelOffre)
    {
        $validatedData = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'date_cloture' => 'required|date|after_or_equal:today',
        ]);

        try {
            $appelOffre->update($validatedData);
            return response()->json(['message' => 'Appel d\'offres mis à jour avec succès.']);
        } catch (\Exception $e) {
            Log::error("Erreur mise à jour appel : " . $e->getMessage());
            return response()->json(['message' => 'Erreur serveur'], 500);
        }
    }
}