<div class="card">
    <div class="card-header theme-bg">
        <h5 class=" text-white"><i class="fa fa-exchange-alt"></i> Attribuer à un autre utilisateur</h5>
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
                <label for="ref" class="form-label">ID <span class="text-danger">*</span></label>
                <input type="text" name="ref" id="ref" value="<?= old('ref', $ref ?? '') ?>" class="form-control-sm form-control" required disabled /> 
            </div>
            <div class="form-group col-lg-6"> 
                <label for="tel" class="form-label">Numéro du nouveau membre <span class="text-danger">*</span></label>
                <input type="tel" name="tel" id="tel" value="<?= old('tel') ?>" class="form-control-sm form-control" placeholder="Entrer le numéro du nouveau membre" autofocus /> 
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