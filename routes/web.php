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
use App\Http\Controllers\MaterialReqController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\HsncodeController;
use App\Http\Controllers\MaterialTypeController;


Auth::routes();
Route::middleware(['auth'])->group(function () {
 
Route::get('/'                         , [HomeController::class, 'index'])->name('home');
// Route::get('/superAdmin'               , [HomeController::class, 'superAdmin'])->name('superAdmin');
// Route::get('logout'                    , [HomeController::class, 'logout'])->name('logout');

});
 
// Client Routes
Route::get('/AddClient'                       , [ClientContoller::class, 'AddClient'])->name('AddClient');
Route::get('/ViewClient'                      , [ClientContoller::class, 'ViewClient'])->name('ViewClient');
Route::post('/storeClient'                    , [ClientContoller::class, 'storeClient'])->name('storeClient');
Route::get('/editClient/{id}'                 , [ClientContoller::class, 'edit'])->name('editClient');
Route::put('/updateClient/{id}'               , [ClientContoller::class, 'update'])->name('updateClient');
Route::get('/deleteClient/{id}'               , [ClientContoller::class, 'destroy'])->name('deleteClient');
Route::post('/updateClientStatus'             , [ClientContoller::class,  'updateClientStatus'])->name('updateClientStatus');
 
// Customer Routes
Route::get('/AddCustomer'                     , [CustomerContoller::class, 'AddCustomer'])->name('AddCustomer');
Route::get('/ViewCustomer'                    , [CustomerContoller::class, 'ViewCustomer'])->name('ViewCustomer');
Route::post('/storeCustomer'                  , [CustomerContoller::class, 'storeCustomer'])->name('storeCustomer');
Route::get('/editCustomer/{id}'               , [CustomerContoller::class, 'edit'])->name('editCustomer');
Route::put('/updateCustomer/{id}'             , [CustomerContoller::class, 'update'])->name('updateCustomer');
Route::get('/deleteCustomer/{id}'             , [CustomerContoller::class, 'destroy'])->name('deleteCustomer');
Route::post('/updateCustomerStatus'           , [CustomerContoller::class, 'updateCustomerStatus'])->name('updateCustomerStatus');

// Vender Routes                                            
Route::get('/AddVendor'                       , [VendorContoller::class, 'AddVendor'])->name('AddVendor');
Route::post('/storeVendor'                    , [VendorContoller::class, 'storeVendor'])->name('storeVendor');
Route::get('/ViewVendor'                      , [VendorContoller::class, 'ViewVendor'])->name('ViewVendor');
Route::get('/editVendor/{id}'                 , [VendorContoller::class, 'edit'])->name('editVendor');
Route::put('/updateVendor/{id}'               , [VendorContoller::class, 'update'])->name('updateVendor');
Route::get('/deleteVendor/{id}'               , [VendorContoller::class, 'destroy'])->name('deleteVendor');

// Workorder Routes
Route::get('/AddWorkOrder'                    , [WorkOrderController::class, 'AddWorkOrder'])->name('AddWorkOrder');
Route::get('/ViewWorkOrder'                   , [WorkOrderController::class, 'ViewWorkOrder'])->name('ViewWorkOrder');
Route::post('/storeWorkEntry'                 , [WorkOrderController::class, 'storeWorkEntry'])->name('storeWorkEntry');
Route::get('/editWorkOrder/{id}'              , [WorkOrderController::class, 'edit'])->name('editWorkOrder');
Route::put('/updateWorkEntry/{id}'            , [WorkOrderController::class, 'update'])->name('updateWorkEntry');
Route::get('/deleteWorkOrder/{id}'            , [WorkOrderController::class, 'destroy'])->name('deleteWorkOrder');
Route::get('/get-projects/{customerId}'       , [WorkorderController::class, 'getProjects']);
Route::get('/get-parts/{projectId}'           , [WorkorderController::class, 'getParts']);

// Project Routes
Route::get('/AddProject'                      , [ProjectController::class, 'AddProject'])->name('AddProject');
Route::get('/ViewProject'                     , [ProjectController::class, 'ViewProject'])->name('ViewProject');
Route::post('/storeProject'                   , [ProjectController::class, 'storeProject'])->name('storeProject');
Route::get('/editProject/{id}'                , [ProjectController::class, 'edit'])->name('editProject');
Route::put('/updateProject/{id}'              , [ProjectController::class, 'update'])->name('updateProject');
Route::get('/deleteProject/{id}'              , [ProjectController::class, 'destroy'])->name('deleteProject');

Route::get('/get-project-quantity/{id}'       , [ProjectController::class, 'getProjectQuantity']);


// Operator Routes
Route::get('/AddOperator'                     , [OperatorController::class, 'AddOperator'])->name('AddOperator');
Route::post('/storeOperator'                  , [OperatorController::class, 'storeOperator'])->name('storeOperator');
Route::get('/editOperator/{id}'               , [OperatorController::class, 'edit'])->name('editOperator');
Route::put('/updateOperator/{id}'             , [OperatorController::class, 'update'])->name('updateOperator');
Route::post('/updateOperatorStatus'           , [OperatorController::class,  'updateOperatorStatus'])->name('updateOperatorStatus');
Route::get('/deleteOperator/{id}'             , [OperatorController::class, 'destroy'])->name('deleteOperator');
Route::get('/trashoperator'                   , [OperatorController::class, 'trash'])->name('trashOperator');
Route::get('/restoreoperator/{id}'            , [OperatorController::class, 'restore'])->name('restoreOperator');
// Edit & Update Routes
Route::get('/operators/edit/{id}',              [OperatorController::class, 'edit'])->name('editOperator');
Route::put('/operators/update/{id}'           , [OperatorController::class, 'update'])->name('updateOperator');


 
 

// Machine Routes
Route::get('/AddMachine'                      , [MachineController::class, 'AddMachine'])->name('AddMachine');
Route::post('/storeMachine'                   , [MachineController::class, 'storeMachine'])->name('storeMachine');
Route::get('/editMachine/{id}'                , [MachineController::class, 'edit'])->name('editMachine');
Route::put('/updateMachine/{id}'              , [MachineController::class, 'update'])->name('updateMachine');
Route::get('/deleteMachine/{id}'              , [MachineController::class, 'destroy'])->name('deleteMachine');
Route::post('/updateStatus'                   , [MachineController::class, 'updateStatus'])->name('updateStatus');

// Setting Routes
Route::get('/AddSetting'                      , [SettingController::class, 'AddSetting'])->name('AddSetting');
Route::post('/storeSetting'                   , [SettingController::class, 'storeSetting'])->name('storeSetting');
Route::get('/editSetting/{id}'                , [SettingController::class, 'editSetting'])->name('editSetting');
Route::put('/updateSetting/{id}'              , [SettingController::class, 'updateSetting'])->name('updateSetting');
Route::get('/deleteSetting/{id}'              , [SettingController::class, 'destroy'])->name('deleteSetting');
Route::post('/updateSettingStatus'            , [SettingController::class,  'updateSettingStatus'])->name('updateSettingStatus');

// HSN Routes
Route::get('hsn/add'                          , [HsncodeController::class, 'addHsn'])->name('addHsn');
Route::post('hsn/store'                       , [HsncodeController::class, 'store'])->name('storeHsn');
Route::get('hsn/edit/{id}'                    , [HsncodeController::class, 'edit'])->name('editHsn');
Route::put('hsn/update/{id}'                  , [HsncodeController::class, 'update'])->name('updateHsn');
Route::post('hsn/status'                      , [HsncodeController::class, 'updateStatus'])->name('updateHsnStatus');
Route::get('hsn/delete/{id}'                  , [HsncodeController::class, 'destroy'])->name('deleteHsn');

// MaterialType Routes
Route::get('/AddMaterialType'                 , [MaterialTypeController::class, 'AddMaterialType'])->name('AddMaterialType');
Route::post('/storeMaterialType'              , [MaterialTypeController::class, 'storeMaterialType'])->name('storeMaterialType');
Route::get('/editMaterialType/{id}'           , [MaterialTypeController::class, 'editMaterialType'])->name('editMaterialType');
Route::put('/updateMaterialType/{id}'         , [MaterialTypeController::class, 'updateMaterialType'])->name('updateMaterialType');
Route::get('/deleteMaterialType/{id}'         , [MaterialTypeController::class, 'destroy'])->name('deleteMaterialType');
Route::get('/trashMaterialType'               , [MaterialTypeController::class, 'trashMaterialType'])->name('trashMaterialType');
Route::get('/restoreMaterialType/{id}'        , [MaterialTypeController::class, 'restoreMaterialType'])->name('restoreMaterialType');

// Setupsheet Routes
Route::get('/AddSetupSheet'                   , [SetupSheetController::class, 'AddSetupSheet'])->name('AddSetupSheet');
Route::post('/storeSetupSheet'                , [SetupSheetController::class, 'storeSetupSheet'])->name('storeSetupSheet');
Route::get('/editSetupSheet/{id}'             , [SetupSheetController::class, 'editSetupSheet'])->name('editSetupSheet');
Route::get('/deleteSetupSheet/{id}'           , [SetupSheetController::class, 'destroy'])->name('deleteSetupSheet');
Route::get('/ViewSetupSheet'                  , [SetupSheetController::class, 'ViewSetupSheet'])->name('ViewSetupSheet');
Route::put('/updateSetupSheet/{encryptedId}'  , [SetupSheetController::class, 'update'])->name('updateSetupSheet');
Route::get('/setup-sheet-data/{partNo}'       , [SetupSheetController::class, 'getSetupSheetData']);
Route::get('/get-customer-parts/{id}'         , [SetupSheetController::class, 'getCustomerParts'])->name('getCustomerParts');

// Machinerecord Routes 
Route::get('/AddMachinerecord'                , [MachinerecordController::class, 'AddMachinerecord'])->name('AddMachinerecord');
Route::get('/ViewMachinerecord'               , [MachinerecordController::class, 'ViewMachinerecord'])->name('ViewMachinerecord');
Route::post('/StoreMachinerecord'             , [MachinerecordController::class, 'StoreMachinerecord'])->name('StoreMachinerecord');
Route::get('/EditMachinerecord/{id}'          , [MachinerecordController::class, 'edit'])->name('EditMachinerecord');
Route::put('/UpdateMachinerecord/{id}'        , [MachinerecordController::class, 'update'])->name('UpdateMachinerecord');
Route::get('/DeleteMachinerecord/{id}'        , [MachinerecordController::class, 'destroy'])->name('DeleteMachinerecord');

// MaterialReq Routes
Route::get('/AddMaterialReq'                  , [MaterialReqController::class, 'AddMaterialReq'])->name('AddMaterialReq');
Route::get('/ViewMaterialReq'                 , [MaterialReqController::class, 'ViewMaterialReq'])->name('ViewMaterialReq');
Route::post('/storeMaterialReq'               , [MaterialReqController::class, 'storeMaterialReq'])->name('storeMaterialReq');
Route::get('/editMaterialReq/{id}'            , [MaterialReqController::class, 'editMaterialReq'])->name('editMaterialReq');
Route::get('/deleteMaterialReq/{id}'          , [MaterialReqController::class, 'destroy'])->name('deleteMaterialReq');
Route::put('/updateMaterialReq/{id}'          , [MaterialReqController::class, 'updateMaterialReq'])->name('updateMaterialReq');

// Materialorder Routes
Route::get('/AddMaterialorder'                , [MaterialorderController::class, 'AddMaterialorder'])->name('AddMaterialorder');
Route::get('/ViewMaterialorder'               , [MaterialorderController::class, 'ViewMaterialorder'])->name('ViewMaterialorder');
Route::post('/storeMaterialorder'             , [MaterialorderController::class, 'storeMaterialorder'])->name('storeMaterialorder');
Route::get('/editMaterialorder/{id}'          , [MaterialorderController::class, 'edit'])->name('editMaterialorder');
Route::get('/deleteMaterialorder/{id}'        , [MaterialorderController::class, 'destroy'])->name('deleteMaterialorder');
Route::put('/updateMaterialorder/{id}'        , [MaterialorderController::class, 'update'])->name('updateMaterialorder');

// Invoice Routes
Route::get('/AddInvoice'                      , [InvoiceController::class, 'AddInvoice'])->name('AddInvoice');
Route::get('/ViewInvoice'                     , [InvoiceController::class, 'ViewInvoice'])->name('ViewInvoice');
Route::post('/StoreInvoice'                   , [InvoiceController::class, 'StoreInvoice'])->name('StoreInvoice');
Route::get('/editInvoice/{id}'                , [InvoiceController::class, 'editInvoice'])->name('editInvoice');
Route::put('/updateInvoice/{id}'              , [InvoiceController::class, 'updateInvoice'])->name('updateInvoice');
Route::get('/deleteInvoice/{id}'              , [InvoiceController::class,  'destroy'])->name('deleteInvoice');
Route::get('/printInvoice/{id}'               , [InvoiceController::class, 'printInvoice'])->name('printInvoice');




























