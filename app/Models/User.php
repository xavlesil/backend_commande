<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Sanctum\HasApiTokens; // Ajout de l'import
use Illuminate\Database\Eloquent\Casts\Attribute; // <-- Ajouter cet import

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable; // Ajout du trait HasApiTokens

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'profil_id',
        'profil_type',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['role'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the parent profil model (employe or prestataire).
     */
    public function profil(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'profil_type', 'profil_id');
    }

    /**
     * The roles that belong to the user.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'permission_user');
    }
    
    /**
     * Interact with the user's first role.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function role(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->roles->first(),
        );
    }

    /**
     * Get the tickets created by the user.
     */
    public function ticketsCrees()
    {
        return $this->hasMany(TicketBesoin::class, 'id_createur');
    }

    /**
     * Get the appels d'offres created by the user.
     */
    public function appelOffresCrees()
    {
        return $this->hasMany(AppelOffre::class, 'id_createur');
    }
} 