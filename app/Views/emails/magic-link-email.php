<?php $this->start('title', lang('Auth.magicLinkSubject')); ?>

<?php $this->start('content'); ?>

<p>
    Veuillez cliquer sur le bouton ci-dessous pour vous connecter. 
    Si vous ne parvenez pas à le faire, vous pouvez copier-coler le lien suivant dans votre navigateur 
    <a href="<?= url_to('verify-magic-link') ?>?token=<?= $token ?>" target="_blank" style="color: #a17141; text-decoration: underline; word-break: break-word;"><?= url_to('verify-magic-link') ?>?token=<?= $token ?></a>. 
</p>
<p style="margin-top: 15px; margin-bottom: 15px;">En cas de problème n'hésitez pas à contacter notre support. </p>
<br />

<b><?= lang('Auth.emailInfo') ?></b>
<p><?= lang('Auth.emailIpAddress') ?> <?= esc($ipAddress) ?></p>
<p><?= lang('Auth.emailDevice') ?> <?= esc($userAgent) ?></p>
<p><?= lang('Auth.emailDate') ?> <?= esc($date) ?></p>

<?php $this->stop(); ?>

<?php $this->start('action') ?>
<tr>
    <td class="card-row" style="font-family: Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px; word-break: break-word; padding-left: 20px; padding-right: 20px; padding-top: 30px; margin-left: 10px; margin-right:10px; "> 
        <table style=" text-align: center;" width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td>
                    <div class="button-holder" style="text-align: center; margin: 0 auto;">
                        <!--[if mso]>
                            <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="<?= url_to('verify-magic-link') ?>?token=<?= $token ?>" style=" white-space: nowrap; height: 40px; v-text-anchor: middle; width: 230px;" arcsize="5%" strokecolor"#a17141" fillcolor="#a17141">
                                <w:anchorlock />
                                <center style="color: #FFFFFF; font-family:Helvetica, Arial,sans-serif; font-size: 16px; font-weight: normal;"><?= lang('Auth.login') ?></center>
                            </v:roundrect>
                        <![endif]--> 
                        <a target="_blank" style=" background-color: #a17141; border: 2px solid #a17141; border-radius: 100px; min-width: 230px; color: #FFFFFF; white-space: nowrap; font-weight: normal; display: block; font-family: Helvetica, Arial, sans-serif; font-size: 16px; line-height: 40px; text-align: center; text-decoration: none; -webkit-text-size-adjust: none; mso-hide: all; " href="<?= url_to('verify-magic-link') ?>?token=<?= $token ?>"><?= lang('Auth.login') ?></a> 
                    </div> 
                </td> 
            </tr>
        </table> 
    </td> 
</tr> 
<?php $this->stop(); ?>
