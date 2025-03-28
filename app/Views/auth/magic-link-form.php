<?php $this->section('title', lang('Auth.forgotPassword')); ?>
<?php $this->extend('auth') ?>

<?php $this->section('content') ?>

<form action="<?= link_to('magic-link') ?>" method="POST" class="card-body text-center">
    <div class="mb-2 d-inline-flex align-items-center flex-column flex-lg-row">
        <img class="img-fluid w-25" src="<?= img_url('logo/logo-mini.jpg') ?>" alt="">
        <h3 class="text-center titre-principal ml-2 mb-0  font-weight-bold text-uppercase"><?= config('app.name') ?></h3>
    </div>
    <h5 class="my-2"><?= lang('Auth.forgotPassword') ?></h5>
    <p class="mb-5"><?= lang('Auth.useMagicLink') ?></p>

    <div class="form-group mb-3">
        <div class="input-group">
            <input type="email" class="form-control" placeholder="Adresse email" name="email"
                value="<?= old('email') ?>" autocomplete="no">
        </div>
        <?php if ($error_email = $errors->line('email')): ?><span class="small text-danger"><?= $error_email ?></span><?php endif; ?>
    </div>

    <?php if (session('error') !== null) : ?>
        <div class="alert alert-danger" role="alert"><?= session('error') ?></div>
    <?php elseif (session('errors') !== null) : ?>
        <div class="alert alert-danger" role="alert">
            <?php if (is_array(session('errors'))) : ?>
                <?php foreach (session('errors') as $error) : ?>
                    <?= $error ?>
                    <br>
                <?php endforeach ?>
            <?php else : ?>
                <?= session('errors') ?>
            <?php endif ?>
        </div>
    <?php endif ?>
    
    <button type="submit" class="btn btn-primary shadow-2 mt-1 mb-4"><?= lang('Auth.send') ?></button>
    <p class="mb-2 text-muted"><a href="<?= url_to('login') ?>"><?= lang('Auth.backToLogin') ?></a></p>
</form>

<?php $this->end() ?>