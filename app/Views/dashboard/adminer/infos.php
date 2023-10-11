<?php $this->section('title', 'Informations utiles'); ?>
<?php $this->extend('app') ?>

<?php $this->section('content') ?>

<div class="row">
	<div class="col-lg-8">
		<div class="card">
            <div class="card-header theme-bg">
                <h5 class=" text-white"><i class="fa fa-id-card-alt fa-fw"></i> Informations générales</h5>
            </div>
			<div class="card-body">
				<section class="col-sm-12 table-responsive">
					<table class="table table-striped table-condensed">
						<tbody><tr>
							<td><img class="rounded border p-1" src="<?= $_user->avatar; ?>" style="width:5em;height:5em;"/></td>
							<td>
								<h3 class="mt-0"><?= $_user->ref; ?></h3>
								<dl class="row">
									<dt class="col-sm-5">Pseudonyme</dt>
									<dd class="col-sm-7 font-weight-bold text-dark"><?= $_user->username; ?></dd>
								</dl>
								<dl class="row">
									<dt class="col-sm-5">Téléphone</dt>
									<dd class="col-sm-7 font-weight-bold text-dark"><?= scl_splitInt($_user->tel, 2); ?></dd>
								</dl>
								<dl class="row">
									<dt class="col-sm-5">Date d'inscription</dt>
									<dd class="col-sm-7 font-weight-bold text-dark"><?= $_user->created_at->format('l, d F Y H:i') ?></dd>
								</dl>
							</td>
						</tr></tbody>
					</table>
				</section>
			</div>
		</div>
	</div>
	
	<div class="col-lg-4">
		<div class="card">
            <div class="card-header theme-bg">
                <h5 class=" text-white"><i class="fa fa-chart-bar fa-fw"></i> Progression</h5>
            </div>
			<div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="px-0 list-group-item d-flex justify-content-between">
                        <span class="mb-0">Classe</span> 
                        <strong class="font-weight-bold text-dark"><?= ucfirst($_user->pack); ?></strong>
                    </li>
                    <li class="px-0 list-group-item d-flex justify-content-between">
                        <span class="mb-0">Niveau actuel</span> 
                        <strong class="font-weight-bold text-dark"><?= $_user->niveau. ' / ' . \App\MS\Constants::NBR_NIVEAU; ?></strong>
                    </li>
                    <li class="px-0 list-group-item d-flex justify-content-between">
                        <span class="mb-0">Nombre de descendants</span> 
                        <strong class="font-weight-bold text-dark"><?= scl_splitInt($_user->utilisateur->nbr_filleuls) ?></strong>
                    </li>
                    <li class="px-0 list-group-item d-flex justify-content-between">
                        <span class="mb-0">Gains cumulés</span> 
                        <strong class="font-weight-bold text-dark"><?= number_format($_user->gains, 0, '.', ' ') ?> $</strong>
                    </li>
                </ul>
			</div>
		</div>
	</div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header theme-bg">
                <h5 class=" text-white"><i class="fa fa-users fa-fw"></i> Informations sur votre famille MS-NET</h5>
            </div>
			<div class="card-body">
				<section class="col-sm-12 table-responsive">
					<table class="table table-striped table-condensed">
						<tbody>
							<tr class="active"><td colspan="2"><h4>Parrain</h4></td></tr>
							<tr>
                                <?php if (empty($_user->utilisateur->parrain)): ?>
                                <td colspan="2">Vous etes au sommet de la pyramide MS-NET<br/><br/></td>
                                <?php else: ?>
								<td><img class="border rounded p-1" src="<?= $_user->referer->user->avatar ?>" style="width:2em;height:2em;" /></td>
								<td>
									<h4 style="margin-top:0"><?= $_user->referer->ref; ?></h4>
									<dl class="row">
                                    <dt class="col-sm-4">Téléphone</dt>
                                        <dd class="col-sm-8 font-weight-bold text-dark"><?= scl_splitInt($_user->referer->user->tel, 2) ?></dd>

                                        <dt class="col-sm-4 mt-2">Pseudonyme</dt>
										<dd class="col-sm-8 mt-2 font-weight-bold text-dark"><?= $_user->referer->user->username; ?></dd>
										
										<dt class="col-sm-4 mt-2">Niveau</dt>
										<dd class="col-sm-8 mt-2 font-weight-bold text-dark"><?= $_user->referer->niveau . ' &nbsp; ('. $_user->referer->nbr_filleuls .' filleuls)'; ?></dd>
									</dl>
								</td>
								<?php endif; ?>
                            </tr>
							<tr class="active"><td colspan="2"><h4>Filleuls directs</h4></td></tr>
                            <?php if (1 > $_user->utilisateur->filleuls->count()): ?>
                            <tr><td colspan="2">Vous n'avez aucun filleuls pour le moment<br/><br/></td></tr>
                            <?php else: foreach($_user->utilisateur->filleuls as $filleul): ?>
                            <tr>
                                <td><img class="border rounded p-1" src="<?= $filleul->user->avatar ?>" style="width:2em;height:2em;" /></td>
                                <td>
                                    <h4 style="margin-top:0"><?= $filleul->ref; ?></h4>
                                    <dl class="row">
                                        <dt class="col-sm-4">Téléphone</dt>
                                        <dd class="col-sm-8 font-weight-bold text-dark"><?= scl_splitInt($filleul->user->tel, 2) ?></dd>

                                        <dt class="col-sm-4 mt-2">Pseudonyme</dt>
                                        <dd class="col-sm-8 mt-2 font-weight-bold text-dark"><?= $filleul->user->username; ?></dd>
                                        
                                        <dt class="col-sm-4 mt-2">Niveau</dt>
                                        <dd class="col-sm-8 mt-2 font-weight-bold text-dark"><?= $filleul->niveau . ' &nbsp; ('. $filleul->nbr_filleuls .' filleuls)'; ?></dd>
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
                <h5 class=" text-white"><i class="fa fa-dollar-sign fa-fw"></i> Soldes</h5>
            </div>
			<div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="px-0 list-group-item d-flex justify-content-between"><span class="mb-0">Solde principal</span> <strong class="font-weight-bold text-dark"><?= number_format($_user->solde_principal, 0, '.', ' ') ?> $</strong></li>
                    <li class="px-0 list-group-item d-flex justify-content-between"><span class="mb-0">Solde recharge</span> <strong class="font-weight-bold text-dark"><?= number_format($_user->solde_recharge, 0, '.', ' ') ?> $</strong></li>
                </ul>
			</div>
		</div>
		<div class="card">
            <div class="card-header theme-bg">
                <h5 class=" text-white"><i class="fa fa-user-tie fa-fw"></i> Informations personnelles</h5>
            </div>
			<div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="px-0 list-group-item d-flex justify-content-between">
                        <span class="mb-0">Nom</span>
                        <strong class="font-weight-bold text-dark"><?= $_user->nom ?: '<small class="font-italic small text-small">Non renseigné</small>' ?></strong>
                    </li>
                    <li class="px-0 list-group-item d-flex justify-content-between">
                        <span class="mb-0">Prenom</span>
                        <strong class="font-weight-bold text-dark"><?= $_user->prenom ?: '<small class="font-italic small text-small">Non renseigné</small>' ?></strong>
                    </li>
                    <li class="px-0 list-group-item d-flex justify-content-between">
                        <span class="mb-0">Email</span>
                        <strong class="font-weight-bold text-dark"><?= $_user->email ?: '<small class="font-italic small text-small">Non renseigné</small>' ?></strong>
                    </li>
                    <li class="px-0 list-group-item d-flex justify-content-between">
                        <span class="mb-0">Sexe</span>
                        <strong class="font-weight-bold text-dark"><?= $_user->sexe ?: '<small class="font-italic small text-small">Non renseigné</small>' ?></strong>
                    </li>
                    <li class="px-0 list-group-item d-flex justify-content-between">
                        <span class="mb-0">Date de naissance</span>
                        <strong class="font-weight-bold text-dark"><?= $_user->date_naiss ?: '<small class="font-italic small text-small">Non renseigné</small>' ?></strong>
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
                        <strong class="font-weight-bold text-dark"><?= $_user->pays ?: '<small class="font-italic small text-small">Non renseigné</small>' ?></strong>
                    </li>
                    <li class="px-0 list-group-item d-flex justify-content-between">
                        <span class="mb-0">Ville</span>
                        <strong class="font-weight-bold text-dark"><?= $_user->ville ?: '<small class="font-italic small text-small">Non renseigné</small>' ?></strong>
                    </li>
                    <li class="px-0 list-group-item d-flex justify-content-between">
                        <span class="mb-0">Quartier</span>
                        <strong class="font-weight-bold text-dark"><?= $_user->quartier ?: '<small class="font-italic small text-small">Non renseigné</small>' ?></strong>
                    </li>
                </ul>
			</div>
		</div>
	</div>
</div>

<?php $this->end() ?>