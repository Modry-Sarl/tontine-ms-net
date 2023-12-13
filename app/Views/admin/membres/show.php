<?php $this->section('title', 'Informations du membre'); ?>
<?php $this->extend('admin') ?>

<?php $this->section('content') ?>

<div class="row">
	<div class="col-lg-8">
		<div class="card card-green">
			<div class="card-body" id="infos_membre">
				<form class="form-group" action="" method="get">
                    <label>ID du membre</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-user-alt"></i></span>
                        </div>
                        <input type="text" name="ref" class="form-control" required value="<?= $ref ??''; ?>"/>
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">Valider</button>
                        </div>
                    </div>
				</form>
			</div>
		</div>
	</div>
	<div class="col-lg-4">
		<div class="card" id="find_member">
            <div class="card-header p-2 d-flex justify-content-between align-items-center theme-bg">
                <h5 class=" text-white"><i class="fa fa-search fa-fw"></i>  Recherche</h5>
                <div class="btn-group">
                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                        Trier
                        <span class="caret"></span>
                    </button>								
                    <ul class="dropdown-menu pull-right radio-sort" role="menu" id="sortFindMember">
                        <li><a><label for="sortFindMember1"><input type="radio" id="sortFindMember1" name="sortFindMember" value="tel"> &nbsp; Par le numero de telephone</label></a></li>
                        <li><a><label for="sortFindMember2"><input type="radio" id="sortFindMember2" name="sortFindMember" value="pseudo"> &nbsp; Par le pseudonyme</label></a></li>
                    </ul>
                </div>
            </div>
			<div class="card-body px-3 pt-3 pb-2">
				<div class="form-group input-group">
					<input type="text" class="form-control" placeholder="Recherche">
					<span class="input-group-btn">
						<button class="btn btn-default" type="button"><i class="fa fa-search"></i></button>
					</span>
				</div>
			</div>
		</div>
	</div>
</div>

<?php if (!empty($ref)): ?><div class="row">
    <div class="col-lg-12">
        <?php if (empty($user)): ?>
            <div class="alert alert-danger">Identifiant inexistant</div>
        <?php else: ?>
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-pills" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a role="tab" <?= $this->class(['nav-link text-uppercase', 'active' => $tab == 'generalites']) ?> href="<?= current_url(true)->addQuery('tab', 'generalites') ?>">Généralités</a>
                        </li>
                        <li class="nav-item">
                            <a role="tab" <?= $this->class(['nav-link text-uppercase', 'active' => $tab == 'transactions']) ?> href="<?= current_url(true)->addQuery('tab', 'transactions') ?>">Transactions</a>
                        </li>
                        <li class="nav-item">
                            <a role="tab" <?= $this->class(['nav-link text-uppercase', 'active' => $tab == 'inscriptions']) ?> href="<?= current_url(true)->addQuery('tab', 'inscriptions') ?>">Inscriptions</a>
                        </li>
                        <li class="nav-item">
                            <a role="tab" <?= $this->class(['nav-link text-uppercase', 'active' => $tab == 'activites']) ?> href="<?= current_url(true)->addQuery('tab', 'activites') ?>">Activités</a>
                        </li>
                        <li class="nav-item">
                            <a role="tab" class="nav-link text-uppercase" href="<?= link_to('admin.membre.config') . '?ref=' . $ref; ?>">Configurer</a>
                        </li>
                    </ul>
                </div>

                <?= $this->includeIf('show.' . $tab) ?>
            </div>
        <?php endif; ?>
    </div>
</div><?php endif; ?>

<?php $this->end() ?>

