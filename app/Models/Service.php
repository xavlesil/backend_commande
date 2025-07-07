<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'chef_de_service',
    ];

    /**
     * Get the employes for the service.
     */
    public function employes(): HasMany
    {
        return $this->hasMany(Employe::class, 'id_service');
    }

    /**
     * Get the manager of the service.
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(Employe::class, 'chef_de_service');
    }
} 