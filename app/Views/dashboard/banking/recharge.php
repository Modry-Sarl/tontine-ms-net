<?php $this->section('title', 'Rechargez votre compte'); ?>
<?php $this->extend('app') ?>

<?php $this->section('content') ?>

<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header theme-bg">
                <h5 class=" text-white"><i class="fa fa-exchange-alt fa-fw"></i> Démarrer la transaction</h5>
            </div>
            <form class="card-body"
                hx-post="<?= link_to('recharge') ?>" 
                hx-disable-element="#indicator"
                hx-target="#response"
                hx-swap="innerHTML"
                hx-select="#result"
            >
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label>Montant à recharger <small class="text-muted">(En Dollar)</small> <small class="text-danger">*</small></label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">$</span>
                                </div>
                                <input type="number" min="1" class="form-control" name="montant" value="<?= old('montant') ?>" />
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary">
                                        Démarrer
                                        <span class="ml-2 htmx-indicator"><i class="fa fa-spin fa-spinner"></i></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="response" class="mt-2">
                    <?= $this->insert('htmx-form-recharge') ?>
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