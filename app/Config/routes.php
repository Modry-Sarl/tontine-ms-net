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

use App\Controllers\Admin\MembresController;
use App\Controllers\AdminerController;
use App\Controllers\BankingController;
use App\Controllers\PaymentController;
use App\Controllers\RegisterController;
use BlitzPHP\Facades\Route;

/**
 * --------------------------------------------------------------------
 * Définition des routes
 * --------------------------------------------------------------------
 */
$routes->get('/', fn() => redirect()->route('dashboard'));
$routes->get('/pass', fn() => service('passwords')->hash($_GET['password'] ?? ''));

auth()->routes($routes, ['except' => ['login', 'register', 'logout', 'auth-actions']]);

Route::middleware(['guest'])->group(function() {
    Route::name('login')->view('/login', 'auth/login');
    Route::post('/login', 'AuthController::login');
});

Route::name('logout')->delete('/logout', 'AuthController::logout');

Route::prefix('payment')->controller(PaymentController::class)->group(function() {
    Route::name('payment.notify')->match(['get', 'post'], '/notify/(:any)', 'notify');
});

Route::middleware('session')->group(function() {    
    Route::name('login.admin')->view('/login-admin', 'auth/login-admin');
    Route::post('/login-admin', 'AuthController::loginAdmin');
    
    Route::prefix('dashboard')->group(function() {
        Route::name('dashboard')->get('/', 'DashboardController::index');

        Route::prefix('register')->controller(RegisterController::class)->group(function() {
            Route::name('register')->get('/', 'form');
            Route::name('inscriptions')->get('/inscriptions', 'inscriptions');
            Route::post('/', 'process');
        });

        Route::prefix('banking')->controller(BankingController::class)->group(function() {
            Route::name('recharge')->form('/recharge', 'recharge');
            Route::name('retrait')->form('/retrait', 'retrait');
            // Route::name('transfert')->form('/transfert', 'transfert');
        });

        Route::prefix('adminer')->controller(AdminerController::class)->group(function() {
            Route::name('infos')->get('/infos', 'infos');
            Route::name('progression')->get('/progression', 'progression');
            Route::name('comptes')->get('/comptes', 'comptes');
            Route::name('filleuls')->get('/filleuls', 'filleuls');
            Route::name('arbre-filleul')->match(['get', 'post'], '/filleuls/arbre', 'arbre');
        });
    });

    Route::prefix('admin')->middleware('group:admin')->namespace('App\Controllers\Admin')->group(function() {
        Route::name('admin.dashboard')->get('/', 'DashboardController::index');
        Route::controller('MembresController')->group(function() {
            Route::name('admin.membres')->get('/membres', 'index');
            Route::name('admin.membre')->get('/membre', 'show');
            Route::name('admin.membre.config')->form('/membre-config', 'config');
            Route::name('admin.membre.add')->form('/membre-add', 'add');
        });
        Route::controller('TransactionsController')->group(function() {
            Route::name('admin.transactions.approbations')->form('/approbations', 'approbations');
        });
    });
});
