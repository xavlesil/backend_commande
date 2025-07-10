<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AppelOffre extends Model
{
    use HasFactory;

    protected $table = 'appel_offres';

    protected $fillable = [
        'titre',
        'description',
        'date_cloture',
        'statut',
        'id_gestionnaire',
    ];

    /**
     * Get the user who created the appel d'offre.
     */
    public function gestionnaire(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_gestionnaire');
    }

    /**
     * The prestataires that belong to the appel d'offre.
     */
    public function prestataires(): BelongsToMany
    {
        return $this->belongsToMany(Prestataire::class, 'appel_offre_prestataire', 'id_appel_offre', 'id_prestataire');
    }

    /**
     * The competences that belong to the appel d'offre.
     */
    public function competences(): BelongsToMany
    {
        return $this->belongsToMany(Competence::class, 'appel_offre_competence', 'id_appel_offre', 'id_competence');
    }

    /**
     * The tickets that belong to the appel d'offre.
     */
    public function tickets(): BelongsToMany
    {
        return $this->belongsToMany(TicketBesoin::class, 'appel_offre_ticket', 'id_appel_offre', 'id_ticket_besoin');
    }

    /**
     * Get the devis for the appel d'offre.
     */
    public function devis(): HasMany
    {
        return $this->hasMany(Devis::class, 'id_appel_offre');
    }
    public function soumissions()
{
    return $this->hasMany(Soumission::class);
}

} 