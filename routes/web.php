<?php

use Illuminate\Support\Facades\Route;

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
Route::get('/aboutus', 'HomeController@aboutus')->name('aboutus');
Route::get('/contactus', 'HomeController@contactus')->name('contactus');
Route::post('/contactus', 'HomeController@storecontactus')->name('store.contactus');

Route::any('/dashboard', 'UserController@dashboard')->name('dashboard')->middleware('checkpaid');
Route::any('/dashboard/payment', 'UserController@payment')->name('payment')->middleware('checkpaid');
Route::any('/dashboard/archieve', 'UserController@archieve')->name('archieve')->middleware('checkpaid');
Route::any('/dashboard/settings', 'UserController@settings')->name('settings')->middleware('checkpaid');
Route::any('/dashboard/deadline', 'UserController@deadline')->name('deadline')->middleware('checkpaid');

Route::any('/dashboard/inbox', 'UserController@inbox')->name('inbox');
Route::get('/dashboard/inbox/{id}', 'UserController@messageview');
Route::get('/dashboard/newproject', 'UserController@newproject')->name('newproject')->middleware('checkpaid');
Route::post('/dashboard/storeproject', 'UserController@storeproject')->name('storeproject');
Route::get('/dashboard/project/{id}', 'UserController@projectview');
Route::post('/dashboard/deleteproject', 'UserController@deleteproject')->name('deleteproject');
Route::post('/dashboard/movetoarchieve', 'UserController@movetoarchieve')->name('movetoarchieve');
Route::post('/dashboard/deletetask', 'UserController@deletetask')->name('deletetask');
Route::post('/dashboard/deleteuser', 'UserController@deleteuser')->name('deleteuser');
Route::post('/dashboard/deleteplan', 'UserController@deleteplan')->name('deleteplan');
Route::get('/dashboard/plan', 'UserController@plan')->name('plan');
Route::get('/dashboard/user', 'UserController@user')->name('user');
Route::get('/dashboard/managerplan', 'UserController@managerplan')->name('managerplan');



Route::post('/ajax/update_paypal', 'UserController@update_paypal');
Route::post('/ajax/update_square', 'UserController@update_square');
Route::post('/ajax/update_password', 'UserController@update_password');
Route::post('/ajax/update_project', 'UserController@update_project');
Route::post('/ajax/create_task', 'UserController@create_task');
Route::post('/ajax/update_task', 'UserController@update_task');
Route::post('/ajax/uploadattach', 'UserController@uploadattach');
Route::post('/ajax/create_comment', 'UserController@create_comment');
Route::post('/ajax/create_invite', 'UserController@create_invite');
Route::post('/ajax/create_user', 'UserController@create_user');
Route::post('/ajax/edit_user', 'UserController@edit_user');
Route::post('/ajax/create_plan', 'UserController@create_plan');
Route::get('/ajax/delete_message', 'UserController@delete_message');
Route::get('/ajax/complete_task', 'UserController@complete_task');
Route::get('/ajax/delete_comment', 'UserController@delete_comment');
Route::get('/ajax/delete_project_all', 'UserController@delete_project_all');



Route::post('handle-payment', 'PayPalPaymentController@handlePayment')->name('make.payment');
Route::get('cancel-payment', 'PayPalPaymentController@paymentCancel')->name('cancel.payment');
Route::get('payment-success', 'PayPalPaymentController@paymentSuccess')->name('success.payment');

Route::post('/charge', 'SquareController@charge')->name('make.charge');





