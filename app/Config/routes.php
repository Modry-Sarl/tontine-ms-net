<?php
/*
| -------------------------------------------------- -----------------
| PARAMÈTRES D'ITINÉRAIRE DE L'APPLICATION
| -------------------------------------------------- -----------------
| Ce fichier contiendra les paramètres de routage de votre application.
|
| Pour des instructions complètes, veuillez consulter la « Configuration de l'itinéraire » dans le guide de l'utilisateur.
*/

/** @var BlitzPHP\Router\RouteCollection $routes */

use App\Controllers\AdminerController;
use App\Controllers\BankingController;
use App\Controllers\PaymentController;
use App\Controllers\RegisterController;
use BlitzPHP\Facades\Route;
use BlitzPHP\Router\RouteBuilder;

/**
 * --------------------------------------------------------------------
 * Définition des routes
 * --------------------------------------------------------------------
 */
$routes->get('/', fn() => redirect()->route('dashboard'));


Route::middleware(['guest'])->group(function(RouteBuilder $route) {
    $route->name('login')->view('/login', 'auth/login');
    $route->post('/login', 'AuthController::login');
});

Route::name('logout')->delete('/logout', 'AuthController::logout');

Route::prefix('dashboard')->middleware(['session'])->group(function(RouteBuilder $route) {
    $route->name('dashboard')->get('/', 'DashboardController::index');

    $route->middleware(['session'])->prefix('register')->controller(RegisterController::class)->group(function(RouteBuilder $route) {
        $route->name('register')->get('/', 'form');
        $route->name('inscriptions')->get('/inscriptions', 'inscriptions');
        $route->post('/', 'process');
    });

    $route->middleware(['session'])->prefix('banking')->controller(BankingController::class)->group(function(RouteBuilder $route) {
        $route->name('recharge')->form('/recharge', 'recharge');
        $route->name('retrait')->form('/retrait', 'retrait');
        $route->name('transfert')->form('/transfert', 'transfert');
    });

    $route->middleware(['session'])->prefix('adminer')->controller(AdminerController::class)->group(function(RouteBuilder $route) {
        $route->name('infos')->get('/infos', 'infos');
        $route->name('progression')->get('/progression', 'progression');
        // $route->name('filleuls')->get('/filleuls', 'filleuls');
        $route->name('comptes')->get('/comptes', 'comptes');
    });
});


Route::prefix('payment')->controller(PaymentController::class)->group(function(RouteBuilder $route) {
    $route->name('payment.notify')->match(['get', 'post'], '/notify/(:any)', 'notify');
});
