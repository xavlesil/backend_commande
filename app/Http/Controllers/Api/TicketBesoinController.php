<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TicketBesoin;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreTicketBesoinRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class TicketBesoinController extends Controller
{
    /**
     * Affiche la liste paginée des tickets de l'utilisateur connecté, avec recherche et filtres.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = TicketBesoin::query();

        // Si le rôle est "gestionnaire", il voit tous les tickets.
        // Sinon, l'employé ne voit que ses propres tickets.
        if ($request->input('role') !== 'gestionnaire') {
            $query->where('id_createur', $user->id);
        }

        // Recherche par titre (le frontend envoie 'search' maintenant)
        if ($request->filled('search')) {
            $query->where('titre', 'like', '%' . $request->search . '%');
        }
        // Filtre par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        // Filtre par priorité
        if ($request->filled('priorite')) {
            $query->where('priorite', $request->priorite);
        }
        // Filtre par catégorie
        if ($request->filled('id_categorie')) {
            $query->where('id_categorie', $request->id_categorie);
        }

        $tickets = $query->with(['categorie', 'createur.profil'])->orderByDesc('created_at')->paginate(10);

        return response()->json($tickets);
    }

    /**
     * Crée un nouveau ticket de besoin.
     */
    public function store(StoreTicketBesoinRequest $request)
    {
        $user = Auth::user();

        // Génération de la référence unique (ex: TCK-20240716-0001)
        $date = now()->format('Ymd');
        $lastId = TicketBesoin::max('id') ?? 0;
        $reference = 'TCK-' . $date . '-' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);

        // Upload de la pièce jointe si présente
        $pieceJointePath = null;
        if ($request->hasFile('piece_jointe')) {
            $pieceJointePath = $request->file('piece_jointe')->store('tickets', 'public');
        }

        $ticket = TicketBesoin::create([
            'reference' => $reference,
            'titre' => $request->titre,
            'description' => $request->description,
            'piece_jointe' => $pieceJointePath,
            'statut' => 'NOUVEAU',
            'id_createur' => $user->id,
            'id_categorie' => $request->id_categorie,
            'priorite' => $request->priorite,
        ]);

        $ticket->load('categorie');

        return response()->json([
            'message' => 'Ticket créé avec succès',
            'ticket' => $ticket
        ], 201);
    }
    public function show(TicketBesoin $ticketBesoin)
    {
        return $ticketBesoin->load(['categorie', 'createur.profil']);
    }
}
