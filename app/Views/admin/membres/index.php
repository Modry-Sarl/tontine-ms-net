<?php $this->section('title', 'Liste des membres MS-NET'); ?>
<?php $this->extend('admin') ?>

<?php $this->section('content') ?>

<div class="container">
	<div class="card">
        <div class="card-header row flex-wrap justify-content-between align-items-center">
            <div class="col-12 col-lg-8 d-flex align-items-center text-center">
                <h6 class="h5 mx-1"><b class="text-primary"><?= $users->total() ?></b> utilisateurs au total</h6>
                <h6 class="h5">/</h6>
                <h6 class="h5 mx-1"><b class="text-primary"><?= $users->count() ?></b> sur cette page</h6>
            </div>
            <form class="col-12 col-lg-4" action="" method="get">
                <div class="input-group">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Rechercher un membre" value="<?= $search ?? '' ?>" />
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr class="bg-light">
                            <td style="border:none"></td>
                            <td>Profil</td>
                            <td>Date d'inscription</td>
                            <td>Type de compte</td>
                            <td>Progression</td>
                            <td style="border:none"></td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($users as $user): ?>
                        <tr>
                            <td><img src="<?= $user->user->avatar ?>" alt="<?= $user->user->username ?>" style="width:2em;height:2em;" class="img-thumbnail rounded-circle img-fluid"/></td>
                            <td>
                                <h5 class="m-0"><?= h_p($user->user->tel, $search ?: '') ?></h5>
                                <hr class="my-2" />
                                <h6 class="m-0"><?= h_p($user->ref, $search ?: '') ?></h6>
                                <span><?= h_p($user->user->username, $search ?: '') ?></span>
                            </td>
                            <td>
                                <?= $user->created_at->format('l, d F Y H:i') ?>
                                <br/>
                                Derniere connexion : <?= $user->user->last_active->format('l, d F Y H:i') ?>
                            </td>
                            <td><?= $user->main == 1 ? 'Compte principal' : 'Compte secondaire' ?></td>
                            <td>
                                Pack : <b><?= ucfirst($user->pack) ?></b>
                                <br/>
                                Niveau : <b><?= $user->niveau ?></b>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown"></button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="<?= link_to('admin.membre') . '?ref=' . $user->ref ?>">Details</a>
                                        <a class="dropdown-item" href="<?= link_to('admin.membre.config') . '?ref=' . $user->ref ?>">Configurer</a>
                                        <a class="dropdown-item" href="<?= link_to('admin.membre.config') . '?ref=' . $user->ref ?>&tab=attribution">Attribuer à un autre</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <?= $users->links() ?>
        </div>
    </div>
</div>

<?php $this->end() ?>