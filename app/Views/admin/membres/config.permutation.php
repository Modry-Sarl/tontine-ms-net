<div class="card">
    <div class="card-header theme-bg">
        <h5 class=" text-white"><i class="fa fa-exchange-alt"></i> Permutation</h5>
    </div>
    <form class="card-body"
        hx-post="<?= current_url(true) ?>" 
        hx-disable-element="#indicator"
        hx-target="#response"
        hx-swap="innerHTML"
        hx-select="#result"
        >
        <div class="row">
            <div class="form-group col-lg-6"> 
                <label for="parrain" class="form-label">Parrain <span class="text-danger">*</span></label>
                <input type="text" name="parrain" id="parrain" value="<?= old('parrain', $user->parrain) ?>" class="form-control-sm form-control" required placeholder="Entrer l'ID du parrain du membre pour le changer" /> 
            </div>
            <div class="form-group col-lg-6"> 
                <label for="permut" class="form-label">ID de permutation</label>
                <input type="text" name="permut" id="type_compte" value="<?= old('permut') ?>" class="form-control-sm form-control" placeholder="Entrer l'ID du membre avec lequel permuter" /> 
            </div>
        </div>
        <hr />
        <div class="row"> 
            <div class="col-lg-8"> 
                <div class="form-group"> 
                    <label for="mdp" class="form-label">Mot de passe <span class="text-danger">*</span></label> 
                    <input type="password" name="password" id="mdp" class="form-control-sm form-control" required placeholder="Entrer votre mot de passe pour confirmer l'operation" /> 
                </div>
            </div> 
            <div class="col-lg-4"> 
                <div class="form-group"> 
                    <label for="">&nbsp;</label> 
                    <div class="btn-group form-control form-control-sm p-0"> 
                        <button type="reset" class="btn btn-danger" id="btn_annuler" >Annuler</button> 
                        <button type="submit" class="btn btn-primary btn-block" id="btn_valider" >
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