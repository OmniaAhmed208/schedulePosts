<?php

use Facebook\Facebook;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TwitterController;
use App\Http\Controllers\YoutubeController;
use App\Http\Controllers\FacebookController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InstagramController;
use App\Http\Controllers\NewsLetterController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PostStatusController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\UploadFilesController;
use App\Http\Controllers\YoutubeCategoryController;
use App\Http\Controllers\RolesPermissionsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::middleware(['guest'])->get('/', function () {
    return view('welcome');
});

Route::post('/auth/register',[UserController::class,'register']);
Route::post('email_verification', [UserController::class,'email_verification']);
Route::post('code_verification_from_profile', [UserController::class,'code_verification_from_profile']);

Route::post('/forgetPassword', [UserController::class,'forgetPassword']);
Route::post('/passwordCode', [UserController::class,'passwordCode']);
Route::post('/resetPassword', [UserController::class,'resetPassword']);

Auth::routes();
Route::group(['middleware' => ['auth']], function ()
{
    Route::post('/test', [DashboardController::class, 'test'])->name('test');
    Route::get('/home', [DashboardController::class, 'index'])->name('admin.index');
    Route::put('updatePassword/{id}', [UserController::class,'updatePassword']);
    Route::post('chartJS/{id}', [DashboardController::class,'chartJS']);
    
    Route::post('/uploadFiles', [UploadFilesController::class, 'store']);
    Route::match(['post', 'delete'], '/removeFiles', [UploadFilesController::class, 'destroy']);
    
    Route::resource('dashboard', DashboardController::class)->only(['show'])->middleware('permission:dashboard.forEachUser');
    
    Route::resource('users', UserController::class);
    Route::get('users', [UserController::class,'index'])->middleware('permission:users.all');
    
    Route::resource('services', ServiceController::class)->middleware('permission:services');
    Route::resource('accounts', AccountController::class);
    Route::resource('posts', PostController::class);
    Route::resource('subscribers', SubscriberController::class)->only(['index'])->middleware('permission:subscribers.all');
    Route::resource('newsLetter', NewsLetterController::class); // permissions in controller

    Route::resource('facebook', FacebookController::class);
    Route::resource('twitter', TwitterController::class);
    Route::resource('youtube', YoutubeController::class);
    Route::resource('instagram', InstagramController::class);
    Route::resource('youtubeCategories', YoutubeCategoryController::class);
    Route::resource('media', MediaController::class);

    Route::resource('rolePermissions', RolesPermissionsController::class)->only(['index'])->middleware('permission:roles.show');
    Route::post('/assignRoleToPermissions/{role_id}', [RolesPermissionsController::class, 'assignRoleToPermissions'])
    ->middleware('permission:roles.assign_role_to_permissions');
    Route::post('/assignUserToRoles/{userId}', [RolesPermissionsController::class, 'assignUserToRoles'])
    ->middleware('permission:roles.assign_roles_to_user');

    Route::resource('roles', RoleController::class)->only(['store', 'update'])
    ->middleware([
        'users.store' => 'permission:roles.add',
        'users.update' => 'permission:roles.edit',
    ]);

    Route::resource('permissions', PermissionController::class);

    Route::post('/getPages', [FacebookController::class, 'getPages'])->name('getPages');
    Route::get('/pagesFacebook', [FacebookController::class, 'pagesFacebook'])->name('pagesFacebook');
    Route::get('/policy', [DashboardController::class, 'policy']);
    Route::get('/checkPostStatus', [PostStatusController::class, 'checkPostStatus'])->name('checkPostStatus'); //cron
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


// You can also apply middleware to specific actions using the "only" or "except" methods
// For example:
// Route::resource('users', UserController::class)->only(['index', 'show'])->middleware('your-middleware');

// Or
// Route::resource('users', UserController::class)->except(['create', 'store'])->middleware('your-middleware')
Route::group(['middleware' => ['admin','auth']], function ()
{ // for admin only to login on them
});
