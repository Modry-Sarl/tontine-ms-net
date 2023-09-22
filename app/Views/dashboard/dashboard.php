<?php $this->section('title', 'Tableau de bord'); ?>
<?php $this->extend('app') ?>

<?php $this->section('content') ?>

<div class="row">
	<div class="col-lg-4 col-md-6">
		<div class="card card-primary border-primary">
			<div class="card-body">
				<div class="row">
					<div class="col-2">
						<i class="fa fa-coins fa-2x"></i>
					</div>
					<div class="col-10 text-right">
						<div class="d-flex align-items-center justify-content-end">
                            <div class="pr-2 border-right">
                                <span class="h3 font-weight-bold text-warning"><?= $_user->solde_principal ?> $</span>
                                <span class="d-inline-block w-100">Principal</span>
                            </div>
                            <div class="pl-2 text-left">
                                <span class="h3 font-weight-bold text-warning"><?= $_user->solde_recharge ?> $</span>
                                <span class="d-inline-block w-100">Recharge</span>
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
			<div class="card-body">
				<div class="row">
					<div class="col-2">
						<i class="fa fa-users fa-2x"></i>
					</div>
					<div class="col-10 text-right ">
                        <div class="d-flex align-items-center justify-content-end">
                            <div class="pr-2 border-right">
                                <span class="h3 font-weight-bold text-warning"><?= model('UserModel')->countFilleuls($_user->utilisateur) ?></span>
                                <span class="d-inline-block w-100">Filleuls</span>
                            </div>
                            <div class="pl-2 text-left">
                                <span class="h3 font-weight-bold text-warning"><?= $_user->inscriptions()->count() ?></span>
                                <span class="d-inline-block w-100">Inscriptions</span>
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
    <div class="col-lg-2 col-md-6">
        <div class="card card-primary border-primary">
			<div class="card-body">
				<div class="row">
					<div class="col-3">
						<i class="fa fa-user-md fa-2x"></i>
					</div>
					<div class="col-9 text-right ">
                        <span class="h3 font-weight-bold text-warning"><?= count($comptes) ?></span>
                        <span class="d-inline-block w-100">Comptes</span>
                    </div>
				</div>
                <div class="progress m-t-30" style="height: 7px;">
                    <div class="progress-bar progress-c-theme w-100"></div>
                </div>
			</div>
		</div>
	</div>
    <div class="col-lg-2 col-md-6">
        <div class="card card-primary border-primary">
			<div class="card-body">
				<div class="row">
					<div class="col-3">
						<i class="fa fa-certificate fa-2x"></i>
					</div>
					<div class="col-9 text-right ">
                        <span class="h4 font-weight-bold text-warning truncate text-truncate"><?= ucfirst($_user->pack) ?></span>
                        <span class="d-inline-block w-100">Niveau <b class="font-weight-bold"><?= $_user->niveau ?></b></span>
                    </div>
				</div>
                <div class="progress m-t-30" style="height: 7px;">
                    <div class="progress-bar progress-c-theme w-100"></div>
                </div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-8">
        <div class="card">
            <div class="card-header theme-bg">
                <h5 class=" text-white"><i class="fa fa-star fa-fw"></i> <?= hello(); ?></h5>
            </div>
			<div class="card-body">
				Salut <?= $_user->username ?>, nous sommes heureux de vous compter parmis les nôtres.
				<br/>
				TONTINE MS-NET c'est simple, c'est facile et comme dab, faites comme chez-vous
				<br/>
				<span class="d-inline-block w-100 text-center mt-2">
					<i class="fa fa-smile fa-5x"></i>
				</span>
			</div>
		</div>

        <?php if (!empty($derniere_connexion)) : ?>
        <div class="card">
            <div class="card-header theme-bg">
                <h5 class=" text-white"><i class="fa fa-clock fa-fw"></i> Derniere connexion</h5>
            </div>
			<div class="card-body">
                Votre dernière connexion au système possède les caracteristiques suivantes :
                <br/>
                <ul class="list-group list-group-flush mt-5">
                    <li class="px-0 list-group-item d-flex"><span class="mb-0 mr-2">Date</span> <strong class="font-weight-bold text-muted"><?= $derniere_connexion->date ?></strong></li>
                    <li class="px-0 list-group-item d-flex"><span class="mb-0 mr-2">Adresse IP</span> <strong class="font-weight-bold text-muted"><?= $derniere_connexion->ip_address ?></strong></li>
                    <li class="px-0 list-group-item d-flex"><span class="mb-0 mr-2">Element d'authentification</span> <strong class="font-weight-bold text-muted"><?= $derniere_connexion->identifier ?></strong></li>
                    <li class="px-0 list-group-item d-flex"><span class="mb-0 mr-2">Equipement</span> <strong class="font-weight-bold text-muted"></strong></li>
                    <li class="px-0 list-group-item d-flex"><span class="mb-0 mr-2">Navigateur</span> <strong class="font-weight-bold text-muted"></strong></li>
                </ul>
			</div>
		</div>
        <?php endif; ?>
	</div>
	
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header theme-bg">
                <h5 class=" text-white"><i class="fa fa-bell fa-fw"></i> Notifications</h5>
            </div>
			<div class="card-body">
                <?php if (1 > $_user->utilisateur->notifications->count()): ?>
				<p class="text-center">
                    Aucune nouvelle notification pour le moment.
                    <br/>
                    Vous serez informé lorsque des notifications seront diponible.
                </p>
                <?php else: ?>
                    <div class="list-group">
                    <?php foreach ($_user->utilisateur->notifications as $notification): ?>
                        <a class="list-group-item" href=""><p>
                            <i class="fa fa-fw <?= get_notification_icon($notification->type) ?>"></i>
                            <span style="font-size:1.1em; font-family:\'Lato\',\'Arial narrow\',candara"><?= $notification->libelle ?></span>
                            <br/>
                            <span class="float-right text-muted small"><em><?= $notification->created_at->format('d/m/Y') ?></em></span>
                        </p></a>
					<?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <?php if ($total_notifications > 0): ?>
                <a href="<?= link_to('notifications') ?>" class="btn btn-primary mt-4 btn-block">Voir toutes les  <b><?= $total_notifications ?></b> notifications</a>
                <?php endif; ?>
			</div>
		</div>
    </div>
</div>

<?php $this->end() ?>