<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TicketBesoin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\AppelOffre;
use App\Models\Commande;
use App\Models\Devis;
use App\Models\TicketCategorie;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Récupère les statistiques pour le dashboard de l'employé.
     */
    public function employeStats()
    {
        $user = Auth::user();

        // 1. Calculer les KPIs (nombre de tickets par statut)
        $stats = TicketBesoin::where('id_createur', $user->id)
            ->select('statut', DB::raw('count(*) as total'))
            ->groupBy('statut')
            ->pluck('total', 'statut');

        // 2. Récupérer les 5 derniers tickets
        $recentTickets = TicketBesoin::where('id_createur', $user->id)
            ->with('categorie')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return response()->json([
            'kpis' => [
                'nouveau' => $stats->get('NOUVEAU', 0),
                'en_cours' => $stats->get('EN COURS', 0),
                'resolu' => $stats->get('RESOLU', 0),
                'clos' => $stats->get('CLOS', 0),
            ],
            'recent_tickets' => $recentTickets
        ]);
    }

    /**
     * Récupère les statistiques pour le dashboard du gestionnaire (Chef SG).
     */
 public function gestionnaireStats()
{
    try {
        $ticketsOuverts = TicketBesoin::whereIn('statut', ['NOUVEAU', 'EN_ATTENTE'])->count();
        $ticketsResolus = TicketBesoin::where('statut', 'RESOLU')->count();
        $ticketsClotures = TicketBesoin::where('statut', 'CLOS')->count();
        $ticketsAppelOffre = TicketBesoin::where('statut', 'APPEL_OFFRE')->count();
        $commandesEnCours = Commande::where('statut', 'EN_COURS')->count();
        $depensesDuMois = Commande::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_ttc');

        return response()->json([
            'kpis' => [
                'tickets_ouverts' => $ticketsOuverts,
                'tickets_resolus' => $ticketsResolus,
                'tickets_clotures' => $ticketsClotures,
                'tickets_appel_offre' => $ticketsAppelOffre,
                'commandes_en_cours' => $commandesEnCours,
                'depenses_du_mois' => $depensesDuMois,
            ],
            'graphiques' => [
                'tickets_par_categorie' => [
                    'labels' => ['NOUVEAU', 'EN_ATTENTE', 'EN_COURS', 'CLOS', 'ATTRIBUE'],
                    'data' => [
                        TicketBesoin::where('statut', 'NOUVEAU')->count(),
                        TicketBesoin::where('statut', 'EN_ATTENTE')->count(),
                        TicketBesoin::where('statut', 'EN_COURS')->count(),
                        TicketBesoin::where('statut', 'CLOS')->count(),
                        TicketBesoin::where('statut', 'ATTRIBUE')->count(),
                    ],
                ]
            ],
            'actions_requises' => [],
            'flux_activite' => [],
        ]);
    } catch (\Exception $e) {
        Log::error('Erreur dans gestionnaireStats: ' . $e->getMessage());
        return response()->json(['error' => 'Erreur serveur.'], 500);
    }
}
}