<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::auth();

Route::get('/', ['as' => 'index', 'uses' => 'CnpjController@index']);

Route::get('consultar', ['as' => 'consultar', 'uses' => 'CnpjController@consultar']);

Route::post('consultar', 'CnpjController@show');

Route::post('salvar', 'CnpjController@store');

Route::delete('destroy/{consulta}', ['as' => 'destroy', 'uses' => 'CnpjController@destroy']);