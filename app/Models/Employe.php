<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employe extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenom',
        'id_user',
        'id_service',
    ];

    /**
     * Get the employe's user account.
     */
    public function user(): MorphOne
    {
        return $this->morphOne(User::class, 'profil');
    }

    /**
     * Get the service that the employe belongs to.
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'id_service');
    }
} 