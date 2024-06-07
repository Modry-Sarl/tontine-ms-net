<?php

namespace App\MS;

use App\Entities\User;
use BlitzPHP\Wolke\Model;
use InvalidArgumentException;

class App
{

    public static function generateRef(string $class): string
    {
        if (!is_a($class, Model::class, true)) {
            throw new InvalidArgumentException('Classe d\'entite invalide. Impossible de generer une reference');
        }

        $total   = method_exists($class, 'withTrashed') ? $class::withTrashed()->count() : $class::count();
        $letters = range('A', 'Z');

        return 
            'MS' . str_pad(++$total, 3, "0", STR_PAD_LEFT) .
             $letters[array_rand($letters)] . $letters[array_rand($letters)] . 
             date('ym');
    }

    public static function generateIdUser(): string
    {
        return self::generateRef(User::class);
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
