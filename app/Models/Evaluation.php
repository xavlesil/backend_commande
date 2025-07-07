<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_commande',
        'note',
        'commentaire',
    ];

    /**
     * Get the commande for the evaluation.
     */
    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class, 'id_commande');
    }
} 