<?php $this->section('title', 'Liste de filleul'); ?>
<?php $this->extend('app') ?>


<?php $this->section('content') ?>

<div class="modal fade" id="modal-arbre-filleul" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Arbre de filleul</h5>
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" style="min-height: 10em"></div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-12">
		<div class="card">
            <div class="card-header theme-bg d-flex align-items-center justify-content-between">
                <h5 class=" text-white"><i class="fa fa-chart-line fa-fw"></i> Progression</h5>
				<div class="btn-group btn-group-sm">
					<button type="button" class="btn btn-secondary" onclick="showTree('<?= $_user->ref ?>');">Arbre de filleul</button>
					<button class="btn btn-light dropdown-toggle" type="button" data-toggle="dropdown">Trier</button>
					<div class="dropdown-menu dropdown-menu-right">
						<a class="dropdown-item" href="#!" onclick="sortListFilleuls('tous')"><label for="sort_all"><input type="radio" id="sort_all" name="sortListFilleuls" checked="checked"> &nbsp; Tous les niveaux</label></a>
						<div class="dropdown-divider"></div>
						<?php for ($i = 1; $i <= $iteration; $i++): ?>
							<a class="dropdown-item" href="#!" onclick="sortListFilleuls(<?= $i ?>)"><label for="sort_<?= $i ?>"><input type="radio" id="sort_<?= $i ?>" name="sortListFilleuls"> &nbsp; Niveau <?= $i ?></label></a>
						<?php endfor ?>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="#!" onclick="sortListFilleuls('paire')"><label for="sort_paire"><input type="radio" id="sort_paire" name="sortListFilleuls"> &nbsp; Niveaux paires</label></a>
						<a class="dropdown-item" href="#!" onclick="sortListFilleuls('impaire')"><label for="sort_impaire"><input type="radio" id="sort_impaire" name="sortListFilleuls"> &nbsp; Niveaux impaires</label></a>
					</div>
				</div>
            </div>
			<div class="card-body">
			<?php for ($i = 1; $i <= $iteration; $i++): ?>
				<section class="w-100 table-responsive mb-4" ref="liste-filleul-<?= $i; ?>">
				<table class="table border table-hover">
					<thead class="bg-light">
						<tr>
							<th colspan="5">
								<h6 class="m-0 text-center">
									Niveau <?= $i ?>
									<br />
									<span style="font-size:.8em"><?= count($filleuls[$i]) ?> / <?= \App\MS\Constants::nbrFilleulByNiveau($i) ?> filleuls</span>
								</h6>
							</th>
						</tr>
						<tr>
							<th>Profil</th>
							<th>Téléphone</th>
							<th>Progression</th>
							<th>Date d'inscription</th>
							<th class="text-right"></th>
						</tr>
					</thead>
					<tbody>
						<?php if (empty($filleuls[$i])): ?>
						<tr><td colspan="5" class="text-center"><br/> Vous n'avez encore aucun filleul à ce niveau <br/></td></tr>
						<?php else: foreach ($filleuls[$i] as $filleul): ?>
						<tr>
							<td>
								<h6 class="m-0">
									<img class="rounded-circle m-r-10" style="width:40px;" src="<?= $filleul->user->avatar ?>" alt="" />
									<?= ucwords($filleul->user->username) ?>
								</h6>
							</td>
							<td><h6 class="m-0"><?= scl_splitInt($filleul->user->tel, 2) ?></h6></td>
							<td>
								<h6 class="m-0"><small>Niveau:</small> <?= $filleul->niveau ?></h6>
								<h6 class="m-0"><small>Filleuls:</small> <?= $filleul->nbr_filleuls ?></h6>
							</td>
							<td><h6 class="m-0"><?= $filleul->user->created_at ?></h6></td>
							<td class="text-right">
								<div class="btn-group btn-group-sm">
									<button class="btn btn-light" type="button" onclick="showTree('<?= $filleul->ref ?>');" title="Arbre de filleul"><i class="fa fa-sitemap"></i></button>
									<a class="btn btn-light" href="<?= link_to('details-filleul', $filleul->id) ?>" title="Details"><i class="fa fa-eye"></i></a>
								</div>
							</td>
						</tr>
						<?php endforeach; endif; ?>
					</tbody>
                </table>
				</section>
			<?php endfor ?>
            </div>
		</div>
	</div>
</div>

<?php $this->end() ?>

<?php $this->section('styles') ?>
<link rel="stylesheet" href="<?= css_url('arbre-filleuls') ?>" />
<?php $this->end() ?>

<?php $this->section('scripts') ?>
<script>	
function showTree(ref) {
	$('#modal-arbre-filleul').modal('show');
	loadTree(ref)
}
function loadTree(ref) {
	$('#modal-arbre-filleul .modal-body')
		.addClass('d-flex justify-content-center align-items-center')
		.html('<i class="fa fa-spin fa-circle-notch fa-2x"></i>')
		.delay(2e3)
		.load('<?= link_to('arbre-filleul') ?>', { ref }, function() {
			$('#modal-arbre-filleul .modal-body').css('min-height', '80vh');
		});
}

function sortListFilleuls(e) {
    return "impaire" == (e = e && void 0 !== e ? e : "tous") ? ($('section[ref^="liste-filleul"]:even').show("slow"), void $('section[ref^="liste-filleul"]:odd').hide("slow")) : "paire" == e ? ($('section[ref^="liste-filleul"]:even').hide("slow"), void $('section[ref^="liste-filleul"]:odd').show("slow")) : -1 != $.inArray(parseInt(e), [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15]) ? ($('section[ref^="liste-filleul"]:not(:eq(' + (e - 1) + "))").hide("slow"), void $('section[ref^="liste-filleul"]:eq(' + (e - 1) + ")").show("slow")) : void $('section[ref^="liste-filleul"]').show("slow")
}
</script>
<?php $this->end() ?>