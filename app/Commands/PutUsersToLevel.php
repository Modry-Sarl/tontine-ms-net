<?php

namespace App\Commands;

use App\Entities\Utilisateur;
use App\Models\UserModel;
use BlitzPHP\Cli\Console\Command;
use BlitzPHP\Wolke\Collection;
use Throwable;

class PutUsersToLevel extends Command
{
    /** @var string Groupe auquel appartient la commande */
    protected $group = 'Virmo Cash';

    /** @var string Nom de la commande */
    protected $name = 'vc:bot:put-users-to-level';

    /** @var string Description de la commande */
    protected $description = 'Place les membres à un niveau donné (en les attribuants les gains qui vont avec)';

    /**
     * Execution de la commande
     *
     * @param array $params
     */
    public function execute(array $params)
    {
        $model = model(UserModel::class);
        $ids = [
            'MS032PZ2309' => [3],
            'VC126KB2504' => [1],
            'VC126PA2504' => [1],
            'VC127QK2505' => [1, 2],
            'MS041OK2310' => [4],
            'MS048ML2310' => [4],
            'MS026FM2309' => [4],
            'MS112TQ2409' => [1],
            'MS112KI2409' => [1],
            'MS043PF2310' => [4],
            'MS113QD2409' => [1],
            'MS119XK2411' => [1],
            'MS098KE2401' => [1],
            'MS119AX2411' => [2],
            'VC127BC2505' => [1, 2],
            'MS106WQ2407' => [1],
        ];

        /**
         * @var Collection<Utilisateur>
         */
        $users = Utilisateur::whereIn('ref', array_keys($ids))->get();

        $progress = $this->progress($users->count());
        $fails = [];

        foreach ($users as $user) {
            $this->info('Traitement du compte ' . $user->ref);

            foreach ($ids[$user->ref] as $niveau) {
                try {
                    $model->valideNiveau($user, $niveau);

                    $this->justify('User - ' . $user->ref, $this->color->ok('Niveau ' . $niveau . ' Ok'));
                } catch(Throwable $e) {
                    $this->justify('User - ' . $user->ref, $this->color->error('Niveau ' . $niveau . ' Error'));
                    $fails[] = [
                        'user' => $user->ref,
                        'niveau' => $niveau,
                        'error' => $e->getMessage(),
                    ];
                }
            }

            $progress->advance();
        }

        $this->info('Terminer');

        if ($fails !== []) {
            $this->eol()->json($fails);
        } 
    }
}
