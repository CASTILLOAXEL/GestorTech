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


use App\Models\Option;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/admin/users/profile', 'ProfileController@index')->name('profile');
Route::patch('/admin/users/profile/{user}', 'ProfileController@update')->name('profile.update');

//Route::get('/users', 'HomeController@index')->name('users');
//Route::get('/menu', 'HomeController@index')->name('menu');

Route::get('/contact', 'HomeController@contact')->name('contact');
Route::get('/', 'HomeController@index');

Route::get('login/{driver}', 'Auth\LoginController@redirectToProvider')->name('social_auth');
Route::get('login/{driver}/callback', 'Auth\LoginController@handleProviderCallback');


//si la aplicación no esta corriendo en consola
if (!app()->runningInConsole()){
    foreach (Option::whereNotNull('ruta')->get() as $op){
        Route::get($op->nombre, 'HomeController@index')->name($op->ruta);
    }
}

