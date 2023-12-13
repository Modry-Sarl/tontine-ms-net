<?php

namespace App\MS;

use App\Entities\User;

class App
{

    public static function generateIdUser(): string
    {
        $totalUser = User::withTrashed()->count();
        $letters   = range('A', 'Z');

        return 
            'MS' . str_pad(++$totalUser, 3, "0", STR_PAD_LEFT) .
             $letters[array_rand($letters)] . $letters[array_rand($letters)] . 
             date('ym');
    }

    public static function isRef(string $arg): bool
    {
        return preg_match('/^MS[0-9]{3}[A-Z]{2}[0-9][4]$/', $arg) != false;
    }

    /**
     * @param User $user
     */
    public static function getPrixInscription($user): int
    {
        return Constants::PRIX_INSCRIPTION - (Constants::REDUCTIONS_INSCRIPTION[$user->ref] ?? 0);
    }
}
