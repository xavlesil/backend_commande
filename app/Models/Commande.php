<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'id_prestataire',
        'id_devis',
        'statut',
        'total_ht',
        'total_ttc',
        'date_livraison_prevue',
    ];

    /**
     * Get the prestataire for the commande.
     */
    public function prestataire(): BelongsTo
    {
        return $this->belongsTo(Prestataire::class, 'id_prestataire');
    }

    /**
     * Get the devis for the commande.
     */
    public function devis(): BelongsTo
    {
        return $this->belongsTo(Devis::class, 'id_devis');
    }

    /**
     * Get the lines for the commande.
     */
    public function lignes(): HasMany
    {
        return $this->hasMany(CommandeLigne::class, 'id_commande');
    }

    /**
     * Get the facture for the commande.
     */
    public function facture(): HasOne
    {
        return $this->hasOne(Facture::class, 'id_commande');
    }

    /**
     * Get the evaluation for the commande.
     */
    public function evaluation(): HasOne
    {
        return $this->hasOne(Evaluation::class, 'id_commande');
    }
} 