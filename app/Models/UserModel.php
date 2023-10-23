<?php 

namespace App\Models;

use App\Entities\Niveau;
use App\Entities\User;
use App\Entities\Utilisateur;
use App\MS\Constants;
use BlitzPHP\Schild\Authentication\Authenticators\Session;
use BlitzPHP\Schild\Models\UserModel as SchildUserModel;
use BlitzPHP\Utilities\Iterable\Arr;
use PDO;

class UserModel extends SchildUserModel
{
    protected string $returnType     = User::class;

    // Filleuls par niveau
    private static $filleul_niv = [];


    public function findForAuth(string $login): ?User
    {
        $data = $this->select([
                $this->table . '.*',
                $this->tables['identities'] . '.secret As email',
                $this->tables['identities'] . '.secret2 As password_hash',
            ])
            ->join($this->tables['identities'], [$this->tables['identities'] . '.user_id' => $this->table . '.id'])
            ->join('utilisateurs', ['utilisateurs.user_id' => $this->table . '.id'])
            ->where($this->tables['identities'] . '.type', Session::ID_TYPE_EMAIL_PASSWORD)
            ->where(function($q) use($login) {
                return $q->orWhere([
                    'LOWER(' . $this->tables['identities'] . '.secret)' => strtolower($login),
                             'tel'                                      => simple_tel($login),
                             'ref'                                      => $login
                ]);    
            })
            ->first(PDO::FETCH_ASSOC);

        if ($data === null) {
            return null;
        }

        $email = $data['email'];
        unset($data['email']);
        $password_hash = $data['password_hash'];
        unset($data['password_hash']);
        $id = $data['id'];
        unset($data['id']);

        $className           = $this->returnType;
        $user                = new $className($data);
        $user->id            = $id;
        $user->email         = $email;
        $user->password_hash = $password_hash;
        $user->syncOriginal();

        return $user;
    }

    /**
     * Renvoi la liste de filleul d'un utilisateur
     */
    public function listFilleuls(Utilisateur $user, int|array|null $niveau = null, $withUser = false): array 
    {
        $limit = null;
        if (is_array($niveau)) {
            $limit  = $niveau['limit'] ?? null;
            $niveau = $niveau['niveau'] ?? null;
        }

        self::$filleul_niv[$user->ref] = [
            1 => [], 2 => [], 3 => [], 4 => [], 5 => [],
            6 => [], 7 => [], 8 => [], 9 => [], 10 => [], 
            11 => [], 12 => [], 13 => [], 14 => [], 15 => [],
        ];
        
        if (empty(self::$filleul_niv[$user->ref][1])) {
            // On recupere les filleuls directs du membre
            $filleuls = ($withUser === true ? $user->filleuls()->with('user') : $user->filleuls())->all();
            foreach ($filleuls as $filleul) {
                self::$filleul_niv[$user->ref][1][] = $filleul;
            }
		}

        if ($niveau === 1) {
            return self::$filleul_niv[$user->ref][1];
        }

        $nb_niveau = count(self::$filleul_niv[$user->ref]);
		
        // A partir d'ici, on recupere les sous filleuls 
		for ($i = 1; $i < $nb_niveau; $i++) {
			foreach (self::$filleul_niv[$user->ref][$i] as $filleul) {
                if (empty($filleul) || !is_a($filleul, Utilisateur::class)) {
                    continue;
				}

                if (count(self::$filleul_niv[$user->ref][($i+1)]) >= Constants::nbrFilleulByNiveau(($i + 1))) {
                    continue;
                }

				foreach (($withUser === true ? $filleul->filleuls()->with('user') : $filleul->filleuls())->all() as $valeur) {
					self::$filleul_niv[$user->ref][($i+1)][] = $valeur;
				}	
			}

            if ($i === $niveau || $i === $limit) {
                break;
            }
		}

		return in_array($niveau, [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15]) 
            ? self::$filleul_niv[$user->ref][$niveau] 
            : self::$filleul_niv[$user->ref];
	}

    public function countFilleuls(Utilisateur $user, ?int $niveau = null): int
    {
        if ($niveau == null) {
            $filleuls = $user->list_filleuls;
        } else {
            $filleuls = $this->listFilleuls($user, $niveau, false);
        }

        
        if(Arr::dimensions($filleuls) == 1) {
            $filleuls = [$filleuls];
        }
        
        $filleuls = array_reduce($filleuls, 'array_merge', []);

        if (is_array($filleuls[0])) {         
            $count = array_reduce($filleuls, fn($acc, $curr) => $acc + count($curr), 0);
        } else {
            $count = count($filleuls);
        }        

        return $count;
    }

    public function valideNiveau(Utilisateur $user, int $niveau)
    {
        if ($user->niveau >= $niveau) {
            return;
        }

        try {
            $this->beginTransaction();

            Niveau::create(['user_id' => $user->id, 'niveau' => $niveau]);
            $user->niveau = $niveau;

            if (!$this->aRecuGain($user, $niveau)) {
                $montant = Constants::GAINS_NIVEAU[$niveau] * 0.5;
                if (in_array($niveau, [5, 10], true)) {
                    $montant = 0;
                }

                $this->insertRecuGain($user, $niveau, Constants::GAINS_NIVEAU[$niveau], $montant);
                
                if (! in_array($niveau, [5, 10], true)) {
                    $user->increment('solde_principal', $montant);
                } else if ($niveau === 5) {
                    $user->pack = 'or';
                } else if ($niveau === 10) {
                    $user->pack = 'diamant';
                }
            }

            $user->save();

            $this->commit();
        } catch (\Throwable $th) {
            $this->rollback();
            throw $th;
        }
    }

    /**
     * Determine si un utilisateur a recu son gain d'un niveau
     */
    public function aRecuGain(Utilisateur $user, int $niveau): bool
    {
        return $this->from('gains')->where(['niveau' => $niveau, 'user_id' => $user->id])->count() > 0;
    }

    public function insertRecuGain(Utilisateur $user, int $niveau, $reel, $recu)
    {
        $this->from('gains')->insert([
            'niveau'       => $niveau,
            'user_id'      => $user->id,
            'montant_reel' => $reel,
            'montant_recu' => $recu,
            'created_at'   => date('Y-m-d H:i:s'),
            'updated_at'   => date('Y-m-d H:i:s'),
        ]);
    }
}