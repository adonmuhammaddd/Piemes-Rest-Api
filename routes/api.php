<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([
    // 'middleware' => 'api',
    'prefix' => 'auth'
], function() {
    Route::post('register', 'UserController@register');
    Route::post('edit-data/{id}', 'UserController@update');
    Route::post('login', 'UserController@login');
    Route::get('user', 'UserController@getAuthenticatedUser');
    Route::post('refresh', 'UserController@refresh');
    Route::get('get-user', 'UserController@index');
    Route::get('get-user/{id}', 'UserController@show');
    Route::post('reset-password', 'PasswordResetRequestController@sendPasswordResetEmail');
    Route::post('search', 'SearchController@filterUser');
    Route::post('check-username', 'UserController@usernameCheck');
    Route::post('check-email', 'UserController@emailCheck');
    Route::post('send-mail/{id}', 'UserController@sendMail');
    Route::post('check-password/{id}', 'UserController@passwordCheck');
    Route::post('change-password/{id}', 'UserController@passwordChange');
});

// Route::group([
//     // 'middleware' => 'api',
//     'prefix' => 'dashboard'
// ], function ()
// {
    Route::get('dashboard', 'DashboardController@index');
    // Route::post('add-data', 'DashboardController@store');
    // Route::post('edit-data/{id}', 'DashboardController@update');
    // Route::delete('delete-data/{id}', 'DashboardController@destroy');
// });

Route::group([
    // 'middleware' => 'api',
    'prefix' => 'download'
], function() {
    Route::get('download-image/{imageFile}', 'SearchController@downloadImage');
});

Route::group([
    // 'middleware' => 'api',
    'prefix' => 'satker'
], function ()
{
    Route::get('get-data', 'SatkerController@index');
    Route::post('add-data', 'SatkerController@store');
    Route::post('edit-data/{id}', 'SatkerController@update');
    Route::delete('delete-data/{id}', 'SatkerController@destroy');
    Route::post('search', 'SearchController@filterSatker');
});

Route::group([
    // 'middleware' => 'api',
    'prefix' => 'kpa'
], function ()
{
    Route::post('add-data', 'KPAController@store');
    Route::post('edit-data/{id}', 'KPAController@update');
    Route::delete('delete-data/{id}', 'KPAController@destroy');
    Route::get('get-data', 'KPAController@index');
    Route::get('get-data-by/{id}', 'KPAController@show');
    Route::post('search', 'SearchController@filterKpa');
});

Route::group([
    // 'middleware' => 'api',
    'prefix' => 'ppk'
], function ()
{
    Route::post('add-data', 'PPKController@store');
    Route::post('edit-data/{id}', 'PPKController@update');
    Route::delete('delete-data/{id}', 'PPKController@destroy');
    Route::get('get-data', 'PPKController@index');
    Route::get('get-data-by/{id}', 'PPKController@show');
    Route::post('search', 'SearchController@filterPpk');
});

Route::group([
    // 'middleware' => 'api',
    'prefix' => 'news'
], function ()
{
    Route::post('add-data', 'NewsController@store');
    Route::post('edit-data/{id}', 'NewsController@update');
    Route::post('is-active/{id}', 'NewsController@isActive');
    Route::delete('delete-data/{id}', 'NewsController@destroy');
    Route::get('get-data', 'NewsController@index');
    Route::get('get-data-by/{id}', 'NewsController@show');
    Route::get('get-active', 'NewsController@getActive');
    Route::post('search', 'SearchController@filterNews');
});

Route::group([
    // 'middleware' => 'api',
    'prefix' => 'tipedokumen'
], function ()
{
    Route::post('add-data', 'TipeDokumenController@store');
    Route::post('edit-data/{id}', 'TipeDokumenController@update');
    Route::delete('delete-data/{id}', 'TipeDokumenController@destroy');
    Route::get('get-data', 'TipeDokumenController@index');
    Route::get('get-data-by/{id}', 'TipeDokumenController@show');
    Route::post('search', 'SearchController@filterTipeDokumen');
});

Route::group([
    // 'middleware' => 'api',
    'prefix' => 'jenisdokumentemuan'
], function ()
{
    Route::post('add-data', 'JenisDokumenTemuanController@store');
    Route::post('edit-data/{id}', 'JenisDokumenTemuanController@update');
    Route::delete('delete-data/{id}', 'JenisDokumenTemuanController@destroy');
    Route::get('get-data', 'JenisDokumenTemuanController@index');
    Route::get('get-data-by/{id}', 'JenisDokumenTemuanController@show');
    Route::post('search', 'SearchController@filterJenisDokumenTemuan');
});

Route::group([
    // 'middleware' => 'api',
    'prefix' => 'dokumen'
], function ()
{
    Route::post('add-data', 'DokumenController@store');
    Route::post('edit-data', 'DokumenController@update');
    Route::delete('delete-data/{id}', 'DokumenController@destroy');
    Route::get('get-data', 'DokumenController@index');
    Route::get('get-data-by/{id}', 'DokumenController@show');
    Route::post('search', 'SearchController@filterDokumen');
    Route::get('test-mail', 'DokumenController@testMail');
});

Route::group([
    // 'middleware' => 'api',
    'prefix' => 'dokumentemuan'
], function ()
{
    Route::post('add-data', 'DokTemuanController@store');
    Route::post('edit-data/{id}', 'DokTemuanController@update');
    Route::delete('delete-data/{id}', 'DokTemuanController@destroy');
    Route::get('get-data', 'DokTemuanController@index');
    Route::get('grid-view/{jenisDokumen}', 'DokTemuanController@ajax_list');
    Route::get('get-data-grid/{id}', 'DokTemuanController@detailTemuan');
    Route::get('get-detail-data/{id}', 'DokTemuanController@show');
    Route::get('get-preview-dokumen/{id}', 'DokTemuanController@previewDokumen');
    Route::get('get-detail-tindaklanjut/{id}', 'DokTemuanController@detailTindakLanjut');
    Route::get('get-detail-parent-grid/{id}', 'DokTemuanController@parentGridDetail');
    Route::post('search', 'SearchController@filterDokTemuan');
    Route::get('search/{fileDokumen}', 'SearchController@downloadFileApproval');
});

Route::group([
    // 'middleware' => 'api',
    'prefix' => 'tindaklanjut'
], function ()
{
    Route::post('add-data', 'TindakLanjutController@store');
    Route::post('edit-data/{id}', 'TindakLanjutController@update');
    Route::post('add-data-respon/{id}', 'TindakLanjutController@createRespon');
    Route::delete('delete-data/{id}', 'TindakLanjutController@destroy');
    Route::get('get-data', 'TindakLanjutController@index');
    Route::get('grid-view', 'TindakLanjutController@gridView');
    Route::get('get-data-grid/{id}', 'TindakLanjutController@detailTindakLanjut');
    Route::get('get-detail-data/{id}/{temuan_id}', 'TindakLanjutController@show');
    Route::post('search', 'SearchController@filterTindakLanjut');
    Route::get('search/{fileDokumen}', 'SearchController@downloadFileTindakLanjut');
});

Route::group([
    // 'middleware' => 'api',
    'prefix' => 'revisitindaklanjut'
], function ()
{
    Route::post('add-data', 'RevisiTindakLanjutController@store');
    Route::post('edit-data/{id}', 'RevisiTindakLanjutController@update');
    Route::delete('delete-data/{id}', 'RevisiTindakLanjutController@destroy');
    Route::get('get-data', 'RevisiTindakLanjutController@index');
    Route::get('get-data-by/{id}', 'RevisiTindakLanjutController@show');
    Route::post('search', 'SearchController@filterRevisiTindakLanjut');
    Route::get('search/{fileDokumen}', 'SearchController@downloadFileTemuan');
});

Route::group([
    // 'middleware' => 'api',
    'prefix' => 'respontindaklanjut'
], function ()
{
    Route::post('add-data', 'ResponDokumenTemuanController@store');
    Route::post('edit-data/{id}', 'ResponDokumenTemuanController@update');
    Route::delete('delete-data/{id}', 'ResponDokumenTemuanController@destroy');
    Route::get('get-data', 'ResponDokumenTemuanController@index');
    Route::get('get-data-by/{id}', 'ResponDokumenTemuanController@show');
    Route::post('search', 'SearchController@filterResponDokumenTemuan');
    Route::get('search/{fileDokumen}', 'SearchController@downloadFileTemuan');
});

Route::group([
    // 'middleware' => 'api',
    'prefix' => 'log'
], function ()
{
    Route::get('get-data', 'LogController@index');
    Route::post('edit-data/{id}', 'LogController@update');
});

Route::group([
    // 'middleware' => 'api',
    'prefix' => 'testing'
], function ()
{
    Route::post('test-post', 'TestingController@testPost');
    Route::get('test-get', 'TestingController@testGet');
});

Route::post('change-password', 'ChangePasswordController@passwordResetProcess');