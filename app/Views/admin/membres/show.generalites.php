<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header py-2 d-flex justify-content-between align-items-center theme-bg">
                <h5 class=" text-white"><i class="fa fa-id-badge fa-fw"></i> Identité</h5>
                <div class="btn-group">
                    <button type="button" class="btn btn-default btn-xs dropdown-toggle"
                        onclick="window.location.href='<?= link_to('admin.membre.config') . '?ref=' . $ref; ?>';">
                        Configurer &nbsp; <span class="fa fa-cog"></span>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <section class=" table-responsive">
                    <table class="table table-striped table-condensed">
                        <tbody>
                            <tr>
                                <td><img src="<?= $user->user->avatar; ?>" style="width:4em; height:4em;"
                                        class="img-fluid img-thumbnail rounded" /></td>
                                <td>
                                    <h3 class="mt-0"><?= $user->ref; ?></h3>
                                    <dl class="row">
                                        <dt class="col-sm-5">Pseudonyme</dt>
                                        <dd class="col-sm-7"><?= $user->user->username; ?></dd>
                                    </dl>
                                    <dl class="row">
                                        <dt class="col-sm-5">Telephone</dt>
                                        <dd class="col-sm-7">
                                            <?= scl_splitInt($user->user->tel, 2).' &nbsp; - &nbsp; '.$user->user->pays; ?>
                                        </dd>
                                    </dl>
                                    <dl class="row">
                                        <dt class="col-sm-5">Date d'inscription</dt>
                                        <dd class="col-sm-7"><?= $user->created_at->format('l, d F Y H:i'); ?></dd>
                                    </dl>
                                    <dl class="row">
                                        <dt class="col-sm-5">Derniere connexion</dt>
                                        <dd class="col-sm-7"><?= $user->user->last_active->format('l, d F Y H:i') ?>
                                        </dd>
                                    </dl>
                                    <dl class="row">
                                        <dt class="col-sm-5">Type de compte</dt>
                                        <dd class="col-sm-7">
                                            <?= $user->main == 1 ? 'Compte principal' : 'Compte secondaire' ?></dd>
                                    </dl>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </section>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header theme-bg">
                <h5 class=" text-white"><i class="fa fa-chart-pie fa-fw"></i> Progression</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="px-0 list-group-item d-flex justify-content-between">
                        <span class="mb-0">Classe</span>
                        <strong class="font-weight-bold text-dark"><?= ucfirst($user->pack); ?></strong>
                    </li>
                    <li class="px-0 list-group-item d-flex justify-content-between">
                        <span class="mb-0">Niveau actuel</span>
                        <strong
                            class="font-weight-bold text-dark"><?= $user->niveau. ' / ' . \App\MS\Constants::NBR_NIVEAU; ?></strong>
                    </li>
                    <li class="px-0 list-group-item d-flex justify-content-between">
                        <span class="mb-0">Nombre de descendants</span>
                        <strong class="font-weight-bold text-dark"><?= scl_splitInt($nbr_filleuls) ?></strong>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card">
            <div class="card-header theme-bg">
                <h5 class=" text-white"><i class="fa fa-dollar-sign fa-fw"></i> Soldes</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="px-0 list-group-item d-flex justify-content-between"><span class="mb-0">Solde
                            principal</span> <strong
                            class="font-weight-bold text-dark"><?= number_format($user->solde_principal, 2, '.', ' ') ?>
                            $</strong></li>
                    <li class="px-0 list-group-item d-flex justify-content-between"><span class="mb-0">Solde
                            recharge</span> <strong
                            class="font-weight-bold text-dark"><?= number_format($user->solde_recharge, 2, '.', ' ') ?>
                            $</strong></li>
                    <li class="px-0 list-group-item d-flex justify-content-between"><span class="mb-0">Gains
                            cumulés</span> <strong
                            class="font-weight-bold text-dark"><?= number_format($user->gains_cumules, 2, '.', ' ') ?>
                            $</strong></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header theme-bg">
                <h5 class=" text-white"><i class="fa fa-chart-line fa-fw"></i> Fiche de progression</h5>
            </div>
            <div class="card-body">
                <section class="col-sm-12 table-responsive">
                    <table class="table table-striped table-condensed table-bordered">
                        <thead>
                            <tr class="active">
                                <td>Niveau</td>
                                <td>Statut </td>
                                <td>Gains</td>
                            </tr>
                        </thead>
                        <tbody><?php for ($i = 1; $i <= $iteration; $i++): ?>
                            <?php if (in_array($i, $niveaux, true)) {
                                                $niveau = $user->niveaux->first(fn($n) => $n->niveau == $i);
                                                
                                                $class = 'bg-success';
                                                $statut = '<h5>Validé</h5><span class="text-white">Le : '. $niveau->created_at .'</span>';
                                                $gains =  '<h5>' . number_format(0.5 * \App\MS\Constants::GAINS_NIVEAU[$i], 0, '.', ' ') . ' $ Recu</h5><span class="text-white">Le : ' . $niveau->created_at . '</span>';
                                            } else {
                                                $class = 'bg-danger';
                                                $statut = '<h5>Non validé</h5>';
                                                $gains =  '<h5>En attente de validation</h5>';
                                            } ?>

                            <tr class="<?= $class ?>">
                                <td class="text-white-50"><?= $i ?></td>
                                <td><?= $statut ?></td>
                                <td><?= $gains ?></td>
                            </tr>
                            <?php endfor; ?></tbody>
                    </table>
                </section>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header theme-bg">
                <h5 class=" text-white"><i class="fa fa-sort-numeric-down fa-fw"></i> Etat de parainage</h5>
            </div>
            <div class="card-body">
                <table class="w-100 table table-striped table-condensed table-bordered">
                    <thead>
                        <tr class="active">
                            <td>Niveau</td>
                            <td>Nombre de filleuls </td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for ($i = 1; $i <= $iteration; $i++): ?>
                        <tr>
                            <td><?= $i ?></td>
                            <td><?= number_format(count($filleuls[$i]), 0, '.', ' ') . ' / ' . number_format(\App\MS\Constants::nbrFilleulByNiveau($i), 0, '.', ' ') ?>
                            </td>
                        </tr>
                        <?php endfor; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header theme-bg">
                <h5 class=" text-white"><i class="fa fa-users fa-fw"></i> Famille V-Cash</h5>
            </div>
            <div class="card-body">
                <section class="table-responsive">
                    <table class="table table-striped table-condensed">
                        <tbody>
                            <tr class="active">
                                <td colspan="2">
                                    <h4>Parrain</h4>
                                </td>
                            </tr>
                            <tr>
                                <?php if (empty($user->parrain)): ?>
                                <td colspan="2">Ce membre est au sommet de la pyramide V-Cash<br /><br /></td>
                                <?php else: ?>
                                <td><img class="border rounded p-1" src="<?= $user->referer->user->avatar ?>"
                                        style="width:2em;height:2em;" /></td>
                                <td>
                                    <h4 class="mt-0"><a class="text-dark" href="<?= link_to('admin.membre') . '?ref=' . $user->referer->ref; ?>"><?= $user->referer->ref; ?></a></h4>
                                    <dl class="row">
                                        <dt class="col-sm-4">Téléphone</dt>
                                        <dd class="col-sm-8 font-weight-bold text-dark">
                                            <?= scl_splitInt($user->referer->user->tel, 2) ?></dd>

                                        <dt class="col-sm-4 mt-2">Pseudonyme</dt>
                                        <dd class="col-sm-8 mt-2 font-weight-bold text-dark">
                                            <?= $user->referer->user->username; ?></dd>

                                        <dt class="col-sm-4 mt-2">Niveau</dt>
                                        <dd class="col-sm-8 mt-2 font-weight-bold text-dark">
                                            <?= $user->referer->niveau; ?></dd>
                                    </dl>
                                </td>
                                <?php endif; ?>
                            </tr>
                            <tr class="active">
                                <td colspan="2">
                                    <h4>Filleuls directs</h4>
                                </td>
                            </tr>
                            <?php if (1 > count($filleuls[1])): ?>
                            <tr>
                                <td colspan="2">Vous n'avez aucun filleuls pour le moment<br /><br /></td>
                            </tr>
                            <?php else: foreach($filleuls[1] as $filleul): ?>
                            <tr>
                                <td><img class="border rounded p-1" src="<?= $filleul->user->avatar ?>"
                                        style="width:2em;height:2em;" /></td>
                                <td>
                                    <h4 class="mt-0"><a class="text-dark" href="<?= link_to('admin.membre') . '?ref=' . $filleul->ref; ?>"><?= $filleul->ref; ?></a></h4>
                                    <dl class="row">
                                        <dt class="col-sm-4">Téléphone</dt>
                                        <dd class="col-sm-8 font-weight-bold text-dark">
                                            <?= scl_splitInt($filleul->user->tel, 2) ?></dd>

                                        <dt class="col-sm-4 mt-2">Pseudonyme</dt>
                                        <dd class="col-sm-8 mt-2 font-weight-bold text-dark">
                                            <?= $filleul->user->username; ?></dd>

                                        <dt class="col-sm-4 mt-2">Niveau</dt>
                                        <dd class="col-sm-8 mt-2 font-weight-bold text-dark"><?= $filleul->niveau; ?>
                                        </dd>
                                    </dl>
                                </td>
                            </tr>
                            <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </section>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header theme-bg">
                <h5 class=" text-white"><i class="fa fa-user-tie fa-fw"></i> Informations personnelles</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="px-0 list-group-item d-flex justify-content-between">
                        <span class="mb-0">Nom</span>
                        <strong
                            class="font-weight-bold text-dark"><?= $user->user->nom ?: '<small class="font-italic small text-small">Non renseigné</small>' ?></strong>
                    </li>
                    <li class="px-0 list-group-item d-flex justify-content-between">
                        <span class="mb-0">Prenom</span>
                        <strong
                            class="font-weight-bold text-dark"><?= $user->user->prenom ?: '<small class="font-italic small text-small">Non renseigné</small>' ?></strong>
                    </li>
                    <li class="px-0 list-group-item d-flex justify-content-between">
                        <span class="mb-0">Email</span>
                        <strong
                            class="font-weight-bold text-dark"><?= $user->user->email ?: '<small class="font-italic small text-small">Non renseigné</small>' ?></strong>
                    </li>
                    <li class="px-0 list-group-item d-flex justify-content-between">
                        <span class="mb-0">Sexe</span>
                        <strong
                            class="font-weight-bold text-dark"><?= $user->user->sexe ?: '<small class="font-italic small text-small">Non renseigné</small>' ?></strong>
                    </li>
                    <li class="px-0 list-group-item d-flex justify-content-between">
                        <span class="mb-0">Date de naissance</span>
                        <strong
                            class="font-weight-bold text-dark"><?= $user->user->date_naiss ?: '<small class="font-italic small text-small">Non renseigné</small>' ?></strong>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card">
            <div class="card-header theme-bg">
                <h5 class=" text-white"><i class="fa fa-map-marker fa-fw"></i> Localisation</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="px-0 list-group-item d-flex justify-content-between">
                        <span class="mb-0">Pays</span>
                        <strong
                            class="font-weight-bold text-dark"><?= $user->user->pays ?: '<small class="font-italic small text-small">Non renseigné</small>' ?></strong>
                    </li>
                    <li class="px-0 list-group-item d-flex justify-content-between">
                        <span class="mb-0">Ville</span>
                        <strong
                            class="font-weight-bold text-dark"><?= $user->user->ville ?: '<small class="font-italic small text-small">Non renseigné</small>' ?></strong>
                    </li>
                    <li class="px-0 list-group-item d-flex justify-content-between">
                        <span class="mb-0">Quartier</span>
                        <strong
                            class="font-weight-bold text-dark"><?= $user->user->quartier ?: '<small class="font-italic small text-small">Non renseigné</small>' ?></strong>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>