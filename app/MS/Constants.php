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

    public const NBR_NIVEAU    = 5;
    public const TOTAL_FILLEUL = 32;

    public const GAINS_NIVEAU = [
        //Argent
        1 => 10,
        2 => 20,
        3 => 40,
        4 => 60,
        5 => 400
    ];

    /** Rupture de niveau, c'est a dire qu'icim on ne gagne pas mais on passe a une classe suivante */
    public const BREAK_LEVEL = [5];

    /** Compte utilisé pour les retrait en masse */
    public const MASSIVE_WITHDRAWAL_ACCOUNT = 'VC125JC2503';

    /** Compte dont la recharge permettra d'incrementer le solde du compte des retraits en masse */
    public const MASSIVE_WITHDRAWAL_REFUND_ACCOUNT = 'MS001QH2309';

    /** Montant de la recharge pour les retraits en masse */
    /** Si on ne recharge pas exactement ce montant, le hook ne sera pas appliquer */
    public const MASSIVE_WITHDRAWAL_REFUND_AMOUNT = 60;

    /** Montant a soustraire du compte de recharge pour aller incrementer le compte de retraits en masse */
    public const MASSIVE_WITHDRAWAL_SUBSTRACTED_AMOUNT = self::MASSIVE_WITHDRAWAL_REFUND_AMOUNT - self::PRIX_INSCRIPTION;

    /** Montant a crediter au compte des retrait en masse lors de la recharge du compte MASSIVE_WITHDRAWAL_REFUND_ACCOUNT */
    public const MASSIVE_WITHDRAWAL_ADDED_AMOUNT = self::MASSIVE_WITHDRAWAL_SUBSTRACTED_AMOUNT + 8;

    public static function nbrFilleulByNiveau(int $niveau): int 
    {
        return pow(2, $niveau);
    }

    /**
     * Nombre de filleul requis pour un terminé un pack
     */
    public static function nbrFilleulByPack(string $pack): int
    {
        $iteration = static::getIteration($pack);
        $nbr       = 0;

        for ($i = 1; $i <= $iteration; $i++) {
            $nbr += static::nbrFilleulByNiveau($i);
        }

        return $nbr;
    }

    /**
     * Renvoie le nombre d'interation pour l'affichage de la progression en fonction du pack actuel de l'utilisateur.
     */
    public static function getIteration(string $pack): int 
    {
        $iteration = Constants::NBR_NIVEAU;
        $pack      = strtolower($pack);

        if ($pack === 'argent') {
            // $iteration -= 5;
            // Plus besoin de ca
        }

        return $iteration;
    }
}
