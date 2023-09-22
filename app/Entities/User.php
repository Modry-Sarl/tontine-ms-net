<?php

namespace App\Entities;

use BlitzPHP\Schild\Entities\User as SchildUser;
use BlitzPHP\Wolke\Casts\Attribute;
use BlitzPHP\Wolke\SoftDeletes;

class User extends SchildUser
{
    use SoftDeletes;

    protected array $fillable = [
        'username', 'email', 'pays', 'num_compte'
    ];

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


    protected function avatar(): Attribute
    {
        helper('assets');
        
        return Attribute::make(
            get: function(?string $value) {
                if (empty($value)) {
                    return img_url('avatars/' . (empty($this->sexe) ? 'default' : $this->sexe) . '.png');
                }

                return $value;
            },
        );
    }

    public function __get(string $key): mixed
    {
        if (!empty($v = parent::__get($key))) {
            return $v;
        }

        return $this->utilisateur->{$key};
    }
}
