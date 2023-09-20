<?php $this->section('title', 'Faire un transfert MS'); ?>
<?php $this->extend('app') ?>

<?php $this->section('content') ?>

<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header theme-bg">
                <h5 class=" text-white"><i class="fa fa-exchange-alt fa-fw"></i> Démarrer la transaction</h5>
            </div>
            <form class="card-body"
                hx-post="<?= link_to('transfert') ?>" 
                hx-disable-element="#indicator"
                hx-target="#response"
                hx-swap="innerHTML"
                hx-select="#result"
            >
                <div class="form-group">
                    <label>Compte du transfert <small class="text-danger">*</small></label>
                    <select class="form-control" name="compte">
                        <option value="" selected disabled>-- Selectionnez le compte à partir du quel vous voullez éffectuer le transfert --</option>
                        <option value="principal">Compte principal - <?= $_user->solde_principal ?> $</option>
                        <option value="recharge">Compte recharge - <?= $_user->solde_recharge ?> $</option>
                    </select>
                </div>
                <div class="row">
                    <div class="col-12 col-lg-6">
                        <div class="form-group">
                            <label>Identifiant du beneficiaire <small class="text-danger">*</small></label>
                            <input type="text" name="ref" value="<?= old('ref') ?>" autocomplete="off" class="form-control" placeholder="Ex: MS125UJ2310" />
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="form-group">
                            <label>Compte visé <small class="text-danger">*</small></label>
                            <select class="form-control" name="cible">
                                <option value="" selected disabled>-- Selectionnez le compte dans lequel vous souhaitez faire le dépôt --</option>
                                <option value="principal">Compte principal</option>
                                <option value="recharge">Compte recharge</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Montant à transferer <small class="text-muted">(En Dollar)</small> <small class="text-danger">*</small></label>
                    <input type="number" name="montant" value="<?= old('montant') ?>" autocomplete="off" class="form-control" placeholder="Ex: 18" />
                </div>

                <hr>
                <div class="form-group">
                    <label>Mot de passe <small class="text-danger">*</small></label>
                    <input type="password" min="1" name="password" class="form-control" placeholder="Entrez votre mot de passe pour valider l'inscription" />
                </div>
                <div class="d-flex my-2">
                    <a href="<?= link_to('dashboard') ?>" class="ml-1 btn btn-danger">Annuler</a>
                    <button type="submit" class="mr-1 btn btn-primary">
                        Valider le transfert
                        <span class="ml-2 htmx-indicator"><i class="fa fa-spin fa-spinner"></i></span>
                    </button>
                </div>

                <div id="response" class="mt-2">
                    <?= $this->insert('htmx-form-transfert') ?>
                </div>
            </form>
        </div>
    </div>

    <div class="col-12 col-lg-4 mb-3">
        <?= $this->insert('components/informations-utiles') ?>

        <div class="card">
            <div class="card-header theme-bg">
                <h5 class=" text-white"><i class="fa fa-clock fa-fw"></i> Dernière recharge</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table m-b-0 mb-0">
                        <tbody>
                            <?php if (!empty($derniere_recharge)): ?>
                            <tr class="unread">
                                <td>
                                    <h5 class="mb-1"><span class="text-primary"><?= $derniere_recharge->montant ?>$</span> - <?= $derniere_recharge->numero ?></h5>
                                    <h6 class="mb-1"><?= $derniere_recharge->operateur ?> - <?= $derniere_recharge->operator_transaction_id ?></h6>
                                    <small class="m-0"><?= $derniere_recharge->created_at ?></small>
                                </td>
                            </tr>
                            <?php else: ?>
                            <tr class="unread">
                                <td>
                                    <div class="alert alert-info m-b-0 mb-0">
                                        Aucune recharge de compte éffectuée
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