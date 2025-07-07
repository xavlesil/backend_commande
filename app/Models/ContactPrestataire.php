<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactPrestataire extends Model
{
    use HasFactory;

    protected $table = 'contacts_prestataire';

    protected $fillable = [
        'id_prestataire',
        'nom',
        'prenom',
        'email',
        'telephone',
        'fonction',
        'is_default',
    ];

    /**
     * Get the prestataire that owns the contact.
     */
    public function prestataire(): BelongsTo
    {
        return $this->belongsTo(Prestataire::class, 'id_prestataire');
    }
} 