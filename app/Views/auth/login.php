<?php $this->section('title', 'Connexion'); ?>
<?php $this->extend('auth') ?>

<?php $this->section('content') ?>

<form action="<?= link_to('login') ?>" method="POST" class="card-body text-center">
    <div class="mb-2 d-inline-flex align-items-center flex-column flex-lg-row">
        <img class="img-fluid w-25" src="<?= img_url('logo/logo-mini.jpg') ?>" alt="">
        <h3 class="text-center titre-principal ml-2 mb-0  font-weight-bold text-uppercase"><?= config('app.name') ?></h3>
    </div>
    <h5 class="mb-5 mt-2">Connectez-vous</h5>

    <div class="form-group mb-3">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="ID, numéro de téléphone ou email" name="login"
                value="<?= old('login') ?>" autocomplete="no">
        </div>
        <?php if ($error_login = $errors->line('login')): ?><span class="small text-danger"><?= $error_login ?></span><?php endif; ?>
    </div>
    <div class="form-group mb-3">
        <div class="input-group">
            <input type="password" class="form-control" placeholder="Mot de passe" name="password" autocomplete="current-password" />
        </div>
        <?php if ($error_password = $errors->line('password')): ?><span class="small text-danger"><?= $error_password ?></span><?php endif; ?>
    </div>

    <?php if (config('auth.session.allow_remembering')): ?>
        <div class="form-check mb-3">
            <label class="form-check-label">
                <input type="checkbox" name="remember" class="form-check-input">
                <?= lang('Auth.rememberMe') ?>
            </label>
        </div>
    <?php endif; ?>

    <?php if ($error = $errors->line('default')): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
    
    <button type="submit" class="btn btn-primary shadow-2 mt-1 mb-4">Se connecter</button>
    <p class="mb-2 text-muted">Mot de passe oublié? <a href="<?= url_to('magic-link') ?>">Réinitiliser</a></p>
</form>

<?php $this->end() ?>