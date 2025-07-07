<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommandeLigne extends Model
{
    use HasFactory;

    protected $table = 'commande_lignes';

    protected $fillable = [
        'id_commande',
        'designation',
        'quantite',
        'prix_unitaire_ht',
    ];

    /**
     * Get the commande that owns the line.
     */
    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class, 'id_commande');
    }
} 