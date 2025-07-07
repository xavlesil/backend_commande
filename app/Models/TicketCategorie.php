<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketCategorie extends Model
{
    use HasFactory;

    protected $table = 'ticket_categories';

    protected $fillable = ['nom'];

    /**
     * Get the tickets for the category.
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(TicketBesoin::class, 'id_categorie');
    }
} 