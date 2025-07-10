<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TicketBesoinController;
use App\Http\Controllers\Api\TicketCategorieController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\AppelOffreController;
use App\Http\Controllers\Api\CompetenceController;
use App\Http\Controllers\Api\PrestataireController;
use App\Http\Controllers\Api\DashboardPrestataireController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user'])->name('user');
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Dashboard
    Route::get('/dashboard-stats/employe', [DashboardController::class, 'employeStats']);
    Route::get('/dashboard-stats/gestionnaire', [DashboardController::class, 'gestionnaireStats']);
    
    Route::get('/tickets', [TicketBesoinController::class, 'index']);
    Route::get('/tickets/{ticketBesoin}', [TicketBesoinController::class, 'show']);
    Route::post('/tickets', [TicketBesoinController::class, 'store']);
    Route::put('/tickets/{ticketBesoin}', [TicketBesoinController::class, 'update']);
    Route::delete('/tickets/{ticketBesoin}', [TicketBesoinController::class, 'destroy']);
    Route::post('/tickets/{ticketBesoin}/assign-to-gestionnaire', [TicketBesoinController::class, 'assignToGestionnaire']);

    // Routes pour les appels d'offres
    Route::get('/appel-offres', [AppelOffreController::class, 'index']);
    Route::get('/appel-offres/{appelOffre}', [AppelOffreController::class, 'show']);
    Route::post('/appel-offres', [AppelOffreController::class, 'store']);
    Route::get('/appel-offres/{appelOffre}/suggest-prestataires', [AppelOffreController::class, 'suggestPrestataires']);
    Route::post('/appel-offres/{appelOffre}/inviter', [AppelOffreController::class, 'inviter']);
    Route::delete('/appel-offres/{appelOffre}', [AppelOffreController::class, 'destroy']);
    Route::put('/appel-offres/{appelOffre}', [AppelOffreController::class, 'update']);

    // Routes pour les compétences
    Route::get('/competences', [CompetenceController::class, 'index']);

    // Routes pour les prestataires (CRUD)
    Route::apiResource('prestataires', PrestataireController::class);
    Route::middleware('auth:sanctum')->get('/dashboard-prestataire', [DashboardPrestataireController::class, 'index']);
    


    // Routes pour les catégories de ticket
    Route::get('/ticket-categories', [TicketCategorieController::class, 'index']);
}); 