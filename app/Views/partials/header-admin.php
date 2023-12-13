<header class="navbar pcoded-header navbar-expand-lg navbar-light">
    <div class="m-header">
        <a class="mobile-menu" id="mobile-collapse1" href="javascript:"><span></span></a>
        <a href="<?= link_to('dashboard') ?>" class="b-brand">
            <img class="img-fluid rounded-circle" style="width: 4.5em" src="<?= img_url('logo/logo-mini.jpg') ?>" alt="">
            <span class="b-title font-weight-bold text-center">TONTINE MS-NET</span>
        </a>
    </div>
    <a class="mobile-menu" id="mobile-header" href="javascript:">
        <i class="feather icon-more-horizontal"></i>
    </a>
    <div class="collapse navbar-collapse theme-bg">
        <div class="mr-auto text-center" style="flex: 1">
            <h1 translate="no" class="notranslate titre-principal">
				<span class="d-none d-lg-block">TONTINE MS-NET : Centre d'administration</span>
				<span class="d-block d-lg-none">Centre d'administration</span>
			</h1>
        </div>
        <ul class="navbar-nav ml-auto">
            <li>
                <div class="dropdown drp-user">
                    <a href="javascript:" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="icon feather icon-user"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right profile-notification">
                        <div class="pro-head">
                            <img src="<?= $_user->avatar ?>" class="img-radius" alt="<?= $_user->username ?>">
                            <span><?= $_user->username ?></span>
                            <form class="d-inline" method="post" action="<?= link_to('logout') ?>">
                                <input type="hidden" name="_method" value="delete" />
                                <a class="dud-logout" href="<?= link_to('logout') ?>" onclick="event.preventDefault(); this.closest('form').submit();">
                                    <i class="feather icon-log-out"></i>
                                </a>
                            </form>
                        </div>
                        <ul class="pro-body">
                            <li><a href="javascript:" class="dropdown-item"><i class="feather icon-settings"></i> Paramètres</a></li>
                            <li><a href="javascript:" class="dropdown-item"><i class="feather icon-file"></i> Log des activités</a></li>
                            <li class="dropdown-divider"></li>
                            <li><a href="<?= link_to('dashboard') ?>" class="dropdown-item" target="_blank"><i class="feather icon-home"></i> Backoffice</a></li>
                            <li class="dropdown-divider"></li>
                            <li><form class="d-inline" method="post" action="<?= link_to('logout') ?>">
                                <input type="hidden" name="_method" value="delete" />
                                <a class="dropdown-item" href="<?= link_to('logout') ?>" onclick="event.preventDefault(); this.closest('form').submit();">
                                    <i class="feather icon-log-out"></i> Déconnexion
                                </a>
                            </form></li>
                        </ul>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</header>