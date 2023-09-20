<header class="navbar pcoded-header navbar-expand-lg navbar-light">
    <div class="m-header">
        <a class="mobile-menu" id="mobile-collapse1" href="javascript:"><span></span></a>
        <a href="index.html" class="b-brand">
            <div class="b-bg">
                <i class="feather icon-trending-up"></i>
            </div>
            <span class="b-title">TONTINE-MS</span>
        </a>
    </div>
    <a class="mobile-menu" id="mobile-header" href="javascript:">
        <i class="feather icon-more-horizontal"></i>
    </a>
    <div class="collapse navbar-collapse theme-bg">
        <div class="mr-auto text-center" style="flex: 1">
            <h1 translate="no" class="notranslate titre-principal">
				<span class="d-none d-lg-block">Gestionnaire de compte</span>
				<span class="d-block d-lg-none">Backoffice</span>
			</h1>
        </div>
        <ul class="navbar-nav ml-auto">
            <li>
                <div class="dropdown">
                    <a class="dropdown-toggle" href="javascript:" data-toggle="dropdown"><i class="icon feather icon-bell"></i></a>
                    <div class="dropdown-menu dropdown-menu-right notification">
                        <div class="noti-head">
                            <h6 class="d-inline-block m-b-0">Notifications</h6>
                            <div class="float-right">
                                <a href="javascript:" class="m-r-10">mark as read</a>
                                <a href="javascript:">clear all</a>
                            </div>
                        </div>
                        <ul class="noti-body">
                            <li class="n-title">
                                <p class="m-b-0">NEW</p>
                            </li>
                            <li class="notification">
                                <div class="media">
                                    <img class="img-radius" src="assets/images/user/avatar-1.jpg" alt="Generic placeholder image">
                                    <div class="media-body">
                                        <p><strong>John Doe</strong><span class="n-time text-muted"><i class="icon feather icon-clock m-r-10"></i>30 min</span></p>
                                        <p>New ticket Added</p>
                                    </div>
                                </div>
                            </li>
                            <li class="n-title">
                                <p class="m-b-0">EARLIER</p>
                            </li>
                            <li class="notification">
                                <div class="media">
                                    <img class="img-radius" src="assets/images/user/avatar-2.jpg" alt="Generic placeholder image">
                                    <div class="media-body">
                                        <p><strong>Joseph William</strong><span class="n-time text-muted"><i class="icon feather icon-clock m-r-10"></i>30 min</span></p>
                                        <p>Prchace New Theme and make payment</p>
                                    </div>
                                </div>
                            </li>
                            <li class="notification">
                                <div class="media">
                                    <img class="img-radius" src="assets/images/user/avatar-3.jpg" alt="Generic placeholder image">
                                    <div class="media-body">
                                        <p><strong>Sara Soudein</strong><span class="n-time text-muted"><i class="icon feather icon-clock m-r-10"></i>30 min</span></p>
                                        <p>currently login</p>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <div class="noti-footer">
                            <a href="javascript:">show all</a>
                        </div>
                    </div>
                </div>
            </li>
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