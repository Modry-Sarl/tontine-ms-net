<?php $this->section('title', 'Ajout de membre'); ?>
<?php $this->extend('admin') ?>

<?php $this->section('content') ?>

<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header theme-bg">
                <h5 class=" text-white"><i class="fa fa-exchange-alt fa-fw"></i> Demarrer l'ajout</h5>
            </div>
            <form class="card-body"
                hx-post="<?= link_to('admin.membre.add') ?>" 
                hx-disable-element="#indicator"
                hx-target="#response"
                hx-swap="innerHTML"
                hx-select="#result"
            >
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label>Nombre de compte à créer <small class="text-danger">*</small></label>
                            <input type="text" value="1" class="form-control" disabled />
                            <input type="hidden" name="nbr_compte" value="1" class="form-control" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Identifiant du parrain </label>
                            <input type="text" name="parrain" value="<?= old('parrain') ?>" autocomplete="off" class="form-control" placeholder="Ex: MS002KI2403" />
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
                            <label>Mot de passe du membre <small class="text-danger">*</small></label>
                            <input type="text" name="mdp" value="<?= old('mdp') ?>" autocomplete="off" class="form-control" />
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-group">
                    <label>Mot de passe <small class="text-danger">*</small></label>
                    <input type="password" min="1" name="password" class="form-control" placeholder="Entrez votre mot de passe pour valider l'inscription" />
                </div>
                <div class="d-flex my-2">
                    <a href="<?= previous_url() ?>" class="ml-1 btn btn-danger">Annuler</a>
                    <button type="submit" class="mr-1 btn btn-primary">
                        Valider l'ajout
                        <span class="ml-2 htmx-indicator"><i class="fa fa-spin fa-spinner"></i></span>
                    </button>
                </div>

                <div id="response" class="mt-1">
                    <?= $this->insert('config.htmx-form-response') ?>
                </div>
            </form>
        </div>
    </div>

    <div class="col-12 col-lg-4 mb-3">
        
    </div>
</div>

<?php $this->end() ?>

