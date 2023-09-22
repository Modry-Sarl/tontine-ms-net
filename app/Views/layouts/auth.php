<!DOCTYPE html>
<html lang="<?= config('app.language') ?>">

<head>
    <title><?= $this->show('title', true) ?> - TONTINE-MS</title>
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

    <style>
        .auth-wrapper .auth-content {
            width: 100%;
            margin: auto auto !important;
        } 
        @media (min-width: 768px) {
            .auth-wrapper .auth-content {
                width: 75%;
            }   
        }

    </style>
</head>

<body>
    <div class="auth-wrapper">
        <div class="auth-content">
            <div class="auth-bg">
                <span class="r"></span>
                <span class="r s"></span>
                <span class="r s"></span>
                <span class="r"></span>
            </div>
            <div class="card">
                <div class="card-body p-0 ">
                    <div class="row">
                        <div class="col-12 col-lg-8 d-none d-lg-block"
                            style="background-size: cover; background-image: url(<?= img_url('bg/bg_2.jpg') ?>)">
                            <div class="w-50 h-100">
                                <div class="card h-100" style="background: transparent !important">
                                    <div class="card-body">
                                        <h3 class="text-center text-secondary mt-2 w-100 header-custom-title">
                                        L'entraide par la pratique
                                        </h3>
                                        <hr />
                                        <ul class="h6 font-weight-light my-3">
                                            <li class="pb-1">
                                                TONTINE MS-NET est une plateforme permettant à des individus qui souhaitent ou 
                                                aspirent à une meilleure vie de s'entraider en mutualisant leur force et leur finance.
                                            </li>
                                            <li class="pb-1">
                                                TONTINE MS-NET est né de la volonté d'un groupe d'hommes d'affaires internationaux de premier 
                                                plan, expérimenté dans l'informatique, le marketing de réseau et le développement personnel 
                                                avec plus de 22 ans d'expériences professionnelle combinée.
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-4">
                            <div class="card">
                                <?= $this->renderView() ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>