<?php

use App\Entities\Notification;
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

function hello() 
{
	$heure = date("H");
    
	if ($heure >= 4 AND $heure <= 6) {
      echo 'Bon reveil !';
    }
	else if ($heure >= 7 AND $heure <= 12) {
      echo 'Bonjour !';
    }
	else if ($heure >= 13 && $heure <= 18) {
      echo 'Salut !';
    }
	else if($heure > 18 && $heure <= 23) {
		echo 'Bonsoir !';
    }
	else {
		echo 'C\'est l\'heure de se coucher !';
	}
}

function get_notification_icon(string $type): string
{
    return match($type) {
        Notification::PRODUCTION_FONDS  => 'fa-donate',
        Notification::CREDIT_DEBIT  => 'fa-coins',
        Notification::DEBLOCAGE  => 'fa-unlock',
        Notification::GENERATION_COMPTE  => 'fa-user-friends',
        Notification::VIREMENT_FONDS  => 'fa-retweet',
        Notification::BONUS  => 'fa-box-open',
        Notification::RECEPTION_FONDS  => 'fa-level-down-alt',
        Notification::NOUVEAU_ADMIN  => 'fa-user-tie',
        default => 'fa-hashtag'
    };
}


if (! function_exists('h_p')) {
    /**
     * Phrase Highlighter
     *
     * Highlights a phrase within a text string
     *
     * @param string $str      the text string
     * @param string $phrase   the phrase you'd like to highlight
     * @param string $tagOpen  the opening tag to precede the phrase with
     * @param string $tagClose the closing tag to end the phrase with
     */
    function h_p(string $str, string $phrase, string $tagOpen = '<mark>', string $tagClose = '</mark>'): string
    {
        return ($str !== '' && $phrase !== '') ? preg_replace('/(' . preg_quote($phrase, '/') . ')/i', $tagOpen . '\\1' . $tagClose, $str) : $str;
    }
}