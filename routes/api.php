<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ContactsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImagesController;
use App\Http\Controllers\OrganizationsController;
use App\Http\Controllers\ConversionsController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\FlatsController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\MeetingsController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SuppliersController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\RealizationController;
use App\Http\Controllers\RealizatorsController;
use App\Http\Controllers\ProfitController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\WorkersController;
use App\Http\Controllers\MarketController;
use App\Http\Controllers\PercentController;
use App\Http\Controllers\NakReturnController;
use Inertia\Inertia;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/api/asdasds', [RealizationController::class, 'getBeforeTime'])
    ->middleware('auth:api');
    
Route::get('/api/requests/update-before-time', [RealizationController::class, 'getBeforeTime'])
    ->middleware('auth:api');

Route::post('/api/requests/update-before-time', [RealizationController::class, 'updateBeforeTime'])
    ->middleware('auth:api');
    

