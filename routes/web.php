<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CustomerContoller;
use App\Http\Controllers\VendorContoller;
use App\Http\Controllers\ClientContoller;


Auth::routes();
Route::middleware(['auth'])->group(function () {
 
Route::get('/'                          , [HomeController::class, 'index'])->name('home');
});
Route::get('logout'                     , [HomeController::class, 'logout'])->name('logout');
Route::get('/superAdmin'                , [HomeController::class, 'superAdmin'])->name('superAdmin');

Route::get('/AddClient'                 , [ClientContoller::class, 'AddClient'])->name('AddClient');
Route::get('/ViewClient'                , [ClientContoller::class, 'ViewClient'])->name('ViewClient');
Route::post('/storeClient'              , [ClientContoller::class, 'storeClient'])->name('storeClient');

Route::get('/AddCustomer'               , [CustomerContoller::class, 'AddCustomer'])->name('AddCustomer');
Route::get('/ViewCustomer'              , [CustomerContoller::class, 'ViewCustomer'])->name('ViewCustomer');
Route::post('/storeCustomer'            , [CustomerContoller::class, 'storeCustomer'])->name('storeCustomer');
Route::get('/editCustomer/{id}'         , [CustomerContoller::class, 'edit'])->name('editCustomer');
Route::put('/updateCustomer/{id}'       , [CustomerContoller::class, 'update'])->name('updateCustomer');
Route::get('/deleteCustomer/{id}'       , [CustomerContoller::class, 'destroy'])->name('deleteCustomer');


Route::get('/AddVendor'                 , [VendorContoller::class, 'AddVendor'])->name('AddVendor');
