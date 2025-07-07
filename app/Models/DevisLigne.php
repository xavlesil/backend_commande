<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DevisLigne extends Model
{
    use HasFactory;

    protected $table = 'devis_lignes';

    protected $fillable = [
        'id_devis',
        'designation',
        'quantite',
        'prix_unitaire_ht',
    ];

    /**
     * Get the devis that owns the line.
     */
    public function devis(): BelongsTo
    {
        return $this->belongsTo(Devis::class, 'id_devis');
    }
} 