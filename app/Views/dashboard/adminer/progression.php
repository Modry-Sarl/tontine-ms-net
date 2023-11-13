<?php $this->section('title', 'Fiche de progression'); ?>
<?php $this->extend('app') ?>

<?php $this->section('content') ?>

<div class="row">
	<div class="col-lg-8">
		<div class="card">
            <div class="card-header theme-bg">
                <h5 class=" text-white"><i class="fa fa-chart-line fa-fw"></i> Progression</h5>
            </div>
			<div class="card-body">
                <section class="col-sm-12 table-responsive">
					<table class="table table-striped table-condensed table-bordered">
						<thead>
							<tr class="active">
								<td>Niveau</td>
								<td>Statut </td>
								<td>Gains</td>
							</tr>
						</thead>
						<tbody>
                        <?php for ($i = 1; $i <= $iteration; $i++): ?>
                            <?php if (in_array($i, $niveaux, true)) {
                                $niveau = $_user->niveaux->first(fn($n) => $n->niveau == $i);
                                
                                $class = 'bg-success';
								$statut = '<h5>Validé</h5><span class="text-white">Le : '. $niveau->created_at .'</span>';
                                $gains =  '<h5>' . number_format(0.5 * \App\MS\Constants::GAINS_NIVEAU[$i], 0, '.', ' ') . ' $ Recu</h5><span class="text-white">Le : ' . $niveau->created_at . '</span>';
                            } else {
                                $class = 'bg-danger';
								$statut = '<h5>Non validé</h5>';
                                $gains =  '<h5>En attente de validation</h5>';
                            } ?>

                            <tr class="<?= $class ?>">
                                <td class="text-white-50"><?= $i ?></td>
                                <td><?= $statut ?></td>
                                <td><?= $gains ?></td>
                            </tr>
						<?php endfor; ?></tbody>
					</table>
				</section>
            </div>
		</div>
        <div class="card">
            <div class="card-header theme-bg">
                <h5 class=" text-white"><i class="fa fa-clipboard fa-fw"></i> Tableau des gains</h5>
            </div>
			<div class="card-body">
                <section class="col-sm-12 table-responsive">
					<table class="table table-striped table-condensed table-bordered">
						<thead>
							<tr class="active">
								<td>Niveau</td>
								<td>Nombre de filleuls requis </td>
								<td>Gains</td>
							</tr>
						</thead>
						<tbody>
                        <?php for ($i = 1; $i <= $iteration; $i++): ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td><?= number_format(\App\MS\Constants::nbrFilleulByNiveau($i), 0, '.', ' ') ?></td>
                                <td><?= number_format(\App\MS\Constants::GAINS_NIVEAU[$i], 0, '.', ' ') ?> $</td>
                            </tr>
						<?php endfor; ?>
                        </tbody>
					</table>
				</section>
            </div>
            <div class="card-body mt-0 pt-0"><div class="alert alert-warning">
				<p class="font-weight-bold">
					50% seront prélévés de tous vos gains pour le financement et la 
					maintenance du programme.
				</p>
				<p>
					Les gains des niveaux 5 et 10 sont récupérer par le système pour 
                    vous permettre de passer à la classe suivante.
				</p>
			</div></div>
		</div>
	</div>
	
	<div class="col-lg-4">
    <div class="card">
            <div class="card-header theme-bg">
                <h5 class=" text-white"><i class="fa fa-sort-numeric-down fa-fw"></i> Classes</h5>
            </div>
			<div class="card-body">
                <table class="w-100 table table-condensed table-bordered">
					<tbody>
                        <tr class="bg-success text-white"><td class="font-weight-bold h5">Argent</td></tr>
                        <tr class="<?= $_user->niveau >= \App\MS\Constants::BREAK_LEVEL[0] && $_user->niveau < \App\MS\Constants::BREAK_LEVEL[1] ? 'bg-success text-white' : '' ?>"><td class="font-weight-bold h5">Or</td></tr>
                        <tr class="<?= $_user->niveau >= \App\MS\Constants::BREAK_LEVEL[1] ? 'bg-success text-white' : '' ?>"><td class="font-weight-bold h5">Diamant</td></tr>
                    </tbody>
				</table>
			</div>
		</div>
        <div class="card">
            <div class="card-header theme-bg">
                <h5 class=" text-white"><i class="fa fa-sort-numeric-down fa-fw"></i> Etat de parainage</h5>
            </div>
			<div class="card-body">
                <table class="w-100 table table-striped table-condensed table-bordered">
					<thead>
						<tr class="active">
							<td>Niveau</td>
							<td>Nombre de filleuls </td>
						</tr>
					</thead>
					<tbody>
                        <?php for ($i = 1; $i <= $iteration; $i++): ?>
                        <tr>
                            <td><?= $i ?></td>
                            <td><?= number_format(count($_user->list_filleuls[$i]), 0, '.', ' ') . ' / ' . number_format(\App\MS\Constants::nbrFilleulByNiveau($i), 0, '.', ' ') ?></td>
                        </tr>
                        <?php endfor; ?>
                    </tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<?php $this->end() ?>