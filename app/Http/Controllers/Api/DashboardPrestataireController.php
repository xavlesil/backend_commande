<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardPrestataireController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Assurer qu’il est bien un prestataire
        if (!$user || $user->role !== 'prestataire') {
            return response()->json(['error' => 'Accès refusé.'], 403);
        }

        try {
            $prestataireId = $user->prestataire->id;

            // 1. Nombre de prestations en cours
            $enCours = DB::table('prestations')
                ->where('prestataire_id', $prestataireId)
                ->where('statut', 'EN_COURS')
                ->count();

            // 2. Nombre de prestations terminées
            $terminees = DB::table('prestations')
                ->where('prestataire_id', $prestataireId)
                ->where('statut', 'TERMINEE')
                ->count();

            // 3. Revenus du mois
            $revenusDuMois = DB::table('prestations')
                ->where('prestataire_id', $prestataireId)
                ->where('statut', 'TERMINEE')
                ->whereMonth('updated_at', Carbon::now()->month)
                ->sum('montant_total');

            // 4. 5 dernières prestations
            $recentes = DB::table('prestations')
                ->where('prestataire_id', $prestataireId)
                ->orderByDesc('updated_at')
                ->limit(5)
                ->get();

            return response()->json([
                'kpis' => [
                    'en_cours' => $enCours,
                    'terminees' => $terminees,
                    'revenus_mois' => $revenusDuMois,
                ],
                'recentes' => $recentes
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur serveur : ' . $e->getMessage()], 500);
        }
    }
}
