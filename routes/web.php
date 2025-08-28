<?php

use Illuminate\Support\Facades\Route;
use  Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CustomerContoller;
use App\Http\Controllers\VendorContoller;
use App\Http\Controllers\ClientContoller;
use App\Http\Controllers\WorkOrderController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\MachinerecordController;
use App\Http\Controllers\SetupSheetController;
use App\Http\Controllers\MaterialorderController;





Auth::routes();
Route::middleware(['auth'])->group(function () {
 
Route::get('/'                          , [HomeController::class, 'index'])->name('home');
// Route::get('/superAdmin'                , [HomeController::class, 'superAdmin'])->name('superAdmin');
// Route::get('logout'                     , [HomeController::class, 'logout'])->name('logout');

});


 

Route::get('/AddClient'                 , [ClientContoller::class, 'AddClient'])->name('AddClient');
Route::get('/ViewClient'                , [ClientContoller::class, 'ViewClient'])->name('ViewClient');
Route::post('/storeClient'              , [ClientContoller::class, 'storeClient'])->name('storeClient');
Route::get('/editClient/{id}'           , [ClientContoller::class, 'edit'])->name('editClient');
Route::put('/updateClient/{id}'         , [ClientContoller::class, 'update'])->name('updateClient');
Route::get('/deleteClient/{id}'         , [ClientContoller::class, 'destroy'])->name('deleteClient');
Route::post('/updateClientStatus'       , [ClientContoller::class,  'updateClientStatus'])->name('updateClientStatus');
 


Route::get('/AddCustomer'                , [CustomerContoller::class, 'AddCustomer'])->name('AddCustomer');
Route::get('/ViewCustomer'               , [CustomerContoller::class, 'ViewCustomer'])->name('ViewCustomer');
Route::post('/storeCustomer'             , [CustomerContoller::class, 'storeCustomer'])->name('storeCustomer');
Route::get('/editCustomer/{id}'          , [CustomerContoller::class, 'edit'])->name('editCustomer');
Route::put('/updateCustomer/{id}'        , [CustomerContoller::class, 'update'])->name('updateCustomer');
Route::get('/deleteCustomer/{id}'        , [CustomerContoller::class, 'destroy'])->name('deleteCustomer');
Route::post('/updateCustomerStatus'      , [CustomerContoller::class, 'updateCustomerStatus'])->name('updateCustomerStatus');

                                            

Route::get('/AddVendor'                  , [VendorContoller::class, 'AddVendor'])->name('AddVendor');
Route::post('/storeVendor'               , [VendorContoller::class, 'storeVendor'])->name('storeVendor');
Route::get('/ViewVendor'                 , [VendorContoller::class, 'ViewVendor'])->name('ViewVendor');
Route::get('/editVendor/{id}'            , [VendorContoller::class, 'edit'])->name('editVendor');
Route::put('/updateVendor/{id}'          , [VendorContoller::class, 'update'])->name('updateVendor');
Route::get('/deleteVendor/{id}'          , [VendorContoller::class, 'destroy'])->name('deleteVendor');

// AddWorkOrder

Route::get('/AddWorkOrder'               , [WorkOrderController::class, 'AddWorkOrder'])->name('AddWorkOrder');
Route::get('/ViewWorkOrder'              , [WorkOrderController::class, 'ViewWorkOrder'])->name('ViewWorkOrder');
Route::post('/storeWorkEntry'            , [WorkOrderController::class, 'storeWorkEntry'])->name('storeWorkEntry');
Route::get('/editWorkOrder/{id}'         , [WorkOrderController::class, 'edit'])->name('editWorkOrder');
Route::put('/updateWorkEntry/{id}'       , [WorkOrderController::class, 'update'])->name('updateWorkEntry');
Route::get('/deleteWorkOrder/{id}'       , [WorkOrderController::class, 'destroy'])->name('deleteWorkOrder');

Route::get('/get-customer-parts/{id}', [SetupSheetController::class, 'getCustomerParts'])->name('getCustomerParts');




Route::get('/AddProject'                 , [ProjectController::class, 'AddProject'])->name('AddProject');
Route::get('/ViewProject'                , [ProjectController::class, 'ViewProject'])->name('ViewProject');
Route::post('/storeProject'              , [ProjectController::class, 'storeProject'])->name('storeProject');
Route::get('/editProject/{id}'           , [ProjectController::class, 'edit'])->name('editProject');
Route::put('/updateProject/{id}'         , [ProjectController::class, 'update'])->name('updateProject');
Route::get('/deleteProject/{id}'         , [ProjectController::class, 'destroy'])->name('deleteProject');


Route::get('/AddOperator'                , [OperatorController::class, 'AddOperator'])->name('AddOperator');
Route::post('/storeOperator'             , [OperatorController::class, 'storeOperator'])->name('storeOperator');
Route::get('/editOperator/{id}'          , [OperatorController::class, 'edit'])->name('editOperator');
Route::put('/updateOperator/{id}'        , [OperatorController::class, 'update'])->name('updateOperator');
Route::post('/updateOperatorStatus'      , [OperatorController::class,  'updateOperatorStatus'])->name('updateOperatorStatus');
Route::get('/deleteOperator/{id}'        , [OperatorController::class, 'destroy'])->name('deleteOperator');



Route::get('/AddMachine'                  ,[MachineController::class, 'AddMachine'])->name('AddMachine');
Route::post('/storeMachine'               ,[MachineController::class, 'storeMachine'])->name('storeMachine');
Route::get('/editMachine/{id}'            ,[MachineController::class, 'edit'])->name('editMachine');
Route::put('/updateMachine/{id}'          ,[MachineController::class, 'update'])->name('updateMachine');
Route::get('/deleteMachine/{id}'          ,[MachineController::class, 'destroy'])->name('deleteMachine');
Route::post('/updateStatus'               ,[MachineController::class, 'updateStatus'])->name('updateStatus');

Route::get('/AddSetting'                  , [SettingController::class, 'AddSetting'])->name('AddSetting');
Route::post('/storeSetting'               , [SettingController::class, 'storeSetting'])->name('storeSetting');
Route::get('/editSetting/{id}'            , [SettingController::class, 'editSetting'])->name('editSetting');
Route::put('/updateSetting/{id}'          , [SettingController::class, 'updateSetting'])->name('updateSetting');
Route::get('/deleteSetting/{id}'          , [SettingController::class, 'destroy'])->name('deleteSetting');
Route::post('/updateSettingStatus'        , [SettingController::class,  'updateSettingStatus'])->name('updateSettingStatus');

Route::get('/AddSetupSheet'               , [SetupSheetController::class, 'AddSetupSheet'])->name('AddSetupSheet');
Route::post('/storeSetupSheet'            , [SetupSheetController::class, 'storeSetupSheet'])->name('storeSetupSheet');
Route::get('/editSetupSheet/{id}'         , [SetupSheetController::class, 'editSetupSheet'])->name('editSetupSheet');
Route::get('/deleteSetupSheet/{id}'       , [SetupSheetController::class, 'destroy'])->name('deleteSetupSheet');
Route::get('/ViewSetupSheet'              , [SetupSheetController::class, 'ViewSetupSheet'])->name('ViewSetupSheet');
Route::put('/updateSetupSheet/{encryptedId}', [SetupSheetController::class, 'update'])->name('updateSetupSheet');

// Route::get('/download-setup-sheet/{id}'   , [SetupSheetController::class, 'downloadSetupSheet'])->name('downloadSetupSheet');
 
 
Route::get('/AddMachinerecord'            , [MachinerecordController::class, 'AddMachinerecord'])->name('AddMachinerecord');
Route::get('/ViewMachinerecord'           , [MachinerecordController::class, 'ViewMachinerecord'])->name('ViewMachinerecord');
Route::post('/StoreMachinerecord'         , [MachinerecordController::class, 'StoreMachinerecord'])->name('StoreMachinerecord');
Route::get('/EditMachinerecord/{id}'      , [MachinerecordController::class, 'edit'])->name('EditMachinerecord');
Route::put('/UpdateMachinerecord/{id}'    , [MachinerecordController::class, 'update'])->name('UpdateMachinerecord');
Route::get('/DeleteMachinerecord/{id}'    , [MachinerecordController::class, 'destroy'])->name('DeleteMachinerecord');


Route::get('/AddMaterialorder'            , [MaterialorderController::class, 'AddMaterialorder'])->name('AddMaterialorder');
Route::get('/ViewMaterialorder'           , [MaterialorderController::class, 'ViewMaterialorder'])->name('ViewMaterialorder');



















