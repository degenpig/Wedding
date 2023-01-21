<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
use App\Http\Middleware\SecureAPI;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Admin User
Route::get('/adminuser/all', [App\Http\Controllers\AppAPI\AdminUserController::class, 'fetchAllRecord']);
Route::get('/adminuser/detail/{id}', [App\Http\Controllers\AppAPI\AdminUserController::class, 'showRecord']);
Route::post('/adminuser/addrecord', [App\Http\Controllers\AppAPI\AdminUserController::class, 'addRecord']);
Route::post('/adminuser/updaterecord', [App\Http\Controllers\AppAPI\AdminUserController::class, 'updateRecord']);
Route::post('/adminuser/deleterecord', [App\Http\Controllers\AppAPI\AdminUserController::class, 'deleteRecord']);

//Heading title
Route::get('/headingtitle/all', [App\Http\Controllers\AppAPI\HeadingTitleController::class, 'fetchAllRecord']);
Route::get('/headingtitle/detail/{id}', [App\Http\Controllers\AppAPI\HeadingTitleController::class, 'showRecord']);
Route::post('/headingtitle/addrecord', [App\Http\Controllers\AppAPI\HeadingTitleController::class, 'addRecord']);
Route::post('/headingtitle/updaterecord', [App\Http\Controllers\AppAPI\HeadingTitleController::class, 'updateRecord']);
Route::post('/headingtitle/deleterecord', [App\Http\Controllers\AppAPI\HeadingTitleController::class, 'deleteRecord']);


//Invitation Status
Route::get('/invitation_status/all', [App\Http\Controllers\AppAPI\InvitationStatusController::class, 'fetchAllRecord']);
Route::get('/invitation_status/detail/{id}', [App\Http\Controllers\AppAPI\InvitationStatusController::class, 'showRecord']);
Route::post('/invitation_status/addrecord', [App\Http\Controllers\AppAPI\InvitationStatusController::class, 'addRecord']);
Route::post('/invitation_status/updaterecord', [App\Http\Controllers\AppAPI\InvitationStatusController::class, 'updateRecord']);
Route::post('/invitation_status/deleterecord', [App\Http\Controllers\AppAPI\InvitationStatusController::class, 'deleteRecord']);

//Invitation Type
Route::get('/invitation_type/all', [App\Http\Controllers\AppAPI\InvitationTypeController::class, 'fetchAllRecord']);
Route::get('/invitation_type/detail/{id}', [App\Http\Controllers\AppAPI\InvitationTypeController::class, 'showRecord']);
Route::post('/invitation_type/addrecord', [App\Http\Controllers\AppAPI\InvitationTypeController::class, 'addRecord']);
Route::post('/invitation_type/updaterecord', [App\Http\Controllers\AppAPI\InvitationTypeController::class, 'updateRecord']);
Route::post('/invitation_type/deleterecord', [App\Http\Controllers\AppAPI\InvitationTypeController::class, 'deleteRecord']);

//Wedding User
Route::get('/wedding_user/all', [App\Http\Controllers\AppAPI\WeddingUserController::class, 'fetchAllRecord']);
Route::get('/wedding_user/detail/{id}', [App\Http\Controllers\AppAPI\WeddingUserController::class, 'showRecord']);
Route::post('/wedding_user/addrecord', [App\Http\Controllers\AppAPI\WeddingUserController::class, 'addRecord']);
Route::post('/wedding_user/updaterecord', [App\Http\Controllers\AppAPI\WeddingUserController::class, 'updateRecord']);
Route::post('/wedding_user/deleterecord', [App\Http\Controllers\AppAPI\WeddingUserController::class, 'deleteRecord']);


Route::post('actionInvitation', [App\Http\Controllers\AppAPI\WeddingAppController::class, 'actionInvitation']);

Route::middleware([secureAPI::class])->group(function(){

Route::prefix('app')->group(function () {

    Route::post('login', [App\Http\Controllers\AppAPI\WeddingAppController::class, 'login']);
    Route::post('logout', [App\Http\Controllers\AppAPI\WeddingAppController::class, 'logout'])->middleware('auth:wedding_user');
    Route::post('profile', [App\Http\Controllers\AppAPI\WeddingAppController::class, 'profile'])->middleware('auth:wedding_user');

    Route::post('createWeddingMember', [App\Http\Controllers\AppAPI\WeddingAppController::class, 'createWeddingMember']);

    Route::post('getWeddingMembers', [App\Http\Controllers\AppAPI\WeddingAppController::class, 'getWeddingMembers']);

    Route::post('createWeddingEvent', [App\Http\Controllers\AppAPI\WeddingAppController::class, 'createWeddingEvent']);

    Route::post('getWeddingEvents', [App\Http\Controllers\AppAPI\WeddingAppController::class, 'getWeddingEvents']);

    Route::post('createGroup', [App\Http\Controllers\AppAPI\WeddingAppController::class, 'createGroup']);

    Route::get('getAllGroup', [App\Http\Controllers\AppAPI\WeddingAppController::class, 'getAllGroup']);

    Route::get('getAllInviatationType', [App\Http\Controllers\AppAPI\WeddingAppController::class, 'getAllInviatationType']);

    Route::get('getAllInviatationStatus', [App\Http\Controllers\AppAPI\WeddingAppController::class, 'getAllInviatationStatus']);

    Route::post('createGuest', [App\Http\Controllers\AppAPI\WeddingAppController::class, 'createGuest']);

    Route::post('getAllGuest', [App\Http\Controllers\AppAPI\WeddingAppController::class, 'getAllGuest']);

    Route::post('sendInvitation', [App\Http\Controllers\AppAPI\WeddingAppController::class, 'sendInvitation']);

    Route::get('getInvitationStatistics', [App\Http\Controllers\AppAPI\WeddingAppController::class, 'getInvitationStatistics']);


});

    Route::prefix('admin')->group(function () {


        Route::post('createWeddingMember', [App\Http\Controllers\AdminAPI\WeddingAppController::class, 'createWeddingMember']);
        Route::post('getWeddingMembers', [App\Http\Controllers\AdminAPI\WeddingAppController::class, 'getWeddingMembers']);
        Route::post('createWeddingEvent', [App\Http\Controllers\AdminAPI\WeddingAppController::class, 'createWeddingEvent']);
        Route::post('getWeddingEvents', [App\Http\Controllers\AdminAPI\WeddingAppController::class, 'getWeddingEvents']);
        Route::post('createGroup', [App\Http\Controllers\AdminAPI\WeddingAppController::class, 'createGroup']);
        Route::get('getAllGroup', [App\Http\Controllers\AdminAPI\WeddingAppController::class, 'getAllGroup']);
        Route::get('getAllInviatationType', [App\Http\Controllers\AdminAPI\WeddingAppController::class, 'getAllInviatationType']);
        Route::get('getAllInviatationStatus', [App\Http\Controllers\AdminAPI\WeddingAppController::class, 'getAllInviatationStatus']);
        Route::post('createGuest', [App\Http\Controllers\AdminAPI\WeddingAppController::class, 'createGuest']);
        Route::post('deleteGuest', [App\Http\Controllers\AdminAPI\WeddingAppController::class, 'deleteGuest']);
        Route::post('getAllGuest', [App\Http\Controllers\AdminAPI\WeddingAppController::class, 'getAllGuest']);
        Route::post('sendInvitation', [App\Http\Controllers\AdminAPI\WeddingAppController::class, 'sendInvitation']);
        Route::get('getAllInviatationStatus', [App\Http\Controllers\AdminAPI\WeddingAppController::class, 'getAllInviatationStatus']);


    });

});
