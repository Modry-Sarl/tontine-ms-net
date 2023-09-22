<?php $this->section('title', 'Comptes'); ?>
<?php $this->extend('app') ?>

<?php $this->section('content') ?>

<div class="row flex-column-reverse flex-lg-row">
	<div class="col-lg-8">
		<div class="card">
            <div class="card-header theme-bg">
                <h5 class=" text-white"><i class="fa fa-star fa-fw"></i> Comptes asscociés</h5>
            </div>
			<div class="card-body">
                <section class="col-lg-12 table-responsive">
                    <table class="text-center table table-striped table-condensed">	
                        <thead>
                            <tr class="active">
                                <td width="7%" style="border:none"></td>
                                <td width="20%">Profil</td>
                                <td width="23%">Nombre de filleuls</td>
                                <td width="23%">Solde</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($comptes as $compte): ?>
                            <tr valign="middle">
                                <td><img class="rounded border img-thumbnail" src="<?= $compte->user->avatar ?>" alt="" style="width:2em; height:2em;"/></td>
                                <td><h4 class="m-0"><?= $compte->ref ?></h4></td>
                                <td>
                                    <?= scl_splitInt($compte->nbr_filleuls) ?> / <?= scl_splitInt(\App\MS\Constants::TOTAL_FILLEUL); ?> 
                                    <br> Niveau <?= $compte->niveau . ' / ' . \App\MS\Constants::NBR_NIVEAU ?>
                                </td>
                                <td>
                                    <span>Principal : <b class="font-weight-bold"><?= number_format($compte->solde_principal, 0, '.', ' ') ?> $</b></span>
                                    <br />
                                    <span>Recharge : <b class="font-weight-bold"><?= number_format($compte->solde_recharge, 0, '.', ' ') ?> $</b></span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </section>
            </div>
		</div>
	</div>
	
	<div class="col-lg-4">
        <div class="card">
            <div class="card-header theme-bg">
                <h5 class=" text-white"><i class="fa fa-id-card-alt fa-fw"></i> Informations générales</h5>
            </div>
			<div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="px-0 list-group-item d-flex justify-content-between">
                        <span class="mb-0">Crée le</span> 
                        <strong class="font-weight-bold text-dark"><?= $_user->created_at->format('l, d M Y à H:i'); ?></strong>
                    </li>
                    <li class="px-0 list-group-item d-flex justify-content-between">
                        <span class="mb-0">Nombre de comptes</span> 
                        <strong class="font-weight-bold text-dark"><?= count($comptes); ?></strong>
                    </li>
                    <li class="px-0 list-group-item d-flex justify-content-between">
                        <span class="mb-0">Solde total (solde principal)</span> 
                        <strong class="font-weight-bold text-dark"><?= number_format($comptes->sum(fn($compte) => $compte->solde_principal), 0, '.', ' ') ?> $</strong>
                    </li>
                    <li class="px-0 list-group-item d-flex justify-content-between">
                        <span class="mb-0">Solde total (solde recharge)</span> 
                        <strong class="font-weight-bold text-dark"><?= number_format($comptes->sum(fn($compte) => $compte->solde_recharge), 0, '.', ' ') ?> $</strong>
                    </li>
                    <li class="px-0 list-group-item d-flex justify-content-between">
                        <span class="mb-0">Solde total cumulé</span> 
                        <strong class="font-weight-bold text-dark"><?= number_format($comptes->sum(fn($compte) => $compte->solde_principal + $compte->solde_recharge), 0, '.', ' ') ?> $</strong>
                    </li>
                </ul>
			</div>
		</div>
	</div>
</div>

<?php $this->end() ?>