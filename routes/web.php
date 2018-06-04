<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group([
    'name' => 'api.infusionsoft_test_',
], function () {
    Route::get(
        '/authorize_infusionsoft',
        'InfusionsoftController@authorizeInfusionsoft'
    )->name('infusionsoft.authorize');

    Route::get(
        '/infusionsoft_test_get_by_email/{email}',
        'InfusionsoftController@testInfusionsoftIntegrationGetEmail'
    )->name('email');

    Route::get(
        '/infusionsoft_test_add_tag/{contact_id}/{tag_id}',
        'InfusionsoftController@testInfusionsoftIntegrationAddTag'
    )->name('tag');

    Route::get(
        '/infusionsoft_test_get_all_tags',
        'InfusionsoftController@testInfusionsoftIntegrationGetAllTags'
    )->name('get_all_tags');
});
