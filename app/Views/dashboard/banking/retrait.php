<?php $this->section('title', 'Effectuez un retrait'); ?>
<?php $this->extend('app') ?>

<?php $this->section('content') ?>

<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header theme-bg">
                <h5 class=" text-white"><i class="fa fa-exchange-alt fa-fw"></i> Démarrer la transaction</h5>
            </div>
            <form class="card-body"
                hx-post="<?= link_to('retrait') ?>" 
                hx-disable-element="#indicator"
                hx-target="#response"
                hx-swap="innerHTML"
                hx-select="#result"
            >
                <div class="form-group">
                    <label>Compte du retrait <small class="text-danger">*</small></label>
                    <select class="form-control" name="compte">
                        <option value="" selected disabled>-- Selectionnez le compte à partir du quel vous voullez éffectuer le retrait --</option>
                        <option value="principal">Compte principal - <?= $_user->solde_principal ?> $</option>
                        <option value="recharge">Compte recharge - <?= $_user->solde_recharge ?> $</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Montant à retirer <small class="text-muted">(En Dollar)</small> <small class="text-danger">*</small></label>
                    <input type="number" name="montant" value="<?= old('montant') ?>" autocomplete="off" class="form-control" placeholder="Ex: 18" />
                </div>
                <div class="form-group">
                    <label>Numéro de bénéficiaire <small class="text-danger">*</small></label>
                    <input type="tel" name="tel" value="<?= old('tel', simple_tel($_user->tel)) ?>" autocomplete="off" class="form-control" placeholder="Ex: 677889900" />
                    <small class="form-text text-muted">Au format local (sans indicatif)</small>
                </div>
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="eum" class="custom-control-input" id="customCheck1">
                        <label class="custom-control-label" for="customCheck1">Envoyer par EU Mobile Money</label>
                    </div>
                </div>
                <hr>
                <div class="form-group">
                    <label>Mot de passe <small class="text-danger">*</small></label>
                    <input type="password" min="1" name="password" class="form-control" placeholder="Entrez votre mot de passe pour valider l'inscription" />
                </div>
                <div class="d-flex my-2">
                    <a href="<?= link_to('dashboard') ?>" class="ml-1 btn btn-danger">Annuler</a>
                    <button type="submit" class="mr-1 btn btn-primary">
                        Valider le retrait
                        <span class="ml-2 htmx-indicator"><i class="fa fa-spin fa-spinner"></i></span>
                    </button>
                </div>

                <div id="response" class="mt-1">
                    <?= $this->insert('htmx-form-retrait') ?>
                </div>
            </form>
        </div>
    </div>

    <div class="col-12 col-lg-4 mb-3">
        <?= $this->insert('components/informations-utiles') ?>

        <div class="card">
            <div class="card-header theme-bg">
                <h5 class=" text-white"><i class="fa fa-clock fa-fw"></i> Dernier retrait</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table m-b-0 mb-0">
                        <tbody>
                            <?php if (!empty($dernier_retrait)): ?>
                                <tr class="unread">
                                <td>
                                    <h5 class="mb-1"><span class="text-primary"><?= $dernier_retrait->montant ?>$</span> - <?= $dernier_retrait->numero ?></h5>
                                    <h6 class="mb-1"><?= $dernier_retrait->operateur ?> - <?= $dernier_retrait->operator_transaction_id ?></h6>
                                    <small class="m-0"><?= $dernier_retrait->created_at ?></small>
                                </td>
                            </tr>
                            <?php else: ?>
                            <tr class="unread">
                                <td>
                                    <div class="alert alert-info m-b-0 mb-0">
                                        Aucun retrait éffectuée
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