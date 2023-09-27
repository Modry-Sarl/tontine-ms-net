<div id="tree">
	<ul class="arbre niv_0 mt-0 justify-content-center">
        <?php $click = ($user->ref != $_user->ref) ? 'onclick="loadTree(\''.$user->parrain.'\');"' : ''; ?>
        <li class="content_li p-1" <?= $click ?>>
            <img class="rounded-circle m-r-10" style="width:40px;" src="<?= $user->user->avatar ?>" alt="" />
            <div>
                <h5 class="m-0 font-weight-bold"><?= $user->ref ?></h5>
                <h6 class="m-0 "><?= scl_splitInt($user->user->tel, 2) ?></h6>
                <h6 class="m-0 small text-muted"><?= $user->user->username ?></h6>
            </div>
        </li>
    </ul>

    <ul class="arbre niv_1 content_ul"><?php displayTree($filleuls[1]) ?></ul>	

    <ul class="arbre niv_2">
    <?php for ($k = 0; $k < 2; $k++): ?>
        <li><ul class="content_ul"><?php displayTree($filleuls[2]) ?></ul></li>
    <?php endfor; ?>
    </ul>

    <ul class="arbre niv_3">
    <?php for ($k = 0; $k < 4; $k++): ?>
        <li><ul class="content_ul"><?php displayTree($filleuls[3]) ?></ul></li>
    <?php endfor; ?>
    </ul>
</div>

<?php function displayTree(array $filleuls) {
    for ($i = 0; $i < 2; $i++) {
        $filleul = $filleuls[$i] ?? null;
        if (!empty($filleul)) {
            $contenu  = 'plein';
            $ref      = $filleul->ref;
            $username = ucwords($filleul->user->username);
            $tel      = scl_splitInt($filleul->user->tel, 2);
            $click    = 'onclick="loadTree(\''.$ref.'\');"';
            $avatar   = $filleul->user->avatar ;
        } else {
            $contenu  = 'vide';
            $ref      = 'Indisponible';
            $username = '';
            $tel      = '';
            $click    = '';
            $avatar   = '';
        }
        ?> 
        <li class="content_li p-1 <?= $contenu ?>" <?= $click ?>>
            <img class="rounded-circle m-r-10" style="width:40px;" src="<?= $avatar ?>" alt="" />
            <div>
                <h5 class="m-0 font-weight-bold"><?= $ref ?></h5>
                <h6 class="m-0 "><?= $tel ?></h6>
                <h6 class="m-0 small text-muted"><?= $username ?></h6>
            </div>
        </li>       
        <?php 
    }
} ?>
