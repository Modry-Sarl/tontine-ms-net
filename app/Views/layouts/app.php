<!DOCTYPE html>
<html lang="<?= config('app.language') ?>">

<head>
    <title><?= $this->show('title', true) ?> - TONTINE MS-NET</title>
    <!-- HTML5 Shim and Respond.js IE10 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 10]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    
    <link rel="icon" href="<?= img_url('favicon.ico') ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= lib_css_url('fontawesome/css/fontawesome-all.min.css') ?>" />
    <link rel="stylesheet" href="<?= lib_css_url('animation/animate.min.css') ?>" />
    <link rel="stylesheet" href="<?= css_url('style.css') ?>">
    <link rel="stylesheet" href="<?= css_url('app.css') ?>">
    <script src="<?= lib_js_url('htmx/htmx.min.js') ?>" defer></script>
    <script src="https://unpkg.com/htmx.org/dist/ext/disable-element.js" defer></script>
</head>

<body hx-ext="disable-element">
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    
    <?= $this->insert('sidebar') ?>
    
    <?= $this->insert('header') ?>

    <div class="pcoded-main-container">
        <div class="pcoded-wrapper">
            <div class="pcoded-content">
                <div class="pcoded-inner-content">
                    <div class="page-header">
                        <div class="page-block">
                            <div class="row align-items-center">
                                <div class="col-md-12">
                                    <div class="page-header-title">
                                        <h3 class="m-b-10"><?= $this->show('title', true) ?></h3>
                                    </div>
                                    <?= $this->show('header') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="main-body">
                        <div class="page-wrapper mt-4">
                            <?= $this->renderView() ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="fixed-bottom m-0 d-none bg-ligth d-lg-block">
		<div>
			<span>Powered by <a target="_blank" href="https://linktr.ee/dimtrovich">Dimtrovich</a></span>
		</div>
    </footer>

    
    <script src="<?= js_url('vendor-all.min.js') ?>"></script>
	<script src="<?= lib_js_url('bootstrap/bootstrap.min.js') ?>"></script>
    <script src="<?= js_url('pcoded.min.js') ?>"></script>
</body>
</html>
