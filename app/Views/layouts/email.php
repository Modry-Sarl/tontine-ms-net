<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html lang="fr" xml:lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="x-apple-disable-message-reformatting">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://fonts.googleapis.com/css?family=Helvetica:400,700" rel="stylesheet">
    <title><?= $this->show('title', true) ?></title>
    <style type="text/css"><?= file_get_contents(__DIR__ . '/email.css') ?></style>
    <!--[if (mso)|(IE)]> 
        <xml:namespace ns="urn:schemas-microsoft-com:vml" prefix="v" /> 
        <style>* {behavior: url(#default#VML); display: inline-block }</style> 
    <![endif]-->
    <!--[if (gte mso 9)|(IE)]> 
        <style> .mso-hidden { display: none; } .parent-full-width { margin: 0 !important; } .d-md-none { display: block !important; float: right; margin-left: 10px; } </style> 
    <![endif]-->
</head>
<body>
    <!--[if mso]> <style type="text/css"> body, table, td, strong, h1, h2, h3, h4, h5, b {font-family: Arial, Helvetica, sans-serif !important;} </style> <![endif]-->
    <div style="display: none;"><?= $this->show('title', true) ?></div>
    <table bgcolor="#F2F2F2" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tbody>
            <tr>
                <!--[if (gte mso 9)|(IE)]> <td><table cellspacing="0" cellpadding="0" width="600" border="0" align="center"><tr> <![endif]-->
                <td>
                    <div style="max-width: 600px; margin: 0 auto; font-size: 16px; line-height: 24px;">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tbody>
                                <tr>
                                    <td>
                                        <table border="0" cellpadding="0" cellspacing="0" class="card-box first" width="100%">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <table border="0" cellpadding="0" cellspacing="0" class="card-box" width="100%">
                                                            <tbody>
                                                                <tr>
                                                                    <td style="background-color: white; padding-top: 30px; padding-bottom: 30px;" class="card">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td align="left" style="padding-top: 0; padding-bottom: 20px; padding-left:30px"> 
                                                                                        <a class="logo" href="<?= site_url() ?>"> 
                                                                                            <img src="<?= img_url('logo/logo-mini.jpg') ?>" alt="<?= config('app.name') ?>" width="50" height="50" style="vertical-align: middle;" /> 
                                                                                            <span class="text-uppercase"><?= config('app.name') ?></span>
                                                                                        </a> 
                                                                                    </td> 
                                                                                </tr> 
                                                                                <tr> 
                                                                                    <td class="card-row" style="font-family: Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px; word-break: break-word; padding: 20px; margin-left: 5px; margin-right: 5px; "> 
                                                                                        <h3 style="margin-top: 0; margin-bottom: 0; font-family: Helvetica, sans-serif; font-weight: normal; font-size: 20px; line-height: 26px; color: #001E00;"><?= $this->show('title', true) ?></h3>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class="card-row" style="font-family: Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px; word-break: break-word; padding-left: 20px; padding-right: 20px; padding-top: 20px; margin-left: 10px; margin-right: 10px; "> 
                                                                                        <?= $this->renderView() ?>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class="card-row" style="font-family: Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px; word-break: break-word; padding-left: 20px; padding-right: 20px; padding-top: 10px; margin-left: 10px; margin-right: 10px; "> 
                                                                                        <table border=" 0" cellpadding="0" cellspacing="0" width="100%">
                                                                                            <tr><td style="font-size: 0; line-height: 0;">&nbsp;</td></tr>
                                                                                        </table>
                                                                                    </td>
                                                                                </tr>
                                                                                <?= $this->show('action') ?>
                                                                                <tr> 
                                                                                    <td class="card-row" style="font-family: Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px; word-break: break-word; padding-left: 20px; padding-right: 20px; padding-top: 30px; margin-left: px; margin-right: px; "> 
                                                                                        <div style="padding-top: 10px;">Merci pour votre confiance,<br>L'équipe <?= config('app.name') ?></div> 
                                                                                    </td> 
                                                                                </tr> 
                                                                                <tr> 
                                                                                    <td class="card-row" style="font-family: Helvetica, Arial, sans-serif; font-size: small; word-break: break-word; color: red; text-align: center"> 
                                                                                        <hr style="margin-bottom: 5px; margin-top: 20px"/>
                                                                                        Email envoyé automatiquement par le programme <?= config('app.name') ?>. Vous ne devez pas le répondre directement. 
                                                                                        Si vous avez des préoccupations, contactez notre support.
                                                                                    </td> 
                                                                                </tr> 
                                                                            </tbody> 
                                                                        </table> 
                                                                    </td>
                                                                </tr> 
                                                            </tbody> 
                                                        </table> 
                                                    </td> 
                                                </tr> 
                                            </tbody> 
                                        </table> 
                                    </td> 
                                </tr> 
                            </tbody>
                        </table> 
                        <table border="0" cellpadding="0" cellspacing="0" width="100%"> 
                            <tbody> 
                                <tr> 
                                    <td align="center" width="100%" style="color: #65735B; font-size: 12px; line-height: 24px; padding-bottom: 30px; padding-top: 30px;"> 
                                        <a href="#" style="color: #65735B; text-decoration: underline;">Conditions d'utilisation</a> 
                                        &nbsp; | &nbsp; 
                                        <a href="https://wa.me/237655094814?text=<?= urlencode("Bonjour j'ai besoin d'aide à propos de la plateforme tontinemsnet") ?>" style="color: #65735B; text-decoration: underline;">Contacter le support</a> 
                                        
                                        <div style="font-family: Helvetica, Arial, sans-serif; word-break: normal;" class="address-link">Yaoundé Cameroun</div> 
                                        <div style="font-family: Helvetica, Arial, sans-serif; word-break:normal;" >&copy; <?= date('Y') ?> <?= strtoupper(config('app.name')) ?>.</div> 
                                    </td> 
                                </tr> 
                            </tbody> 
                        </table> 
                    </div> 
                </td> 
            <!--[if (gte mso 9)|(IE)]> </tr></table></td> <![endif]--> </tr> 
        </tbody> 
    </table>
    <img width="1px" height="1px" alt="" src="http://email.mg.upwork.com/o/eJwczM1ugzAMAOCngRvI-XFCDjnssteojPGotSZBkGrVnn5az5_0bRkMkIujZBNS8C5EXMZ7JhFKYbEOvVsIk7MLbSlZi5GZI46aLVhnALwJFhFng0wA27oSr-zWr8FD2efn8dPO75lbGc-8adF-6qWd79IHD3shfbyx5y7leFCXwX2UVqXrL3VtdbCf3GoV7tdNXoeeWvfb1VodX5O06T_Yn3XSLZsIKQDGmKIxxqAN_i8AAP__XnxFBA">
</body>
</html>