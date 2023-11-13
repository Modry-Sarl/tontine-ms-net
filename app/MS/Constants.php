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

    public const NBR_NIVEAU    = 18;
    public const TOTAL_FILLEUL = 262144;

    public const GAINS_NIVEAU = [
        //Argent
        1 => 10,
        2 => 20,
        3 => 40,
        4 => 60,
        5 => 80,
        6 => 400,
        // Or
        7 => 100,
        8 => 200,
        9 => 400,
        10 => 600,
        11 => 800,
        12 => 4000,
        // Diament
        13 => 1000,    
        14 => 2000,
        15 => 4000,
        16 => 6000,
        17 => 8000,
        18 => 40000,
    ];

    /** Rupture de niveau, c'est a dire qu'icim on ne gagne pas mais on passe a une classe suivante */
    public const BREAK_LEVEL = [6, 12];

    public static function nbrFilleulByNiveau(int $niveau): int 
    {
        return pow(2, $niveau);
    }

    /**
     * Nombre de filleul requis pour un terminé un pack
     * c'est a dire le nombre de filleul du dernier niveau du pack
     */
    public static function nbrFilleulByPack(string $pack): int
    {
        $iteration = static::getIteration($pack);

        return static::nbrFilleulByNiveau($iteration);
    }

    /**
     * Renvoie le nombre d'interation pour l'affichage de la progression en fonction du pack actuel de l'utilisateur.
     */
    public static function getIteration(string $pack): int 
    {
        $iteration = Constants::NBR_NIVEAU;
        $pack = strtolower($pack);

        if ($pack === 'argent') {
            $iteration -= 12;
        } elseif ($pack === 'or') {
            $iteration -= 6;
        }

        return $iteration;
    }
}
