<?php $this->section('title', lang('Auth.useMagicLink')); ?>
<?php $this->extend('auth') ?>

<?php $this->section('content') ?>

<div class="card-body text-center">
    <div class="mb-2 d-inline-flex align-items-center flex-column flex-lg-row">
        <img class="img-fluid w-25" src="<?= img_url('logo/logo-mini.jpg') ?>" alt="">
        <h3 class="text-center titre-principal ml-2 mb-0  font-weight-bold">TONTINE MS-NET</h3>
    </div>
    <h5 class="mt-2 mb-5"><?= lang('Auth.useMagicLink') ?></h5>

    <p><b><?= lang('Auth.checkYourEmail') ?></b></p>
    <p><?= lang('Auth.magicLinkDetails', [config('auth.magic_link_lifetime') / 60]) ?></p>

    <p class="mt-4 mb-2 text-muted"><a href="<?= url_to('login') ?>"><?= lang('Auth.backToLogin') ?></a></p>
</div>

<?php $this->end() ?>