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
Route::get("/user/center",'User\UserController@center');//用户中心
Route::get("/login",'User\UserController@loginShow');//用户登录
Route::post("login",'User\UserController@login');//用户登录
Route::get("/register",'User\UserController@regShow');//用户注册
Route::post("/register",'User\UserController@register');//用户注册
Route::post("/apiLogin",'User\UserController@apiLogin');//apipossport登录

Route::get("/quit",'User\UserController@quit');//退出