<div id="result">
    <?php if (isset($status) && $status === 'cancelled'): ?>
        <div class="alert alert-danger" role="alert">
            <p>Le paiement a été annulé</p>
        </div>
    <?php endif; ?>

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

    <?php if (!empty($payment_url)) : ?>
        <fieldset class="paiement-form">
            <p class="text-center">
                Vous êtes sur le point de recharger 
                <b><?= number_format($montant) ?> $ (<i><?= number_format(to_cfa($montant)) ?> FCFA</i>)</b> 
                dans votre compte MS.
            </p>
            <hr/><br/>
            <script type="text/javascript" src="https://fr.monetbil.com/widget/v2/monetbil.min.js"></script>
            <form method="post" action="<?= $payment_url ?>" data-monetbil="form">
                <button type="submit" class="btn btn-primary btn-lg btn-block">Recharger maintenant</button>
            </form>
        </fieldset>
    <?php endif ?>

    <?php if (session('success') !== null) : ?>
        <div class="alert alert-success" role="alert">
            <h4 class="alert-heading">Opération réussie!</h4>
            <p><?= session('success') ?></p>
            <hr>
            <small class="text-small">Redirection en cours ... <i class="fa fa-spin fa-circle-notch"></i></small>
            <script>setTimeout(function(){ location.href="<?= link_to('inscriptions') ?>"; }, 2500);</script>
        </div>
    <?php endif ?>
</div>