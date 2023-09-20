<div id="result">
    <?php if (!empty($e = $errors->all())) : ?>
        <div class="alert alert-danger" role="alert">
            <h4 class="alert-heading">Une ou plusieurs erreurs se sont produite</h4>
            <ul>
                <?php foreach ($e as $error) : ?>
                    <li><?= join('<li>', (array) $error) ?></li>
                <?php endforeach ?>
            </ul>
        </div>
    <?php endif ?>

    <?php if (session('success') !== null) : ?>
        <div class="alert alert-success" role="alert">
            <h4 class="alert-heading">Opération réussie!</h4>
            <p><?= session('success') ?></p>
            <hr>
            <small class="text-small">Redirection en cours ... <i class="fa fa-spin fa-circle-notch"></i></small>
            <script>setTimeout(function(){ location.href="<?= link_to('transactions') ?>"; }, 2500);</script>
        </div>
    <?php endif ?>
</div>