<?php $this->section('title', 'Connexion'); ?>
<?php $this->extend('auth') ?>

<?php $this->section('content') ?>

<form action="<?= link_to('login') ?>" method="POST" class="card-body text-center">
    <div class="mb-2 d-inline-flex align-items-center">
        <div class="b-bg btn-theme p-2">
            <i class="feather icon-trending-up fa-2x text-white"></i>
        </div>
        <h3 class="text-center titre-principal ml-2 mb-0  font-weight-bold" style="font-size: 2em ;">TONTINE-MS</h3>
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
    <?php if ($error = $errors->line('default')): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
    
    <button type="submit" class="btn btn-primary shadow-2 mt-1 mb-4">Se connecter</button>
    <p class="mb-2 text-muted">Mot de passe oublié? <a href="auth-reset-password.html">Réinitiliser</a></p>
</form>

<?php $this->end() ?>