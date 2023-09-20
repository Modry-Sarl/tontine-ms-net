<?php

namespace App\Entities;

use BlitzPHP\Schild\Entities\User as SchildUser;
use BlitzPHP\Wolke\SoftDeletes;

class User extends SchildUser
{
    use SoftDeletes;

    public function utilisateur() 
    {
        return $this->hasOne(Utilisateur::class);
    }

    public function comptes() 
    {
        return $this->hasMany(Utilisateur::class);
    }

    public function inscriptions() 
    {
        return $this->hasMany(Inscription::class, 'inscriptor');
    }

    public function __get(string $key): mixed
    {
        if (!empty($v = parent::__get($key))) {
            return $v;
        }

        return $this->utilisateur->{$key};
    }
}
