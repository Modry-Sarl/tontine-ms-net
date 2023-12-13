<div class="card">
    <div class="card-header theme-bg">
        <h5 class=" text-white"><i class="fa fa-id-card"></i> Profil d'utilisateur</h5>
    </div>
    <form class="card-body" 
        hx-post="<?= current_url(true) ?>" 
        hx-disable-element="#indicator" 
        hx-target="#response"
        hx-swap="innerHTML" 
        hx-select="#result">
        <div class="row">
            <div class="form-group col-lg-6"> 
                <label for="tel" class="form-label">Téléphone </label>
                 <input type="tel" name="tel" id="tel" value="<?= old('tel', $user->user->tel) ?>" class="form-control-sm form-control" placeholder="Entrer le numero de téléphone du membre" /> 
                 <span class="text-warning small text-small">Au format local (sans indicatif)</span>
            </div>
            <div class="form-group col-lg-6"> 
                <label for="email" class="form-label">Email </label> 
                <input type="email" name="email" id="email" value="<?= old('email', $user->user->getEmail()) ?>" class="form-control-sm form-control" placeholder="Entrer l'adresse email du membre" /> 
            </div>
            <div class="form-group col-lg-6"> 
                <label for="pwd" class="form-label">Mot de passe du membre </label>
                <input type="text" name="pwd" id="pwd" value="" class="form-control-sm form-control" placeholder="Entrer un nouveau mot de passe pour le membre" /> 
            </div>
            <div class="form-group col-lg-6"> 
                <label for="verouillage" class="form-label">Verouillage du compte</label> 
                <select name="verouillage" id="verouillage" class="form-control-sm form-control">
                    <option value="none">---------------------------------------</option>
                    <optgroup label="Verouillage">
                        <option value="locked">Blocage</option>
                        <option value="disabled">Désactivation</option>
                        <option value="deleted">Suppression temporelle</option>
                        <option value="removed">Suppression définitive</option>
                    </optgroup>
                    <optgroup label="Deverouillage">
                        <option value="unlocked">Déblocage</option>
                        <option value="enabled">Activation</option>
                        <option value="undeleted">Remise au programe</option>
                    </optgroup>
                </select> 
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-lg-8">
                <div class="form-group">
                    <label for="mdp" class="form-label">Mot de passe <span class="text-danger">*</span></label>
                    <input type="password" name="password" id="mdp" class="form-control-sm form-control" required
                        placeholder="Entrer votre mot de passe pour confirmer l'operation" />
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="">&nbsp;</label>
                    <div class="btn-group form-control form-control-sm p-0">
                        <button type="reset" class="btn btn-danger" id="btn_annuler">Annuler</button>
                        <button type="submit" class="btn btn-primary btn-block" id="btn_valider">
                            Valider
                            <span class="ml-2 htmx-indicator"><i class="fa fa-spin fa-spinner"></i></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div id="response" class="mt-2">
            <?= $this->insert('config.htmx-form-response') ?>
        </div>
    </form>
</div>