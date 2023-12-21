<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\api\PostController;
use App\Http\Controllers\api\RoleController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\AccountController;
use App\Http\Controllers\api\ServiceController;
use App\Http\Controllers\api\TwitterController;
use App\Http\Controllers\api\YoutubeController;
use App\Http\Controllers\api\FacebookController;
use App\Http\Controllers\api\DashboardController;
use App\Http\Controllers\api\InstagramController;
use App\Http\Controllers\api\NewsLetterController;
use App\Http\Controllers\api\PermissionController;
use App\Http\Controllers\api\SubscriberController;
use App\Http\Controllers\api\UploadFilesController;
use App\Http\Controllers\api\YoutubeCategoryController;
use App\Http\Controllers\api\RolesPermissionsController;

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
Auth::routes();
Route::get('/home', [DashboardController::class, 'index'])->name('admin.index');

Route::post('/auth/login', [UserController::class,'login']);

Route::post('/auth/register',[UserController::class,'register']);
Route::post('email_verification', [UserController::class,'email_verification']);

Route::post('/forgetPassword', [UserController::class,'forgetPassword']);
Route::post('/passwordCode', [UserController::class,'passwordCode']);
Route::post('/resetPassword', [UserController::class,'resetPassword']);
Route::get('newsletter', [NewsLetterController::class,'index']);
Route::post('subscribers', [SubscriberController::class,'store']);

Route::middleware('auth:sanctum')->group(function ()
{
    Route::post('send_code_verification', [UserController::class,'send_code_verification']);
    Route::post('/logout', [UserController::class, 'logout']);
    Route::post('updatePassword', [UserController::class,'updatePassword']);

    Route::post('/uploadFiles', [UploadFilesController::class, 'store'])->middleware('cors');
    Route::match(['post', 'delete'], '/removeFiles', [UploadFilesController::class, 'destroy'])->middleware('cors');
    
    Route::resource('dashboard', DashboardController::class);
    Route::get('dashboard/{id}', [DashboardController::class,'show'])->middleware('permission:dashboard.forEachUser');
    
    Route::resource('users', UserController::class);
    Route::get('users', [UserController::class,'index'])->middleware('permission:users.all');
    Route::get('/users/search/{name}', [UserController::class, 'search']);
    Route::get('subscribers', [SubscriberController::class,'index'])->middleware('permission:subscribers.all');
    
    Route::resource('services', ServiceController::class)->middleware('permission:services');
    Route::resource('accounts', AccountController::class);
    Route::resource('posts', PostController::class);

    // Route::resource('newsletter', NewsLetterController::class); // permissions in controller
    Route::post('newsletter', [NewsLetterController::class, 'store']);
    Route::put('newsletter', [NewsLetterController::class, 'update']);
    Route::delete('newsletter', [NewsLetterController::class, 'destroy']);
    
    Route::resource('twitter', TwitterController::class);
    Route::resource('instagram', InstagramController::class);
    Route::resource('facebook', FacebookController::class);
    Route::resource('youtube', YoutubeController::class);
    Route::resource('categories', YoutubeCategoryController::class);
    
    Route::resource('roles', RoleController::class);

    Route::resource('permissions', PermissionController::class);
    Route::resource('rolePermissions', RolesPermissionsController::class)->only(['index'])->middleware('permission:roles.show');
    Route::post('/assignUserToRoles/{userId}', [RolesPermissionsController::class, 'assignUserToRoles'])
    ->middleware('permission:roles.assign_roles_to_user');
    Route::post('/assignRoleToPermissions/{role_id}', [RolesPermissionsController::class, 'assignRoleToPermissions'])
    ->middleware('permission:roles.assign_role_to_permissions');
    Route::get('checkPermission', [RolesPermissionsController::class, 'checkPermission']);

});

// Route::group(['middleware' => ['admin','auth:sanctum']], function () {});

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
