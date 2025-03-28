<nav class="pcoded-navbar">
    <div class="navbar-wrapper">
        <div class="navbar-brand header-logo">
            <a href="<?= link_to('dashboard') ?>" class="b-brand ml-n2">
                <img class="img-fluid rounded-circle" style="width: 2em" src="<?= img_url('logo/logo-mini.jpg') ?>" alt="">
                <span class="b-title font-weight-bold text-center text-uppercase"><?= config('app.name') ?></span>
            </a>
            <a class="mobile-menu" id="mobile-collapse" href="javascript:"><span></span></a>
        </div>
        <div class="navbar-content scroll-div">
            <div class="d-flex menu-box-avatar py-2 theme-bg flex-lg-column align-items-center justify-content-center">
                <img class="img-fluid rounded-circle img-thumbnail" src="<?= $_user->avatar ?>"  id="avatar" alt="Trovich">
                <h2 class="notranslate m-0 h3 text-white text-center" translate="no">
                    <?= $_user->ref ?>
                    <span class="d-inline-block w-100"><?= $_user->username ?></span>
                </h2>
            </div>
            <ul class="nav pcoded-inner-navbar">
                <li class="nav-item <?= link_active('dashboard', 'active', true) ?>">
                    <a href="<?= link_to('dashboard') ?>" class="nav-link">
                        <span class="pcoded-micon"><i class="fa fa-fw fa-tachometer-alt"></i></span>
                        <span class="pcoded-mtext">Tableau de bord</span>
                    </a>
                </li>
                <li class="nav-item pcoded-hasmenu <?= link_active(['infos', 'progression', 'filleuls', 'comptes'], 'active pcoded-trigger') ?>">
                    <a href="javascript:" class="nav-link ">
                        <span class="pcoded-micon"><i class="fa fa-fw fa-user-cog"></i></span>
                        <span class="pcoded-mtext notranslate" translate="no">MS Adminer</span>
                    </a>
                    <ul class="pcoded-submenu">
                        <li class="<?= link_active('infos') ?>"><a class="" href="<?= link_to('infos') ?>">Informations utiles</a></li>
                        <li class="<?= link_active('progression') ?>"><a class="" href="<?= link_to('progression') ?>">Fiche de progression</a></li>
                        <li class="<?= link_active('filleuls') ?>"><a class="" href="<?= link_to('filleuls') ?>">Liste de filleul</a></li>
                        <li class="<?= link_active('comptes') ?>"><a class="" href="<?= link_to('comptes') ?>">Comptes</a></li>
                    </ul>
                </li>
                <li class="nav-item pcoded-hasmenu <?= link_active(['retrait', 'recharge', 'transfert', 'transactions'], 'active pcoded-trigger') ?>">
                    <a href="javascript:" class="nav-link ">
                        <span class="pcoded-micon"><i class="fa fa-fw fa-credit-card"></i></span>
                        <span class="pcoded-mtext notranslate" translate="no">MS Banking</span>
                    </a>
                    <ul class="pcoded-submenu">
                        <li class="<?= link_active('retrait') ?>"><a class="" href="<?= link_to('retrait') ?>">Effectuez un retrait</a></li>
                        <li class="<?= link_active('recharge') ?>"><a class="" href="<?= link_to('recharge') ?>">Rechargez votre compte</a></li>
                        <li class="<?= link_active('transfert') ?>"><a class="" href="<?= link_to('transfert') ?>">Faire un transfert MS</a></li>
                        <li class="<?= link_active('transactions') ?>"><a class="" href="<?= link_to('transactions') ?>">Recap des transactions</a></li>
                    </ul>
                </li>
                <li class="nav-item pcoded-hasmenu <?= link_active(['register', 'inscriptions'], 'active pcoded-trigger') ?>">
                    <a href="javascript:" class="nav-link ">
                        <span class="pcoded-micon"><i class="fa fa-fw fa-user-plus"></i></span>
                        <span class="pcoded-mtext notranslate" translate="no">MS Register</span>
                    </a>
                    <ul class="pcoded-submenu">
                        <li class="<?= link_active('register') ?>"><a class="" href="<?= link_to('register') ?>">Inscrivez un membre</a></li>
                        <li class="<?= link_active('inscriptions') ?>"><a class="" href="<?= link_to('inscriptions') ?>">Liste de vos inscriptions</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>