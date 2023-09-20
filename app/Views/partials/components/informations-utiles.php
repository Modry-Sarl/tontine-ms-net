<div class="card">
    <div class="card-header theme-bg">
        <h5 class=" text-white"><i class="fa fa fa-clipboard fa-fw"></i> Informations utiles</h5>
    </div>
    <div class="card-body">
        <h3 class="mt-0 mb-3 text-center"><?= $_user->ref ?> : <span class="text-truncate truncate"><?= $_user->username ?></span></h3>
        <ul class="list-group list-group-flush">
            <li class="px-0 list-group-item d-flex justify-content-between"><span class="mb-0">Numero de compte</span> <strong class="font-weight-bold text-muted">159868101111</strong></li>
            <li class="px-0 list-group-item d-flex justify-content-between"><span class="mb-0">Solde principal</span> <strong class="font-weight-bold text-muted"><?= $_user->solde_principal ?> $</strong></li>
            <li class="px-0 list-group-item d-flex justify-content-between"><span class="mb-0">Solde recharge</span> <strong class="font-weight-bold text-muted"><?= $_user->solde_recharge ?> $</strong></li>
        </ul>
    </div>
</div>