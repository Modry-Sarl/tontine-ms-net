<?php

namespace App\Entities;

use BlitzPHP\Wolke\Model;

class Transaction extends Model
{
    protected array $fillable = [
        'user_id', 'numero', 'ref',
        'montant', 'frais', 'type',
        'statut', 'message', 'operateur', 'reference', 'operator_transaction_id',
        'ip', 'ua', 'dt',
    ];

    public function scopeEntrees($query)
    {
        return $query->where('type', 'entree');
    }
    
    public function scopeSorties($query)
    {
        return $query->where('type', 'sortie');
    }

    public function scopeTransferts($query)
    {
        return $query->where('type', 'transfert');
    }
}
