<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Soumission extends Model
{
    protected $fillable = [
        'appel_offre_id',
        'prestataire_id',
        'montant_propose',
        'description',
        'soumis_le',
    ];

    public function appelOffre()
    {
        return $this->belongsTo(AppelOffre::class);
    }

    public function prestataire()
    {
        return $this->belongsTo(Prestataire::class);
    }
}
