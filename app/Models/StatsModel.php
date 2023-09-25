<?php

namespace App\Models;

use App\Entities\Utilisateur;
use BlitzPHP\Database\Model;

class StatsModel extends Model
{
    /**
     * Renvoie le nombre de filleuls direct d'un membre
     */
    public function countFilleuls(string $ref) : int
    {
        return $this->from('utilisateurs')->where('ref', $ref)->count();
    }

    /**
     * Compte le nombre de membres de membres par niveau
     */
    public function countMembersByNiveau(int $max = 5): array
    {
        $membres_niveau = $this->from('utilisateurs')
            ->select(['count(id) as count', 'niveau'])
            ->whereBetween('niveau', 0, $max)
            ->group('niveau')
            ->sortAsc('niveau')
            ->all();

        $niveaux_presents = array_map(fn($mn) => $mn->niveau, $membres_niveau);
        $arr = [];
        for ($i = 0; $i <= $max; $i++) {
            $arr[] = $i;
        }

        $niveaux_manquants = array_diff($arr, $niveaux_presents);

        foreach ($niveaux_manquants as $niveau) {
            $membres_niveau[] = (object) ['count' => 0, 'niveau' => $niveau];
        }
        
        return $membres_niveau; 
    }

    /**
    * Renvoi les dernieres inscriptions enregistrees dans la plateforme

    * @return array<Utilisateur>
    */
    public function lastInscriptions(int $limit = 5): array
    {
        $limit = $limit < 1 ? 5 : $limit;
    
        return Utilisateur::with('user')->latest()->limit($limit)->all();
    }

    /**
     * Compte le nombre de membre de la plateforme
     */
    public function countMembers(array $filter = []) : int
    {
        return $this->_compileFilter((array) $filter)->count();
    }

    /**
     * Renvoie les membres enregistres en base de donnees
     *
     * @param array $filter
     * @param bool $hydrate
     * @return array
     */
    public function members(?array $filter = [], bool $hydrate = false) : array
    {
        return $this->_compileFilter((array) $filter)->select('*, m.id_membre as id_membre')->all($hydrate ? MembresEntity::class : null);
    }

    /**
     * Renvoie la liste ordonnee des meilleurs inscripteurs de la plateforme
     *
     * @return array
     */    
    public function bestRegister(int $limit = 6) : array
    {
        return $this
            ->select(['auth_identities.secret as tel', 'users.username', 'utilisateurs.ref', 'utilisateurs.niveau', 'utilisateurs.pack', 'COUNT(inscriptor) as nbrInscriptions'])
            ->from('inscriptions')
            ->join('utilisateurs', ['utilisateurs.user_id' => 'inscriptions.inscriptor'])
            ->join('users', ['utilisateurs.user_id' => 'users.id'])
            ->join('auth_identities', ['utilisateurs.user_id' => 'auth_identities.user_id'])
            ->groupBy('inscriptor')
            ->sortDesc('nbrInscriptions')
            ->limit($limit)
            ->all();
    }

    /**
    * Renvoi les dernieres transactions effectuees dans la plateforme
    *
    * @param integer $limit
    * @param boolean $hydrate
    * @return void
    */
    public function lastTransactions(int $limit = 5)
    {
        $limit = (!is_int($limit) OR $limit < 1) ? 5 : $limit;
        
        return $this
            ->from('transactions As t')
            ->join('useragents As ua', ['t.id_ua' => 'ua.id_ua'])
            ->order('ua.date_ua', 'DESC')
            ->limit($limit)
            ->all();
    }


    /**
     * Genere la requete sql de selection des membres en fonction des filtres passes en arguments
     *
     * @param array $filter
     * @return StatsModel
     */
    private function _compileFilter(array $filter) : self
    {
        $this->from('utilisateurs');
        
        if (isset($filter['sort_by'])) {
            $day   = date('j');                                                    // Jour courant
            $week  = date("W", mktime(0, 0, 0, date('n'), date('d'), date('y')));  // Semaine courante
            $month = date('m');                                                    // Mois courant
            $year  = date('Y');                                                    // Annee courante

            if ($filter['sort_by'] === 'day') {
                $this->where([
                    'DAY(utilisateurs.created_at)'   => $day,
                    'MONTH(utilisateurs.created_at)' => $month,
                    'YEAR(utilisateurs.created_at)'  => $year
                ]);
            }
            else if ($filter['sort_by'] === 'week') {
                $this->where([
                    'WEEK(utilisateurs.created_at)' => $week,
                    'YEAR(utilisateurs.created_at)' => $year
                ]);
            }
            else if ($filter['sort_by'] === 'month') {
                $this->where([
                    'MONTH(utilisateurs.created_at)' => $month,
                    'YEAR(utilisateurs.created_at)'  => $year
                ]);
            }
            else if ($filter['sort_by'] === 'year') {
                $this->where(['YEAR(utilisateurs.created_at)' => $year]);
            }

            if ($filter['sort_by'] === 'day_before') {
                if ($day == 1) {
                    if ($month == 1) {
                        $day = 31;
                        $month = 12;
                        $year--;
                    }
                    else {
                        $month--;
                        $day = date('t', mktime( 0, 0, 0, $month, 1, $year));
                    }
                }
                else {
                    $day--;
                }
                $this->where([
                    'DAY(utilisateurs.created_at)'   => $day,
                    'MONTH(utilisateurs.created_at)' => $month,
                    'YEAR(utilisateurs.created_at)'  => $year
                ]);
            }
            else if ($filter['sort_by'] === 'week_before') {
                if ($week == 1) {
                    $week = 52;
                    $year--;
                }
                else {
                    $week--;
                }
                $this->where([
                    'WEEK(utilisateurs.created_at)' => $week,
                    'YEAR(utilisateurs.created_at)' => $year
                ]);
            }
            else if ($filter['sort_by'] === 'month_before') {
                if ($month == 1) { 
                    $month = 12;
                    $year--;
                }
                else {
                    $month--;
                }
                $this->where([
                    'MONTH(utilisateurs.created_at)' => $month,
                    'YEAR(utilisateurs.created_at)'  => $year
                ]);
            }
            else if($filter['sort_by'] === 'year_before') {
                $this->where(['YEAR(utilisateurs.created_at)' => $year -1]);
            }
        }

        if (isset($filter['level']) AND (int) $filter['level'] >= 0 AND (int)$filter['level'] <= 11) {
            $this->where('niveau', (int) $filter['level']);
        }

        if (isset($filter['limit']) AND $filter['limit'] !== null AND $filter['limit'] != -1) {
            $filter['limit'] = str_replace(['-', '_', '.'], ',', $filter['limit']);
            $limit = explode(',', $filter['limit']);
            if (count($limit) == 1) {
                $this->limit((int)$limit[0]);
            }
            if (count($limit) == 2) {
                $this->limit((int) $limit[0], (int) $limit[1]);
            }
        }

        if (!empty($filter['search'])) {
            $q = htmlspecialchars($filter['search']);
            $this->whereLike('ref', $q)->orWhereLike('tel', $q);
        }

       return $this;
    }
}
