<?php

namespace App\Entities;

use BlitzPHP\Wolke\Model;

class Notification extends Model
{
    const PRODUCTION_FONDS = 1;
	const CREDIT_DEBIT = 2;
	const DEBLOCAGE = 3;
	const GENERATION_COMPTE = 4;
	const VIREMENT_FONDS = 'VIREMENT_DE_FONDS';
	const BONUS = 6;
	const RECEPTION_FONDS = 'RECEPTION_DE_FONDS';


	const NOUVEAU_ADMIN = 10;


    protected array $fillable = [
        'user_id', 'libelle', 'description', 
        'lu', 'type',
    ];

    public function user() 
    {
        return $this->belongsTo(Utilisateur::class, 'user_id');
    }


    /**
     * Virement de fonds dans le compte principal
     */
	public static function virementFonds(Utilisateur $user, Utilisateur $reserver, $montant, $type)
	{
        $data = [
            'libelle' => 'Virement de fonds',
            'type'    => self::VIREMENT_FONDS,
            'user_id' => $user->id
        ];

        if ($type == 'entree') {
            $data['description'] = '<p>
                Vous venez de recevoir les <b>' . number_format($montant).' $</b>. de votre compte <i>' . $reserver->ref . '</i>.
            </p>';
        }
        else if($type == 'sortie')
		{
            $data['description'] = '<p>
                Vos <b>'.number_format($montant).' $</b>. ont été transferés à votre compte <i>' . $reserver->ref . '</i>. 
            </p>';	
		}

        self::create($data);
	}

    /**
     * Reception de fonds suite a un transfert 
     */
	public static function receptionFonds($montant, Utilisateur $user, Utilisateur $sender, string $type_compte)
	{
        self::create([
            'libelle' => 'Reception de fonds',
            'type'    => self::RECEPTION_FONDS,
            'user_id' => $user->id,
            'description' => '
                <p>
                    Vous venez de recevoir <b>'.number_format($montant).' $</b>. dans votre compte <i>'.$type_compte.'</i> de la part de '.$sender->ref.'.
                </p>
            '
        ]);
	}
}
