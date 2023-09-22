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
                        </div>
                        <ul class="noti-body">
                            <?php if (empty($_user->utilisateur->notifications)): ?>
                            <p class="text-center">
                                Aucune nouvelle notification pour le moment.
                                <br/>
                                Vous serez informé lorsque des notifications seront diponible.
                            </p>
                            <?php else: foreach ($_user->utilisateur->notifications as $notification): ?>
                            <li class="notification">
                                <div class="media">
                                    <div style="width: 2em; height: 2em" class="mr-2 rounded-circle theme-bg d-flex justify-content-center align-items-center">
                                        <i class="text-white fa <?= get_notification_icon($notification->type) ?>"></i>
                                    </div>
                                    <div class="media-body">
                                        <p>
                                            <strong>J<?= $notification->libelle ?></strong>
                                            <span class="n-time text-muted"><i class="icon feather icon-clock m-r-10"></i> <?= $notification->created_at->format('d/m/Y') ?></span>
                                        </p>
                                    </div>
                                </div>
                            </li>
                            <?php endforeach; endif; ?>
                        </ul>
                        <div class="noti-footer">
                            <a href="<?= link_to('notifications') ?>">Voir toutes les <b><?= $total_notifications ?></b> notifications</a>
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