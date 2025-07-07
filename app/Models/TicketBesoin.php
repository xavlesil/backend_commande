<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TicketBesoin extends Model
{
    use HasFactory;

    protected $table = 'tickets_besoin';

    protected $fillable = [
        'reference',
        'titre',
        'description',
        'piece_jointe',
        'statut',
        'id_createur',
        'id_categorie',
        'priorite',
        'date_resolution',
    ];

    /**
     * Get the user who created the ticket.
     */
    public function createur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_createur');
    }

    /**
     * Get the category for the ticket.
     */
    public function categorie(): BelongsTo
    {
        return $this->belongsTo(TicketCategorie::class, 'id_categorie');
    }

    /**
     * The appel_offres that belong to the ticket.
     */
    public function appelOffres(): BelongsToMany
    {
        return $this->belongsToMany(AppelOffre::class, 'appel_offre_ticket', 'id_ticket_besoin', 'id_appel_offre');
    }
} 