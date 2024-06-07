<?php $this->section('title', 'Approbations de retraits'); ?>
<?php $this->extend('admin') ?>

<?php $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <ul class="nav nav-pills" id="myTab" role="tablist">
            <li class="nav-item">
                <a role="tab" <?= $this->class(['nav-link text-uppercase', 'active' => $tab == 'pending']) ?>
                    href="<?= current_url(true)->addQuery('tab', 'pending') ?>">En attente</a>
            </li>
            <li class="nav-item">
                <a role="tab" <?= $this->class(['nav-link text-uppercase', 'active' => $tab == 'validated']) ?>
                    href="<?= current_url(true)->addQuery('tab', 'validated') ?>">Validés</a>
            </li>
            <li class="nav-item">
                <a role="tab" <?= $this->class(['nav-link text-uppercase', 'active' => $tab == 'rejected']) ?>
                    href="<?= current_url(true)->addQuery('tab', 'rejected') ?>">Rejétés</a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <?php if ($errors->count()) : ?>
            <div class="alert alert-danger"><?= $errors->line('default') ?></div>
        <?php elseif (session()->has('success')): ?>
                <div class="alert alert-success"><?= session('success') ?></div>
        <?php endif; ?>

        <?php if (!$approbations->count()): ?>
        <div class="alert alert-info text-center">
            <i class="fa fa-exclamation-triangle fa-3x"></i>
            <h4 class="mt-3">Aucune demande de retrait 
                <?= $tab == 'pending' ? 'en attente de validation' : '' ?>
                <?= $tab == 'validated' ? 'validée' : '' ?>
                <?= $tab == 'rejected' ? 'rejétée' : '' ?>
            </h4>
        </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr class="bg-light">
                        <td style="border:none"></td>
                        <td>Téléphone</td>
                        <td>Montant</td>
                        <td>Utilisateur</td>
                        <td>Compte</td>
                        <td>Ref</td>
                        <?php if ($tab == 'pending'): ?>
                        <td style="border:none"></td>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0; foreach($approbations as $retrait): ?>
                    <tr>
                        <td><?= ++$i ?></td>
                        <td><?= scl_splitInt($retrait->tel, 2) ?></td>
                        <td><?= scl_splitInt($retrait->montant) ?> $</td>
                        <td>
                            <h6 class="m-0"><a href="<?= link_to('admin.membre') . '?ref=' . $retrait->user->ref ?>"><?= $retrait->user->ref ?></a></h6>
                            <span><?= $retrait->user->user?->username ?></span>
                        </td>
                        <td><?= $retrait->compte ?></td>
                        <td><?= $retrait->ref ?></td>
                        <?php if ($tab == 'pending'): ?>
                        <td> 
                            <div class="d-flex">
                                <form class="d-inline" method="post">
                                    <input type="hidden" name="action" value="validated" />
                                    <input type="hidden" name="ref" value="<?= $retrait->ref ?>" />
                                    <button type="submit" class="btn btn-sm btn-icon btn-success" data-toggle="tooltip" title="Approuver"><i class="fa fa-check-circle"></i></button>
                                </form>
                                <form class="d-inline" method="post">
                                    <input type="hidden" name="action" value="rejected" />
                                    <input type="hidden" name="ref" value="<?= $retrait->ref ?>" />
                                    <button type="submit" class="btn btn-sm btn-icon btn-danger" data-toggle="tooltip" title="Rejéter"><i class="fa fa-times-circle"></i></button>
                                </form>
                            </div>
                        </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>

        <?= $approbations->links() ?>
    </div>
</div>

<?php $this->end() ?>