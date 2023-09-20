<?php

use App\MS\Constants;

/**
 * @param int $montant
 * @return float
 */
function to_dollar($montant, string $moment = 'sortie')
{
    $dollar_value = strtolower($moment) === 'entree' ? Constants::DOLLAR_VALUE_ENTREE : Constants::DOLLAR_VALUE;

	return $montant / $dollar_value;
}

/**
 * @param float $montant
 * @return int
 */
function to_cfa($montant, string $moment = 'sortie')
{
    $dollar_value = strtolower($moment) === 'entree' ? Constants::DOLLAR_VALUE_ENTREE : Constants::DOLLAR_VALUE;

	return $montant * $dollar_value;
}

function simple_tel(string $tel): string
{
    return str_replace(['237', '.', ' ', '-', ',', '_', '+'], '', htmlspecialchars($tel));
}