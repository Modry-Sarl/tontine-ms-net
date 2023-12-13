<?php $this->section('title', 'Informations du membre'); ?>
<?php $this->extend('admin') ?>

<?php $this->section('content') ?>

<div class="card">
    <div class="card-body d-flex justify-content-between align-items-center flex-wrap">
        <div><?php if (empty($ref)): ?>
            <span class="text-warning">Veuillez entrer l'identifiant du membre dont vous voullez configurer.</span>
        <?php elseif (empty($user)): ?>
            <span class="text-warning">L'identifiant que vous avez entré est incorrect. Veuillez le verifier et réessayer.</span>
        <?php else: ?>
            <span class="text-primary">Configuration du membre <b><?= $user->ref ?> (<?= $user->user->tel ?> / <?= $user->user->username ?>)</b></span>
        <?php endif; ?></div>

        <form class="form-inline float-right" method="get" >
            <div class="input-group">
                <input type="text" name="ref" value="<?= $ref ?? null; ?>" class="form-control form-control-sm" placeholder="Identifiant du membre" />
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary btn-sm">Valider</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php if (!empty($user)) : ?><div class="row flex-lg-row-reverse">
    <div class="col-lg-3 border-left-lg border-dark mb-3">
        <ul class="nav nav-pills flex-column">
            <li class="nav-item">
                <a <?= $this->class(['nav-link text-uppercase', 'active' => $tab == 'profil']) ?> href="<?= current_url(true)->addQuery('tab', 'profil') ?>">Profil d'utilisateur</a>
            </li>
            <li class="nav-item">
                <a <?= $this->class(['nav-link text-uppercase', 'active' => $tab == 'access']) ?> href="<?= current_url(true)->addQuery('tab', 'access') ?>">Accès au compte</a>
            </li>
            <li class="nav-item">
                <a <?= $this->class(['nav-link text-uppercase', 'active' => $tab == 'identity']) ?> href="<?= current_url(true)->addQuery('tab', 'identity') ?>">Identité</a>
            </li>
            <li class="nav-item">
                <a <?= $this->class(['nav-link text-uppercase', 'active' => $tab == 'permutation']) ?> href="<?= current_url(true)->addQuery('tab', 'permutation') ?>">Permutation</a>
            </li>
            <hr>
            <li class="nav-item">
                <a class="nav-link text-uppercase" href="<?= link_to('admin.membre') . '?ref=' . $ref; ?>">Informations du membre</a>
            </li>
        </ul>
    </div>
    <div class="col-lg-9">
        <?= $this->includeIf('config.' . $tab) ?>
    </div>
</div><?php endif; ?>

<?php $this->end() ?>

