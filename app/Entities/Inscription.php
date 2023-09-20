<?php

namespace App\Entities;

use BlitzPHP\Wolke\Model;

class Inscription extends Model
{
    protected array $fillable = [
        'user_id', 'inscriptor', 'nbr',
        'ip', 'ua',
    ];

    public function utilisateur() 
    {
        return $this->belongsTo(Utilisateur::class, 'user_id', 'user_id')->with('user');
    }
}
