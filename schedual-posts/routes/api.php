<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\api\CronController;
use App\Http\Controllers\api\PostController;
use App\Http\Controllers\api\RoleController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\AccountController;
use App\Http\Controllers\api\ServiceController;
use App\Http\Controllers\api\TwitterController;
use App\Http\Controllers\api\YoutubeController;
use App\Http\Controllers\api\AnalyticsController;
use App\Http\Controllers\api\DashboardController;
use App\Http\Controllers\api\TimeThinkController;
use App\Http\Controllers\api\PermissionController;
use App\Http\Controllers\api\SubscriberController;
use App\Http\Controllers\api\PublishPostController;
use App\Http\Controllers\api\YoutubeCategoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


//public routes
Route::post('/auth/register',[UserController::class,'register']);
Route::post('/auth/login', [UserController::class,'login']); 
Route::resource('subscribers', SubscriberController::class);

//protected routes
Route::middleware('auth:sanctum')->group(function () 
{
    Route::post('/logout', [UserController::class, 'logout']);

    Route::resource('dashboard', DashboardController::class); 
    Route::resource('analytics', AnalyticsController::class); 
    Route::resource('categories', YoutubeCategoryController::class);
    Route::resource('accounts', AccountController::class);
    Route::resource('timeThink', TimeThinkController::class);
    Route::resource('posts', PostController::class);
    Route::resource('publishPosts', PublishPostController::class);
    Route::resource('cron', CronController::class);
    
    Route::resource('twitter', TwitterController::class);
    Route::resource('youtube', YoutubeController::class);
});

Route::group(['middleware' => ['admin','auth:sanctum']], function () { 
    Route::resource('services', ServiceController::class); 
    Route::resource('users', UserController::class); 
    Route::get('/users/search/{name}', [UserController::class, 'search']);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
}); 

Route::middleware('web')->group(function () {

    Route::get('/auth/redirect', function () {
        return Socialite::driver('facebook')->redirect();
    })->name('faceLogin');
    Route::get('/auth/callback', [FacebookController::class, 'callback']);

    Route::get('auth/instagram',[InstagramController::class, 'redirectToInstagramProvider'])->name('instagram.login');
    Route::get('auth/instagram/callback', [InstagramController::class, 'instagramProviderCallback'])->name('instagram.login.callback');

    Route::get('auth/twitter',[TwitterController::class, 'twitterRedirect']);
    Route::get('auth/twitter/callback', [TwitterController::class, 'twitterCallback'])->name('twitter.callback');

    Route::get('auth/youtube',[YoutubeController::class, 'redirectToYoutube']);
    Route::get('auth/youtube/callback', [YoutubeController::class, 'YoutubeCallback'])->name('youtube.callback');
});