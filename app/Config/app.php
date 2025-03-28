<?php

/*
| -------------------------------------------------- -----------------
| PARAMÈTRES GÉNÉRAUX DE L'APPLICATION
| -------------------------------------------------- -----------------
| Ce fichier contiendra les paramètres généraux de votre application.
|
| Pour des instructions complètes, veuillez consulter la « Configuration générale » dans le Guide de l'utilisateur.
|
*/


return [
    /*
    | URL de votre racine dFramework. Il s'agira généralement de votre URL de base, AVEC une barre oblique à la fin : par exemple : http://example.com/
    |
    | AVERTISSEMENT : Vous DEVEZ définir cette valeur !
    |
    | S'il n'est pas défini, dFramework essaiera de deviner le protocole et le chemin
    | votre installation, mais pour des raisons de sécurité, le nom d'hôte sera défini
    | à $_SERVER['SERVER_ADDR'] si disponible, ou localhost sinon.
    | Le mécanisme d'auto-détection n'existe que pour plus de commodité pendant
    | développement et NE DOIT PAS être utilisé en production !
    |
    | Si vous devez autoriser plusieurs domaines, n'oubliez pas que ce fichier est toujours
    | un script PHP et vous pouvez facilement le faire vous-même.
    |
    */
    'base_url' => env('app.baseURL', ''),

    /*
    | Nom de l'application
    |
    | Cette valeur est le nom de votre application. 
	| Cette valeur est utilisée lorsque le framework doit placer le nom de l'application 
	| dans une notification ou à un autre endroit requis par l'application ou ses paquets.
    */
    'name' => env('app.name', 'Application'),

    /*
    | Cette option permet d'ajouter un suffixe à toutes les URL générées par le framework
    | Pour plus d'informations, veuillez consulter le guide de l'utilisateur :
    */
    'url_suffix' => '',

    /*
    | Specifie l'environement de travail dans lequel vous etes
    |   - dev / prod
    |	- auto : utilisera prod ou dev selon que le site soit en ligne ou pas. (la fonction is_online() est utiliser pour determiner la valeur à choisir)
    */
    'environment' => env('ENVIRONMENT', 'auto'),

    /** 
     * Specifie si les liens doivent etre en absolue (avec le nom de domaine) ou en relatif
     */
    'use_absolute_link' => true,

    /*
    |--------------------------------------------------------------------------
    | Caractères d'URL autorisés
    |--------------------------------------------------------------------------
    |
    | Cette option vous permet de spécifier les caractères autorisés dans vos URL.
    | Si quelqu'un tente de soumettre une URL contenant des caractères non autorisés, il recevra un message d'avertissement.
    |
    | Par mesure de sécurité, nous vous encourageons vivement à limiter les URL à un nombre de caractères aussi réduit que possible.
    |
    | Par défaut, seuls les caractères suivants sont autorisés : `a-z 0-9~%.:_-`
    |
    | Vous pouvez définir une chaîne vide pour autoriser tous les caractères, mais seulement si vous êtes fou.
    |
    | La valeur configurée est en fait un groupe de caractères d'expression régulière et sera utilisée comme suit : '/\A[<permitted_uri_chars>]+\z/iu'
    |
    | NE MODIFIEZ PAS CETTE VALEUR SI VOUS NE COMPRENEZ PAS PARFAITEMENT LES RÉPERCUSSIONS !
    */
    'permitted_uri_chars' => 'a-z 0-9~%.:_\-',

    /*
    |--------------------------------------------------------------- -------------------------
    | Langue par défaut
    |--------------------------------------------------------------- -------------------------
    |
    | Cela détermine quel ensemble de fichiers de langue doit être utilisé. S'assurer
    | il y a une traduction disponible si vous avez l'intention d'utiliser quelque chose d'autre
    | que l'anglais.
    */
    'language' => 'en',

    /**
     * ------------------------------------------------- -------------------------
     * Négocier les paramètres régionaux
     * ------------------------------------------------- -------------------------
     *
     * Si vrai, l'objet Request actuel déterminera automatiquement le
     * langue à utiliser en fonction de la valeur de l'en-tête Accept-Language.
     *
     * Si faux, aucune détection automatique ne sera effectuée.
     */
    'negotiate_locale' => true,

    /**
     * ------------------------------------------------- -------------------------
     * Paramètres régionaux pris en charge
     * ------------------------------------------------- -------------------------
     *
     * Si $negotiate_locale est vrai, ce tableau répertorie les paramètres régionaux pris en charge
     * par l'application par ordre décroissant de priorité. Si aucune correspondance n'est
     * trouvé, le premier paramètre régional sera utilisé.
     */
    'supported_locales' => ['fr', 'en'],

    /**
     * ------------------------------------------------- -------------------------
     * Fuseau horaire de l'application
     * ------------------------------------------------- -------------------------
     *
     * Le fuseau horaire par défaut qui sera utilisé dans votre application pour afficher
     * les dates avec l'assistant de date, et peuvent être récupérées via app_timezone()
     */
    'timezone' => 'Africa/Douala',

    /**
     * --------------------------------------------------------------- -------------------------
     * Jeu de caractères par défaut
     * --------------------------------------------------------------- -------------------------
     *
     * Cela détermine quel jeu de caractères est utilisé par défaut dans diverses méthodes
     * qui nécessitent la fourniture d'un jeu de caractères.
     *
     * Voir http://php.net/htmlspecialchars pour une liste des jeux de caractères pris en charge.
     */
    'charset' => 'UTF-8',

    /**
     * --------------------------------------------------------------- -------------------------
     * PROTOCOLE URI
     * --------------------------------------------------------------- -------------------------
     *
     * Si vrai, cela forcera chaque demande faite à cette application à être
     * via une connexion sécurisée (HTTPS). Si la demande entrante n'est pas
     * sécurisée, l'utilisateur sera redirigé vers une version sécurisée de la page
     * et l'en-tête HTTP Strict Transport Security (HSTS) sera défini.
     */
    'force_global_secure_requests' => false,
];
