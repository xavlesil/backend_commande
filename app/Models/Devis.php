<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Devis extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_appel_offre',
        'id_prestataire',
        'reference',
        'statut',
        'total_ht',
        'total_ttc',
        'notes',
    ];

    /**
     * Get the appel d'offre for the devis.
     */
    public function appelOffre(): BelongsTo
    {
        return $this->belongsTo(AppelOffre::class, 'id_appel_offre');
    }

    /**
     * Get the prestataire for the devis.
     */
    public function prestataire(): BelongsTo
    {
        return $this->belongsTo(Prestataire::class, 'id_prestataire');
    }

    /**
     * Get the lines for the devis.
     */
    public function lignes(): HasMany
    {
        return $this->hasMany(DevisLigne::class, 'id_devis');
    }
} 