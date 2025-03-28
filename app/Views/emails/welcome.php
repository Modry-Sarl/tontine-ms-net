<?php $this->start('title', 'Bienvenue sur ' . strtoupper(config('app.name'))); ?>

<?php $this->start('content'); ?>

<p>Salut <?= $user->user->username ?>, </p>
<p>Vous avez été inscrit avec succès sur la plateforme <?= strtoupper(config('app.name')) ?>. Nous vous souhaitons la bienvenue et esperons que vous vous plairez parmis nous. </p>
<p>Trouvez ci dessous vos informations de connexion à votre compte. </p>
<table  width="100%" style="margin-top: 25px; margin-bottom: 25px" border="0" cellspacing="0" cellpadding="0">
    <tbody>
        <tr>
            <td class="border plr20 pb20 pt20">Identifiant</td>
            <th class="border plr20 pb20 pt20"><?= $user->ref ?></th>
        </tr>
        <tr>
            <td class="border plr20 pb20 pt20">Numéro de téléphone</td>
            <th class="border plr20 pb20 pt20"><?= $user->user->tel ?></th>
        </tr>
        <tr>
            <td class="border plr20 pb20 pt20">Mot de passe</td>
            <th class="border plr20 pb20 pt20"><?= $password ?: 'Identique à celui de votre premier compte' ?></th>
        </tr>
    </tbody>
</table>
<p>En cas de problème n'hésitez pas à contacter notre support. </p>

<?php $this->stop(); ?>

<?php $this->start('action') ?>
<tr>
    <td class="card-row" style="font-family: Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px; word-break: break-word; padding-left: 20px; padding-right: 20px; padding-top: 30px; margin-left: 10px; margin-right:10px; "> 
        <table style=" text-align: center;" width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td>
                    <div class="button-holder" style="text-align: center; margin: 0 auto;">
                        <!--[if mso]>
                            <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="<?= link_to('login') ?>" style=" white-space: nowrap; height: 40px; v-text-anchor: middle; width: 230px;" arcsize="5%" strokecolor"#a17141" fillcolor="#a17141">
                                <w:anchorlock />
                                <center style="color: #FFFFFF; font-family:Helvetica, Arial,sans-serif; font-size: 16px; font-weight: normal;"><?= lang('Auth.login') ?></center>
                            </v:roundrect>
                        <![endif]--> 
                        <a target="_blank" style=" background-color: #a17141; border: 2px solid #a17141; border-radius: 100px; min-width: 230px; color: #FFFFFF; white-space: nowrap; font-weight: normal; display: block; font-family: Helvetica, Arial, sans-serif; font-size: 16px; line-height: 40px; text-align: center; text-decoration: none; -webkit-text-size-adjust: none; mso-hide: all; " href="<?= link_to('login') ?>"><?= lang('Auth.login') ?></a> 
                    </div> 
                </td> 
            </tr>
        </table> 
    </td> 
</tr> 
<?php $this->stop(); ?>
