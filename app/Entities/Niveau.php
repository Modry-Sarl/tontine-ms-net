<?php

namespace App\Entities;

use BlitzPHP\Wolke\Model;

class Niveau extends Model
{
    public string $table = 'niveaux';

    protected array $fillable = [
        'user_id', 'niveau'
    ];

    public function user() 
    {
        return $this->belongsTo(Utilisateur::class, 'user_id');
    }
}
