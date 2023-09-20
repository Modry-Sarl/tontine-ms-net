<?php

namespace App\Entities;

use BlitzPHP\Wolke\Casts\Attribute;
use BlitzPHP\Wolke\Model;

class Utilisateur extends Model
{
    public array|bool $timestamps = ["created_at"]; //only want to used created_at column
    const UPDATED_AT = null; //and updated by default null set

    protected array $fillable = [
        'user_id', 'ref', 'parrain',
        'tel', 'pays', 'main'
    ];

    /**
     * {@inheritDoc}
     *
     * @var array<string, string>
     */
    protected array $casts = [
        'main'      => 'boolean',
    ];

    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }

    public function niveaux()
    {
        return $this->hasMany(Niveau::class, 'user_id');
    }

    public function gains()
    {
        return $this->hasMany(Gain::class, 'user_id');
    }
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function filleuls()
    {
        return $this->hasMany(self::class, 'parrain', 'ref');
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
}
