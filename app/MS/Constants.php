<?php

namespace App\MS;

class Constants
{
    /**
     * Prix d'un inscription en dollar
     */
    public const PRIX_INSCRIPTION = 40;

    public const REDUCTIONS_INSCRIPTION = [
        'llAw2IkU' => 10
    ];

    /**
     * Valeur du dollar dans le system
     */
    public const DOLLAR_VALUE = 500;
    public const DOLLAR_VALUE_ENTREE = 475;

    public const NBR_NIVEAU = 5;

    public const GAINS_NIVEAU = [
        1 => 10,
        2 => 20,
        3 => 40,
        4 => 60,
        5 => 80,
        6 => 100,
        7 => 200,
        8 => 400,
        9 => 600,
        10 => 800,
        11 => 1000,
        12 => 2000,
        13 => 4000,
        14 => 6000,
        15 => 8000,
    ];

    public static function nbrFilleulByNiveau(int $niveau): int 
    {
        return pow(2, $niveau);
    }
}
