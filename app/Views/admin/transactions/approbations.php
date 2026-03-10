<?php $this->section('title', 'Approbations de retraits'); ?>
<?php $this->extend('admin') ?>

<?php $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <ul class="nav nav-pills" id="myTab" role="tablist">
            <li class="nav-item">
                <a role="tab" <?= $this->class(['nav-link text-uppercase', 'active' => $tab == 'pending']) ?>
                    href="<?= current_url(true)->setQueryArray(['tab' => 'pending']) ?>">En attente</a>
            </li>
            <li class="nav-item">
                <a role="tab" <?= $this->class(['nav-link text-uppercase', 'active' => $tab == 'validated']) ?>
                    href="<?= current_url(true)->setQueryArray(['tab' => 'validated']) ?>">Validés</a>
            </li>
            <li class="nav-item">
                <a role="tab" <?= $this->class(['nav-link text-uppercase', 'active' => $tab == 'rejected']) ?>
                    href="<?= current_url(true)->setQueryArray(['tab' => 'rejected']) ?>">Rejétés</a>
            </li>
            <li class="nav-item">
                <a role="tab" <?= $this->class(['nav-link text-uppercase', 'active' => $tab == 'massive']) ?>
                    href="<?= current_url(true)->setQueryArray(['tab' => 'massive']) ?>">Retraits en masse</a>
            </li>
        </ul>
        <div class="row align-items-center">
            <div class="col-lg-4 col-md-6">
                <div class="card card-primary border-primary">
                    <div class="card-body px-3">
                        <div class="row flex-nowrap">
                            <div class="col-1">
                                <i class="fa fa-coins fa-2x"></i>
                            </div>
                            <div class="col-11 text-right">
                                <div class="d-flex align-items-center justify-content-end">
                                    <div class="">
                                        <span class="d-inline-block w-100">Solde du compte de collecte</span>
                                        <div class="mt-3 d-flex align-items-center h4 font-weight-bold text-warning">
                                            <span class="pr-2 border-right"><?= number_format(to_dollar($solde_collecte, 'entree'), 3, '.', ' ') ?> $</span>
                                            <span class="pl-2 text-left"><?= number_format($solde_collecte, 0, '.', ' ') ?> F</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="progress m-t-30" style="height: 7px;">
                            <div class="progress-bar progress-c-theme w-100"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card card-primary border-primary">
                    <div class="card-body px-3">
                        <div class="row flex-nowrap">
                            <div class="col-1">
                                <i class="fa fa-money-bill fa-2x"></i>
                            </div>
                            <div class="col-11 text-right ">
                                <div class="d-flex align-items-center justify-content-end">
                                    <div class="">
                                        <span class="d-inline-block w-100">Solde du compte de paiement</span>
                                        <div class="mt-3 d-flex align-items-center h4 font-weight-bold text-warning">
                                            <span class="pr-2 border-right"><?= number_format(to_dollar($solde_paiement, 'sortie'), 3, '.', ' ') ?> $</span>
                                            <span class="pl-2 text-left"><?= number_format($solde_paiement, 0, '.', ' ') ?> F</span>
                                        </div>
                                    </div>
                                </div>               
                            </div>
                        </div>
                        <div class="progress m-t-30" style="height: 7px;">
                            <div class="progress-bar progress-c-theme w-100"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <form method="post">
                    <input type="hidden" name="action" value="transfert-fund" />
                    <button type="submit" class="btn btn-primary btn-lg btn-block">Transférer les fond <br />vers le compte de paiement</button>
                </form>
            </div>
        </div>
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
                <?= $tab == 'massive' ? 'en masse' : '' ?>
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
                        <td><?= $tab === 'massive' ? 'Statut' : 'Utilisateur' ?></td>
                        <td><?= $tab === 'massive' ? 'Date d\'initialisation' : 'Compte' ?></td>
                        <td>Ref</td>
                        <?php if (in_array($tab, ['pending', 'massive'])): ?>
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
                        <td><?php if ($tab === 'massive'): ?>
                            <span <?= $this->class([
                                'badge',
                                'badge-warning' => $retrait->statut === 'pending',
                                'badge-success' => $retrait->statut === 'validated',
                                'badge-danger'  => $retrait->statut === 'rejected',
                            ]) ?>><?= match($retrait->statut) {
                                'pending'   => 'En attente',
                                'validated' => 'Validée',
                                'rejected'  => 'Rejetée',
                            } ?></span>
                        <?php else: ?>
                            <h6 class="m-0"><a href="<?= link_to('admin.membre') . '?ref=' . $retrait->user->ref ?>"><?= $retrait->user->ref ?></a></h6>
                            <span><?= $retrait->user->user?->username ?></span>
                        <?php endif; ?></td>
                        <td><?= $tab === 'massive' ? $retrait->created_at->format('d/m/Y - H:i') : $retrait->compte ?></td>
                        <td><?= $retrait->ref ?></td>
                        <?php if ($tab === 'pending' || ($tab === 'massive' && $_user->can('admin.process-massive-withdrawal-request'))): ?>
                        <td> 
                            <div class="d-flex">
                                <form class="d-inline" method="post">
                                    <input type="hidden" name="action" value="validated" />
                                    <input type="hidden" name="ref" value="<?= $retrait->ref ?>" />
                                    <button type="submit" class="btn btn-sm btn-icon btn-success" data-toggle="tooltip" title="Approuver" <?= $this->disabled($retrait->statut !== 'pending') ?>><i class="fa fa-check-circle"></i></button>
                                </form>
                                <form class="d-inline" method="post">
                                    <input type="hidden" name="action" value="rejected" />
                                    <input type="hidden" name="ref" value="<?= $retrait->ref ?>" />
                                    <button type="submit" class="btn btn-sm btn-icon btn-danger" data-toggle="tooltip" title="Rejéter" <?= $this->disabled($retrait->statut !== 'pending') ?>><i class="fa fa-times-circle"></i></button>
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