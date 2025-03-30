<?php

namespace App\Entities;

use App\MS\App;
use BlitzPHP\Wolke\Model;

class Retrait extends Model
{
    protected array $fillable = [
        'ref', 'user_id', 'tel', 
        'compte', 'montant', 'meta', 
        'statut', 'process_at', 'process_by', 'rejected_reason',
    ];
    protected array $casts = [
        'meta' => 'json',
    ];

    public function user() 
    {
        return $this->belongsTo(Utilisateur::class)->with('user');
    }

    public static function generateReference(Utilisateur $user, array $data): string
    {
        $ref = App::generateRef(self::class);
        self::create([
            'ref'     => $ref,
            'user_id' => $user->id,
            'tel'     => simple_tel($data['tel']),
            'montant' => (float) $data['montant'],
            'compte'  => $data['compte'],
            'meta'    => (array) ($data['meta'] ?? []),
        ]);

        return $ref;
    }
}