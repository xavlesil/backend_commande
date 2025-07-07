<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Prestataire extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom_commercial',
        'raison_sociale',
        'nif',
        'adresse',
        'latitude',
        'longitude',
    ];

    /**
     * Get the prestataire's user account.
     */
    public function user(): MorphOne
    {
        return $this->morphOne(User::class, 'profil');
    }

    /**
     * Get the contacts for the prestataire.
     */
    public function contacts(): HasMany
    {
        return $this->hasMany(ContactPrestataire::class, 'id_prestataire');
    }

    /**
     * The competences that belong to the prestataire.
     */
    public function competences(): BelongsToMany
    {
        return $this->belongsToMany(Competence::class, 'competence_prestataire', 'id_prestataire', 'id_competence');
    }

    /**
     * Get the devis for the prestataire.
     */
    public function devis(): HasMany
    {
        return $this->hasMany(Devis::class, 'id_prestataire');
    }

    /**
     * The appel_offres that belong to the prestataire.
     */
    public function appelOffres(): BelongsToMany
    {
        return $this->belongsToMany(AppelOffre::class, 'appel_offre_prestataire', 'id_prestataire', 'id_appel_offre');
    }
} 