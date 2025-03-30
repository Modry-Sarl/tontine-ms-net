<?php $this->section('title', 'Retraits en masse'); ?>
<?php $this->extend('admin') ?>

<?php $this->section('content') ?>

<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header theme-bg">
                <h5 class=" text-white"><i class="fa fa-exchange-alt fa-fw"></i> Initialisation des retraits</h5>
            </div>
            <form class="card-body"
                hx-post="<?= link_to('admin.transactions.retraits') ?>" 
                hx-disable-element="#indicator"
                hx-target="#response"
                hx-swap="innerHTML"
                hx-select="#result"
            >
                <?php if (empty($compteRetrait)): ?>
                    <div class="alert alert-danger">
                        Retraits en masse non disponible pour le moment
                    </div>
                <?php endif; ?>
                <?php for ($i = 0; $i < 10; $i++): ?><div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label>Numéro de bénéficiaire <?php if ($i == 0): ?><small class="text-danger">*</small><?php endif ?></label>
                            <input type="tel" name="tel[<?= $i ?>]" autocomplete="off" class="form-control" placeholder="Ex: 677889900" <?= $this->required($i == 0) ?> />
                            <small class="form-text text-muted">Au format local (sans indicatif)</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label>Montant à retirer <small class="text-muted">(En Dollar)</small> <?php if ($i == 0): ?><small class="text-danger">*</small><?php endif ?></label>
                            <input type="number" name="montant[<?= $i ?>]" min="1" autocomplete="off" class="form-control" placeholder="Ex: 18" <?= $this->required($i == 0) ?> />
                        </div>
                    </div>
                </div><?php endfor; ?>
                
                <hr>
                <div class="form-group">
                    <label>Mot de passe <small class="text-danger">*</small></label>
                    <input type="password" min="1" name="password" class="form-control" placeholder="Entrez votre mot de passe pour valider l'inscription" />
                </div>
                <div class="d-flex my-2">
                    <a href="<?= link_to('admin.dashboard') ?>" class="ml-1 btn btn-danger">Annuler</a>
                    <button type="submit" class="mr-1 btn btn-primary">
                        Valider l'initialisation
                        <span class="ml-2 htmx-indicator"><i class="fa fa-spin fa-spinner"></i></span>
                    </button>
                </div>

                <div id="response" class="mt-1">
                    <?= $this->insert('App\Views\admin\htmx-form-response', [
                        'message'     => 'Retraits en masse initialisés avec succès',
                        'redirection' => link_to('admin.transactions.retraits')
                    ]) ?>
                </div>
            </form>
        </div>
    </div>

    <div class="col-12 col-lg-4 mb-3">
        <div class="card">
            <div class="card-header theme-bg">
                <h5 class=" text-white"><i class="fa fa-dollar-sign fa-fw"></i> Solde du compte des retraits</h5>
            </div>
            <div class="card-body">
                <?php if (empty($compteRetrait)): ?>
                    <div class="alert alert-danger">Retraits en masse non disponible pour le moment</div>
                <?php else: ?>
                    <h3 class="mt-0 mb-3 text-center"><?= $compteRetrait->solde_recharge ?> $</h3>
                    <ul class="list-group list-group-flush">
                        <li class="px-0 list-group-item d-flex justify-content-between"><span class="mb-0">Numero de compte</span> <strong class="font-weight-bold text-muted"><?= $compteRetrait->user->num_compte ?></strong></li>
                    </ul>
                <?php endif; ?>
           </div>
        </div>

        <div class="card">
            <div class="card-header theme-bg">
                <h5 class=" text-white"><i class="fa fa-clock fa-fw"></i> Dernières demandes</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table m-b-0 mb-0">
                        <tbody>
                            <?php if (!empty($dernieres_demandes)): ?>
                                <?php foreach ($dernieres_demandes as $demande): ?>
                                <tr class="unread">
                                    <td><?= scl_splitInt($demande->tel, 2) ?></td>
                                    <td><b><?= scl_splitInt($demande->montant) ?> $</b></td>
                                    <td><span <?= $this->class([
                                        'badge',
                                        'badge-warning' => $demande->statut === 'pending',
                                        'badge-success' => $demande->statut === 'validated',
                                        'badge-danger'  => $demande->statut === 'rejected',
                                    ]) ?>><?= match($demande->statut) {
                                       'pending'   => 'En attente',
                                       'validated' => 'Validée',
                                       'rejected'  => 'Rejetée',
                                    } ?></span></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                            <tr class="unread">
                                <td>
                                    <div class="alert alert-info m-b-0 mb-0">
                                        Aucune demande de retrait éffectuée
                                    </div>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
           </div>
        </div>
    </div>
</div>

<?php $this->end() ?>