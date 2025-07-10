<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Prestataire extends Model
{
    use HasFactory;

    /**
     * Les attributs qui peuvent être assignés en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom_commercial',
        'nom_officiel', // Ce champ est dans votre schéma, il devrait être ici.
        'nif',
        'adresse_siege', // Le nom complet est plus clair, correspond au schéma.
        'telephone_standard', // Le nom complet est plus clair.
        'email_generique', // Le nom complet est plus clair.
        'site_web',
        'statut',
        'latitude',
        'longitude',
    ];

    /**
     * Obtenir le compte utilisateur associé à ce prestataire (relation polymorphique).
     */
    public function user(): MorphOne
    {
        return $this->morphOne(User::class, 'profil');
    }

    /**
     * Obtenir tous les contacts associés à ce prestataire.
     */
    public function contacts(): HasMany
    {
        // Votre clé étrangère 'id_prestataire' n'est pas standard. Laravel s'attendrait à 'prestataire_id'.
        // Le fait de la spécifier ici est donc la bonne chose à faire.
        return $this->hasMany(ContactPrestataire::class, 'id_prestataire');
    }

    /**
     * Obtenir le contact principal du prestataire.
     * C'est une relation filtrée pour ne retourner qu'un seul contact.
     */
    public function contactPrincipal(): HasOne
    {
        return $this->hasOne(ContactPrestataire::class, 'id_prestataire')->where('est_contact_principal', true);
    }

    /**
     * Obtenir tous les devis soumis par ce prestataire.
     */
    public function devis(): HasMany
    {
        return $this->hasMany(Devis::class, 'id_prestataire');
    }
    
    /**
     * Obtenir toutes les soumissions faites par ce prestataire.
     * Note: Assurez-vous que la table 'soumissions' a bien une colonne 'id_prestataire'.
     */
    public function soumissions(): HasMany
    {
        return $this->hasMany(Soumission::class, 'id_prestataire');
    }

    /**
     * Les compétences que possède ce prestataire.
     */
    public function competences(): BelongsToMany
    {
        return $this->belongsToMany(Competence::class, 'competence_prestataire', 'id_prestataire', 'id_competence');
    }

    /**
     * Les appels d'offres auxquels ce prestataire a été invité.
     */
    public function appelOffres(): BelongsToMany
    {
        return $this->belongsToMany(AppelOffre::class, 'appel_offre_prestataire', 'id_prestataire', 'id_appel_offre');
    }
}