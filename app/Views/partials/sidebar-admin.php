<nav class="pcoded-navbar">
    <div class="navbar-wrapper">
        <div class="navbar-brand header-logo">
            <a href="<?= link_to('admin.dashboard') ?>" class="b-brand ml-n2">
                <img class="img-fluid rounded-circle" style="width: 2em" src="<?= img_url('logo/logo-mini.jpg') ?>" alt="">
                <span class="b-title font-weight-bold text-center">TONTINE MS-NET</span>
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
                <li class="nav-item <?= link_active('admin.dashboard', 'active', true) ?>">
                    <a href="<?= link_to('admin.dashboard') ?>" class="nav-link">
                        <span class="pcoded-micon"><i class="fa fa-fw fa-tachometer-alt"></i></span>
                        <span class="pcoded-mtext">Tableau de bord</span>
                    </a>
                </li>
                <li class="nav-item pcoded-hasmenu <?= link_active(['admin.membres', 'admin.membre', 'admin.membre.config', 'admin.membre.add'], 'active pcoded-trigger') ?>">
                    <a href="javascript:" class="nav-link ">
                        <span class="pcoded-micon"><i class="fa fa-fw fa-users-cog"></i></span>
                        <span class="pcoded-mtext notranslate">Gestion de membres</span>
                    </a>
                    <ul class="pcoded-submenu">
                        <li class="<?= link_active('admin.membres', 'active', true) ?>"><a class="" href="<?= link_to('admin.membres') ?>">Liste des membres</a></li>
                        <li class="<?= link_active('admin.membre', 'active', true) ?>"><a class="" href="<?= link_to('admin.membre') ?>">Informations du membre</a></li>
                        <li class="<?= link_active('admin.membre.config', 'active', true) ?>"><a class="" href="<?= link_to('admin.membre.config') ?>">Configuration de membre</a></li>
                        <li class="<?= link_active('admin.membre.add', 'active', true) ?>"><a class="" href="<?= link_to('admin.membre.add') ?>">Ajout d'adherants</a></li>
                    </ul>
                </li>
                <li class="nav-item pcoded-hasmenu <?= link_active(['admin.admins', 'admin.activites', 'admin.admin.config', 'admin.admin.add'], 'active pcoded-trigger') ?>">
                    <a href="javascript:" class="nav-link ">
                        <span class="pcoded-micon"><i class="fa fa-fw fa-user-tie"></i></span>
                        <span class="pcoded-mtext notranslate">Gestion d'administrateurs</span>
                    </a>
                    <ul class="pcoded-submenu">
                        <li class="<?= link_active('admin.admins') ?>"><a class="" href="<?= link_to('admin.admins') ?>">Liste des administrateurs</a></li>
                        <li class="<?= link_active('admin.activites') ?>"><a class="" href="<?= link_to('admin.activites') ?>">Log des activités</a></li>
                        <li class="<?= link_active('admin.admin.config') ?>"><a class="" href="<?= link_to('admin.admin.config') ?>">Configuration d'admin</a></li>
                        <li class="<?= link_active('admin.admin.add') ?>"><a class="" href="<?= link_to('admin.admin.add') ?>">Ajout d'administrateur</a></li>
                    </ul>
                </li>
                <li class="nav-item pcoded-hasmenu <?= link_active(['admin.transactions.credit', 'admin.transactions.sorties', 'admin.transactions.entrees'], 'active pcoded-trigger') ?>">
                    <a href="javascript:" class="nav-link ">
                        <span class="pcoded-micon"><i class="fa fa-fw fa-exchange-alt"></i></span>
                        <span class="pcoded-mtext notranslate" translate="no">Transactions</span>
                    </a>
                    <ul class="pcoded-submenu">
                        <li class="<?= link_active('admin.transactions.credit') ?>"><a class="" href="<?= link_to('admin.transactions.credit') ?>">Créditer/Débiter un compte</a></li>
                        <li class="<?= link_active('admin.transactions.sorties') ?>"><a class="" href="<?= link_to('admin.transactions.sorties') ?>">Sorties de fonds</a></li>
                        <li class="<?= link_active('admin.transactions.entrees') ?>"><a class="" href="<?= link_to('admin.transactions.entrees') ?>">Entrées de fonds</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>