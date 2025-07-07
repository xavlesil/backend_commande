<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Competence extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'description'];

    /**
     * The prestataires that belong to the competence.
     */
    public function prestataires(): BelongsToMany
    {
        return $this->belongsToMany(Prestataire::class, 'competence_prestataire', 'id_competence', 'id_prestataire');
    }

    /**
     * The appel_offres that belong to the competence.
     */
    public function appelOffres(): BelongsToMany
    {
        return $this->belongsToMany(AppelOffre::class, 'appel_offre_competence', 'id_competence', 'id_appel_offre');
    }
} 