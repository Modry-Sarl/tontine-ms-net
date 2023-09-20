<?php $this->section('title', 'Tableau de bord'); ?>
<?php $this->extend('app') ?>

<?php $this->section('content') ?>

<div class="row">
	<div class="col-lg-4 col-md-6">
		<div class="card card-primary border-primary">
			<div class="card-body">
				<div class="row">
					<div class="col-3">
						<i class="fa fa-coins fa-3x"></i>
					</div>
					<div class="col-9 text-right">
						<div class="d-flex align-items-center justify-content-end">
                            <div class="pr-2 border-right">
                                <span class="h3 font-weight-bold text-primary"><?= $_user->solde_principal ?> $</span>
                                <span class="d-inline-block w-100">Principal</span>
                            </div>
                            <div class="pl-2 text-left">
                                <span class="h3 font-weight-bold text-primary"><?= $_user->solde_recharge ?> $</span>
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
					<div class="col-3">
						<i class="fa fa-users fa-3x"></i>
					</div>
					<div class="col-9 text-right ">
                        <div class="d-flex align-items-center justify-content-end">
                            <div class="pr-2 border-right">
                                <span class="h3 font-weight-bold text-primary"><?= model('UserModel')->countFilleuls($_user->utilisateur) ?></span>
                                <span class="d-inline-block w-100">Filleuls</span>
                            </div>
                            <div class="pl-2 text-left">
                                <span class="h3 font-weight-bold text-primary"><?= $_user->inscriptions()->count() ?></span>
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
						<i class="fa fa-user-md fa-3x"></i>
					</div>
					<div class="col-9 text-right ">
                        <span class="h3 font-weight-bold text-primary"><?= count($comptes) ?></span>
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
                        <span class="h4 font-weight-bold text-primary truncate text-truncate"><?= ucfirst($_user->pack) ?></span>
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


<?php $this->end() ?>