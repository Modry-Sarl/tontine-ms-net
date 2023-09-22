<?php

namespace App\Entities;

use App\Models\UserModel;
use BlitzPHP\Wolke\Model;

class Utilisateur extends Model
{
    public array|bool $timestamps = ["created_at"]; //only want to used created_at column
    const UPDATED_AT = null; //and updated by default null set

    protected array $fillable = [
        'user_id', 'ref', 'parrain',
        'main'
    ];

    /**
     * {@inheritDoc}
     *
     * @var array<string, string>
     */
    protected array $casts = [
        'main'      => 'boolean',
    ];

    private $_nbr_filleuls  = null;
    private $_list_filleuls = null;

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

    public function referer()
    {
        return $this->belongsTo(self::class, 'parrain', 'ref')->with('user');
    }

    public function getListFilleulsAttribute()
    {
        if (null === $this->_list_filleuls) {
            $this->_list_filleuls = model(UserModel::class)->listFilleuls($this);
        }

        return $this->_list_filleuls;
    }

    public function getNbrFilleulsAttribute()
    {
        if (null === $this->_nbr_filleuls) {
            $this->_nbr_filleuls = model(UserModel::class)->countFilleuls($this);
        }

        return $this->_nbr_filleuls;
    }
}
