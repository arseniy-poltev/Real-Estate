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

Auth::routes(['verify' => true]);

Route::get('/', function () {
    return view('welcome');
});


Route::get('/', 'HomeController@index')->name('home');


/* Trend Analysis */
//Route::get('/trends-and-analysis/residential', 'TrendsAnalysis\SearchController@residential');
Route::get('/trends-and-analysis/report', 'TrendsAnalysis\SearchController@report');


/* Non residential */
Route::get('/trends-and-analysis/non-residential', 'TrendsAnalysis\NonResidentialController@search')->name('non-residential');
Route::get('/trends-and-analysis/non-residential/report/', 'TrendsAnalysis\NonResidentialController@report');
Route::post('/trends-and-analysis/non-residential/searchData', 'TrendsAnalysis\NonResidentialController@searchData');
Route::post('/trends-and-analysis/non-residential/report/refresh_setting', 'TrendsAnalysis\NonResidentialController@refresh_setting');
Route::post('/trends-and-analysis/non-residential/report/save_setting', 'TrendsAnalysis\NonResidentialController@save_setting');


/* Residential */
Route::get('/trends-and-analysis/residential', 'TrendsAnalysis\ResidentialController@search')->name('residential');
Route::get('/trends-and-analysis/residential/report', 'TrendsAnalysis\ResidentialController@report');
Route::post('/trends-and-analysis/residential/searchData', 'TrendsAnalysis\ResidentialController@searchData');
Route::post('/trends-and-analysis/residential/report/refresh_setting', 'TrendsAnalysis\ResidentialController@refresh_setting');
Route::post('/trends-and-analysis/residential/report/save_setting', 'TrendsAnalysis\ResidentialController@save_setting');
Route::get('/trends-and-analysis/residential/report/pdf', 'TrendsAnalysis\ResidentialController@printPDF');
Route::post('/trends-and-analysis/residential/report/search_units', 'TrendsAnalysis\ResidentialController@search_units');


/* Landed */
Route::get('/trends-and-analysis/landed', 'TrendsAnalysis\LandedController@search')->name('landed');
Route::get('/trends-and-analysis/landed/report/', 'TrendsAnalysis\LandedController@report');
Route::post('/trends-and-analysis/landed/report/refresh_setting', 'TrendsAnalysis\LandedController@refresh_setting');
Route::post('/trends-and-analysis/landed/report/save_setting', 'TrendsAnalysis\LandedController@save_setting');
Route::post('/trends-and-analysis/landed/searchData', 'TrendsAnalysis\LandedController@searchData');


/* Payment */
Route::get('/checkout', 'PaymentController@index')->middleware(['verified','auth']);
Route::get('/checkout/pay-with-paypal', 'PaymentController@createPayment')->middleware('auth');
Route::get('/checkout/confirm', 'PaymentController@confirmPayment')->name('confirm-payment')->middleware('auth');
