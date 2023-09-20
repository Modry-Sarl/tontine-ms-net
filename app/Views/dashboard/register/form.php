<?php $this->section('title', 'Inscrivez un membre'); ?>
<?php $this->extend('app') ?>

<?php $this->section('content') ?>

<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header theme-bg">
                <h5 class=" text-white"><i class="fa fa-exchange-alt fa-fw"></i> Demarrer l'inscription</h5>
            </div>
            <form class="card-body"
                hx-post="<?= link_to('register') ?>" 
                hx-disable-element="#indicator"
                hx-target="#response"
                hx-swap="innerHTML"
                hx-select="#result"
            >
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label>Nombre de compte à créer <small class="text-danger">*</small></label>
                            <input type="number" min="1" max="2" name="nbr_compte" value="<?= old('nbr_compte', 1) ?>" class="form-control" placeholder="Entrez le nombre de compte à créer" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Identifiant du parrain </label>
                            <input type="text" name="parrain" value="<?= old('parrain') ?>" autocomplete="off" class="form-control" placeholder="Ex: llAw2IkU" />
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Pays de résidence du membre <small class="text-danger">*</small></label>
                            <select class="form-control" name="pays">
                                <option value="cm" selected>Cameroun</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Numéro de téléphone du membre <small class="text-danger">*</small></label>
                            <input type="tel" name="tel" value="<?= old('tel') ?>" autocomplete="off" class="form-control" placeholder="Ex: 677889900" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Adresse email du membre <small class="text-danger">*</small></label>
                            <input type="email" name="email" value="<?= old('email') ?>" autocomplete="off" class="form-control" placeholder="Ex: johndoe@example.com" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Mot de passe du membre</label>
                            <input type="text" name="mdp" autocomplete="off" class="form-control" />
                        </div>
                        <small class="form-text text-muted">Uniquement s'il est nouveau.</small>
                    </div>
                </div>
                <hr>
                <div class="form-group">
                    <label>Mot de passe <small class="text-danger">*</small></label>
                    <input type="password" min="1" name="password" class="form-control" placeholder="Entrez votre mot de passe pour valider l'inscription" />
                </div>
                <div class="d-flex my-2">
                    <a href="<?= link_to('dashboard') ?>" class="ml-1 btn btn-danger">Annuler</a>
                    <button type="submit" class="mr-1 btn btn-primary">
                        Valider l'inscription
                        <span class="ml-2 htmx-indicator"><i class="fa fa-spin fa-spinner"></i></span>
                    </button>
                </div>

                <div id="response" class="mt-1">
                    <?= $this->insert('htmx-form-response') ?>
                </div>
            </form>
        </div>
    </div>

    <div class="col-12 col-lg-4 mb-3">
        <?= $this->insert('components/informations-utiles') ?>

        <div class="card">
            <div class="card-header theme-bg">
                <h5 class=" text-white"><i class="fa fa-clock fa-fw"></i> Dernière inscription</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table m-b-0 mb-0">
                        <tbody>
                            <?php if (!empty($dernier_inscrit)): ?>
                            <tr class="unread">
                                <td><img class="rounded-circle" style="width:40px;" src="<?= $dernier_inscrit->avatar ?>" alt=""></td>
                                <td>
                                    <h5 class="mb-1"><?= $dernier_inscrit->ref ?></h5>
                                    <h6 class="mb-1"><?= $dernier_inscrit->tel ?> - <?= $dernier_inscrit->user->username ?></h6>
                                    <small class="m-0"><?= $dernier_inscrit->created_at ?></small>
                                </td>
                            </tr>
                            <?php else: ?>
                            <tr class="unread">
                                <td>
                                    <div class="alert alert-info m-b-0 mb-0">
                                        Aucune inscription éffectuée
                                    </div>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
           </div>
        </div>
    </div>
</div>

<?php $this->end() ?>