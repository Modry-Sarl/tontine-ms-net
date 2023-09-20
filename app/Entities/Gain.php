<?php

namespace App\Entities;

use BlitzPHP\Wolke\Model;

class Gain extends Model
{
    protected array $fillable = [
        'user_id', 'niveau',
        'montant_recu', 'montant_reel',
    ];

    public function user() 
    {
        return $this->belongsTo(Utilisateur::class, 'user_id');
    }
}
