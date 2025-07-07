<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Facture extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_commande',
        'numero_facture',
        'date_facture',
        'montant',
        'statut',
        'chemin_fichier',
    ];

    /**
     * Get the commande for the facture.
     */
    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class, 'id_commande');
    }
} 