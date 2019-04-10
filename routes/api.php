<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/hierarchy/data/index','HierarchyController@dataHierarchy');
Route::get('/list/data/index','ListController@dataList');
Route::get('/data/positions','EmployeeController@dataPositions')->middleware('auth:api');
Route::get('/data/heads','EmployeeController@dataHeads')->middleware('auth:api');
Route::get('/employee/data/list','EmployeeController@index')->middleware('auth:api');
Route::post('/employee/data/create','EmployeeController@store')->middleware('auth:api');
Route::get('/employee/data/edit/{id}','EmployeeController@show')->middleware('auth:api');
Route::put('/employee/data/update/{id}','EmployeeController@update')->middleware('auth:api');
Route::delete('/employee/data/delete/{id}','EmployeeController@destroy')->middleware('auth:api');
Route::get('/hierarchy/data/tree/{id}','HierarchyController@dataLoading');
