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
use App\Http\Controllers\FinancialYearController;
use App\Http\Controllers\AdminSettingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserAdminController;
use App\Http\Controllers\ProformaInvoiceController;
use App\Http\Controllers\RolePermissionController;
use Illuminate\Support\Facades\Artisan;

Auth::routes();
Route::middleware(['auth','check.subscription'])->group(function () {
 
Route::get('/'                         , [HomeController::class, 'index'])->name('home');

// Client Routes
Route::get('/AddClient'                       , [ClientContoller::class, 'AddClient'])->name('AddClient');
Route::get('/ViewClient'                      , [ClientContoller::class, 'ViewClient'])->name('ViewClient');
Route::post('/storeClient'                    , [ClientContoller::class, 'storeClient'])->name('storeClient');
Route::get('/editClient/{id}'                 , [ClientContoller::class, 'edit'])->name('editClient');
Route::put('/updateClient/{id}'               , [ClientContoller::class, 'update'])->name('updateClient');
Route::get('/deleteClient/{id}'               , [ClientContoller::class, 'destroy'])->name('deleteClient');
Route::post('/updateClientStatus'             , [ClientContoller::class,  'updateClientStatus'])->name('updateClientStatus');
Route::post('/client/update-plan'             , [ClientContoller::class, 'updateClientPlan'])->name('updateClientPlan');
Route::post('/client/renew'                   , [ClientContoller::class, 'renewPlan'])->name('client.renew');
 
// Customer Routes
Route::get('/AddCustomer'                     , [CustomerContoller::class, 'AddCustomer'])->name('AddCustomer');
Route::get('/ViewCustomer'                    , [CustomerContoller::class, 'ViewCustomer'])->name('ViewCustomer');
Route::post('/storeCustomer'                  , [CustomerContoller::class, 'storeCustomer'])->name('storeCustomer');
Route::get('/editCustomer/{id}'               , [CustomerContoller::class, 'edit'])->name('editCustomer');
Route::put('/updateCustomer/{id}'             , [CustomerContoller::class, 'update'])->name('updateCustomer');
Route::get('/deleteCustomer/{id}'             , [CustomerContoller::class, 'destroy'])->name('deleteCustomer');
Route::post('/updateCustomerStatus'           , [CustomerContoller::class, 'updateCustomerStatus'])->name('updateCustomerStatus');
Route::get('/customers'                       , [CustomerContoller::class, 'ViewCustomer'])->name('ViewCustomer');
Route::post('/customers/import'               , [CustomerContoller::class, 'importCustomers'])->name('importCustomers');
Route::get('/customers/export-sample'         , [CustomerContoller::class, 'exportSample'])->name('exportCustomers');
Route::get('/financial-years'                 , [CustomerContoller::class, 'getFinancialYears'])->name('financial.years');

// Vender Routes                                            
Route::get('/AddVendor'                       , [VendorContoller::class, 'AddVendor'])->name('AddVendor');
Route::post('/storeVendor'                    , [VendorContoller::class, 'storeVendor'])->name('storeVendor');
Route::get('/ViewVendor'                      , [VendorContoller::class, 'ViewVendor'])->name('ViewVendor');
Route::get('/editVendor/{id}'                 , [VendorContoller::class, 'edit'])->name('editVendor');
Route::put('/updateVendor/{id}'               , [VendorContoller::class, 'update'])->name('updateVendor');
Route::get('/deleteVendor/{id}'               , [VendorContoller::class, 'destroy'])->name('deleteVendor');
Route::get('/trashVendor'                     , [VendorContoller::class, 'trash'])->name('trashVendor');
Route::get('/restoreVendor/{id}'              , [VendorContoller::class, 'restore'])->name('restoreVendor');

// Workorder Routes
Route::get('/AddWorkOrder'                    , [WorkOrderController::class, 'AddWorkOrder'])->name('AddWorkOrder');
Route::get('/ViewWorkOrder'                   , [WorkOrderController::class, 'ViewWorkOrder'])->name('ViewWorkOrder');
Route::post('/storeWorkEntry'                 , [WorkOrderController::class, 'storeWorkEntry'])->name('storeWorkEntry');
Route::get('/editWorkOrder/{id}'              , [WorkOrderController::class, 'edit'])->name('editWorkOrder');
Route::put('/updateWorkEntry/{id}'            , [WorkOrderController::class, 'update'])->name('updateWorkEntry');
Route::get('/deleteWorkOrder/{id}'            , [WorkOrderController::class, 'destroy'])->name('deleteWorkOrder');
Route::get('/get-projects/{customerId}'       , [WorkorderController::class, 'getProjects']);
Route::get('/get-parts/{projectId}'           , [WorkorderController::class, 'getParts']);
Route::post('/WorkOrder/import'               , [WorkorderController::class, 'importWorkOrder'])->name('importWorkOrder');
Route::get('/WorkOrder/export-sample'         , [WorkorderController::class, 'exportSample'])->name('exportWorkOrder');
Route::get('/trashWorkOrder'                  , [WorkOrderController::class, 'trash'])->name('trashWorkOrder');
Route::get('/restoreWorkOrder/{id}'           , [WorkOrderController::class, 'restore'])->name('restoreWorkOrder');

// Project Routes
Route::get('/AddProject'                      , [ProjectController::class, 'AddProject'])->name('AddProject');
Route::get('/ViewProject'                     , [ProjectController::class, 'ViewProject'])->name('ViewProject');
Route::post('/storeProject'                   , [ProjectController::class, 'storeProject'])->name('storeProject');
Route::get('/editProject/{id}'                , [ProjectController::class, 'edit'])->name('editProject');
Route::put('/updateProject/{id}'              , [ProjectController::class, 'update'])->name('updateProject');
Route::get('/deleteProject/{id}'              , [ProjectController::class, 'destroy'])->name('deleteProject');
Route::get('/get-project-quantity/{id}'       , [ProjectController::class, 'getProjectQuantity']);
Route::post('/projects/import'                , [ProjectController::class, 'importProjects'])->name('importProjects');
Route::get('/projects/export-sample'          , [ProjectController::class, 'exportSample'])->name('exportProjects');

// Operator Routes
Route::get('/AddOperator'                     , [OperatorController::class, 'AddOperator'])->name('AddOperator');
Route::post('/storeOperator'                  , [OperatorController::class, 'storeOperator'])->name('storeOperator');
Route::get('/editOperator/{id}'               , [OperatorController::class, 'edit'])->name('editOperator');
Route::put('/updateOperator/{id}'             , [OperatorController::class, 'update'])->name('updateOperator');
Route::post('/updateOperatorStatus'           , [OperatorController::class,  'updateOperatorStatus'])->name('updateOperatorStatus');
Route::get('/deleteOperator/{id}'             , [OperatorController::class, 'destroy'])->name('deleteOperator');
Route::get('/trashoperator'                   , [OperatorController::class, 'trash'])->name('trashOperator');
Route::get('/restoreoperator/{id}'            , [OperatorController::class, 'restore'])->name('restoreOperator');

// Machine Routes
Route::get('/AddMachine'                      , [MachineController::class, 'AddMachine'])->name('AddMachine');
Route::post('/storeMachine'                   , [MachineController::class, 'storeMachine'])->name('storeMachine');
Route::get('/editMachine/{id}'                , [MachineController::class, 'edit'])->name('editMachine');
Route::put('/updateMachine/{id}'              , [MachineController::class, 'update'])->name('updateMachine');
Route::get('/deleteMachine/{id}'              , [MachineController::class, 'destroy'])->name('deleteMachine');
Route::post('/machine/status/update'          , [MachineController::class, 'updateStatus'])->name('machine.updateStatus');

Route::get('/trashmachine'                    , [MachineController::class, 'trash'])->name('trashmachine');
Route::get('/restoremachine/{id}'             , [MachineController::class, 'restore'])->name('restoremachine');

// Setting Routes
Route::get('/AddSetting'                      , [SettingController::class, 'AddSetting'])->name('AddSetting');
Route::post('/storeSetting'                   , [SettingController::class, 'storeSetting'])->name('storeSetting');
Route::get('/editSetting/{id}'                , [SettingController::class, 'editSetting'])->name('editSetting');
Route::put('/updateSetting/{id}'              , [SettingController::class, 'updateSetting'])->name('updateSetting');
Route::get('/deleteSetting/{id}'              , [SettingController::class, 'destroy'])->name('deleteSetting');
Route::post('/updateSettingStatus'            , [SettingController::class,  'updateSettingStatus'])->name('updateSettingStatus');
Route::get('/trashSetting'                    , [SettingController::class, 'trash'])->name('trashSetting');
Route::get('/restoreSetting/{id}'             , [SettingController::class, 'restore'])->name('restoreSetting');

// HSN Routes
Route::get('hsn/add'                          , [HsncodeController::class, 'addHsn'])->name('addHsn');
Route::post('hsn/store'                       , [HsncodeController::class, 'store'])->name('storeHsn');
Route::get('hsn/edit/{id}'                    , [HsncodeController::class, 'edit'])->name('editHsn');
Route::put('hsn/update/{id}'                  , [HsncodeController::class, 'update'])->name('updateHsn');
Route::post('hsn/status'                      , [HsncodeController::class, 'updateStatus'])->name('updateHsnStatus');
Route::get('hsn/delete/{id}'                  , [HsncodeController::class, 'destroy'])->name('deleteHsn');
Route::get('/trashhsn'                        , [HsncodeController::class, 'trash'])->name('trashhsn');
Route::get('/restorehsn/{id}'                 , [HsncodeController::class, 'restore'])->name('restorehsn');

// MaterialType Routes
Route::get('/AddMaterialType'                 , [MaterialTypeController::class, 'AddMaterialType'])->name('AddMaterialType');
Route::post('/storeMaterialType'              , [MaterialTypeController::class, 'storeMaterialType'])->name('storeMaterialType');
Route::get('/editMaterialType/{id}'           , [MaterialTypeController::class, 'editMaterialType'])->name('editMaterialType');
Route::put('/updateMaterialType/{id}'         , [MaterialTypeController::class, 'updateMaterialType'])->name('updateMaterialType');
Route::get('/deleteMaterialType/{id}'         , [MaterialTypeController::class, 'destroy'])->name('deleteMaterialType');
Route::post('/updateMaterialStatus'           , [MaterialTypeController::class, 'updateMaterialStatus'])->name('updateMaterialStatus');
Route::get('/trashMaterialType'               , [MaterialTypeController::class, 'trash'])->name('trashMaterialType');
Route::get('/restoreMaterialType/{id}'        , [MaterialTypeController::class, 'restore'])->name('restoreMaterialType');

//financial-year Routes
Route::get('/financial-year/add'              , [FinancialYearController::class, 'AddFinancialYear'])->name('AddFinancialYear');
Route::post('/financial-year/store'           , [FinancialYearController::class, 'storeFinancialYear'])->name('StoreFinancialYear');
Route::get('/financial-year/edit/{id}'        , [FinancialYearController::class, 'edit'])->name('EditFinancialYear');
Route::put('/financial-year/update/{id}'      , [FinancialYearController::class, 'update'])->name('UpdateFinancialYear');
Route::get('/financial-year/delete/{id}'      , [FinancialYearController::class, 'destroy'])->name('DeleteFinancialYear');
Route::post('/financial-year/status'          , [FinancialYearController::class, 'updateStatus'])->name('FinancialYearStatus');
Route::get('/trashFinancial'                  , [FinancialYearController::class, 'trash'])->name('trashFinancial');
Route::get('/restoreFinancial/{id}'           , [FinancialYearController::class, 'restore'])->name('restoreFinancial');

// Setupsheet Routes
Route::get('/AddSetupSheet'                   , [SetupSheetController::class, 'AddSetupSheet'])->name('AddSetupSheet');
Route::post('/storeSetupSheet'                , [SetupSheetController::class, 'storeSetupSheet'])->name('storeSetupSheet');
Route::get('/editSetupSheet/{id}'             , [SetupSheetController::class, 'editSetupSheet'])->name('editSetupSheet');
Route::get('/deleteSetupSheet/{id}'           , [SetupSheetController::class, 'destroy'])->name('deleteSetupSheet');
Route::get('/ViewSetupSheet'                  , [SetupSheetController::class, 'ViewSetupSheet'])->name('ViewSetupSheet');
Route::put('/updateSetupSheet/{encryptedId}'  , [SetupSheetController::class, 'update'])->name('updateSetupSheet');
Route::get('/setup-sheet-data/{partNo}'       , [SetupSheetController::class, 'getSetupSheetData']);
Route::get('/get-customer-parts/{id}'         , [SetupSheetController::class, 'getCustomerParts'])->name('getCustomerParts');
Route::get('/getPartsByCustomer/{id}'         , [WorkOrderController::class, 'getPartsByCustomer']);
Route::get('/trashSetupSheet'                 , [SetupSheetController::class, 'trash'])->name('trashSetupSheet');
Route::get('/restoreSetupSheet/{id}'          , [SetupSheetController::class, 'restore'])->name('restoreSetupSheet');

// Machinerecord Routes 
Route::get('/AddMachinerecord'                , [MachinerecordController::class, 'AddMachinerecord'])->name('AddMachinerecord');
Route::get('/ViewMachinerecord'               , [MachinerecordController::class, 'ViewMachinerecord'])->name('ViewMachinerecord');
Route::post('/StoreMachinerecord'             , [MachinerecordController::class, 'StoreMachinerecord'])->name('StoreMachinerecord');
Route::get('/EditMachinerecord/{id}'          , [MachinerecordController::class, 'edit'])->name('EditMachinerecord');
Route::put('/UpdateMachinerecord/{id}'        , [MachinerecordController::class, 'update'])->name('UpdateMachinerecord');
Route::get('/DeleteMachinerecord/{id}'        , [MachinerecordController::class, 'destroy'])->name('DeleteMachinerecord');
Route::get('/trashMachineRecord'              , [MachinerecordController::class, 'trash'])->name('trashMachineRecord');
Route::get('/restoreMachineRecord/{id}'       , [MachinerecordController::class, 'restore'])->name('restoreMachineRecord');
Route::get('/get-invoice-by-customer/{customer_id}', [MachinerecordController::class, 'getInvoiceByCustomer']);


// MaterialReq Routes
Route::get('/AddMaterialReq'                  , [MaterialReqController::class, 'AddMaterialReq'])->name('AddMaterialReq');
Route::get('/ViewMaterialReq'                 , [MaterialReqController::class, 'ViewMaterialReq'])->name('ViewMaterialReq');
Route::post('/storeMaterialReq'               , [MaterialReqController::class, 'storeMaterialReq'])->name('storeMaterialReq');
Route::get('/editMaterialReq/{id}'            , [MaterialReqController::class, 'editMaterialReq'])->name('editMaterialReq');
Route::get('/deleteMaterialReq/{id}'          , [MaterialReqController::class, 'destroy'])->name('deleteMaterialReq');
Route::put('/updateMaterialReq/{id}'          , [MaterialReqController::class, 'updateMaterialReq'])->name('updateMaterialReq');
Route::get('/trashMaterialReq'                , [MaterialReqController::class, 'trash'])->name('trashMaterialReq');
Route::get('/restoreMaterialReq/{id}'         , [MaterialReqController::class, 'restore'])->name('restoreMaterialReq');
Route::get('/get-material/{id}'               , [MaterialReqController::class, 'getMaterial']);

// Materialorder Routes
Route::get('/AddMaterialorder'                , [MaterialorderController::class, 'AddMaterialorder'])->name('AddMaterialorder');
Route::get('/ViewMaterialorder'               , [MaterialorderController::class, 'ViewMaterialorder'])->name('ViewMaterialorder');
Route::post('/storeMaterialorder'             , [MaterialorderController::class, 'storeMaterialorder'])->name('storeMaterialorder');
Route::get('/editMaterialorder/{id}'          , [MaterialorderController::class, 'editMaterialorder'])->name('editMaterialorder');
Route::get('/deleteMaterialorder/{id}'        , [MaterialorderController::class, 'destroy'])->name('deleteMaterialorder');
Route::put('/updateMaterialorder/{id}'        , [MaterialorderController::class, 'update'])->name('updateMaterialorder');
Route::get('materialorder/trash'              , [MaterialorderController::class, 'trash'])->name('trashMaterialorder');
Route::get('materialorder/restore/{id}'       , [MaterialorderController::class, 'restore'])->name('restoreMaterialorder');
Route::get('/get-customer-data/{id}'          , [MaterialorderController::class, 'getCustomerData'])->name('getCustomerData');
Route::get('/get-material-requests/{customer_id}', [MaterialorderController::class, 'getMaterialRequests']);
Route::get('/get-material-request-details/{id}'  , [MaterialOrderController::class, 'getMaterialRequestDetails']);
 Route::get('/material-req/by-customer/{customer_id}', [MaterialorderController::class, 'getByCustomer']);


//invoice Routes
Route::get('invoice'                          , [InvoiceController::class, 'index'])->name('invoice.index');
Route::get('invoice/add'                      , [InvoiceController::class, 'create'])->name('invoice.add');
Route::post('invoice/store'                   , [InvoiceController::class, 'store'])->name('invoice.store');
Route::get('invoice/{id}/download'            , [InvoiceController::class, 'download'])->name('invoice.download');
Route::get('invoice/print/{id}'               , [InvoiceController::class, 'printInvoice'])->name('invoice.print');
Route::get('get-hsn-details/{id}'             , [InvoiceController::class, 'getHsnDetails'])->name('get.hsn.details');
Route::get('/invoice/fetch-machine-records/{customer_id}', [InvoiceController::class, 'getMachineRecords']);
Route::get('/invoice/get-machine-details/{id}', [InvoiceController::class, 'getMachineDetails']); 
Route::get('/invoice/fetch-material-rate/{material}', [InvoiceController::class, 'getMaterialRate']);

 
// User Admin CRUD
Route::get('/useradmin'                       , [UserAdminController::class, 'index'])->name('ListUserAdmin');
Route::get('/useradmin/add'                   , [UserAdminController::class, 'AddUserAdmin'])->name('AddUserAdmin');
Route::post('/useradmin/store'                , [UserAdminController::class, 'StoreUser'])->name('StoreUser');
Route::get('/useradmin/edit/{id}'             , [UserAdminController::class, 'edit'])->name('EditUserAdmin');
Route::put('/useradmin/update/{id}', [UserAdminController::class, 'update'])->name('UpdateUserAdmin');
Route::post('/useradmin/updatestatus'         , [UserAdminController::class, 'userupdateStatus'])->name('updateStatus');

Route::get('/RolePermission'                  , [RolePermissionController::class, 'RolePermission'])->name('RolePermission');
Route::post('/RolePermission/store'            , [RolePermissionController::class, 'Store'])->name('Store');
Route::get('/get-role-permissions/{id}'       , [RolePermissionController::class, 'getRolePermissions']);


//Admin Setting Routes
Route::get('/Setting'                         , [AdminSettingController::class, 'EditSetting'])->name('Setting');
Route::post('/UpdateAdminSetting'             , [AdminSettingController::class, 'UpdateAdminSetting'])->name('UpdateAdminSetting');


Route::get('/Payment', [PaymentController::class, 'Payment'])->name('Payment');
Route::post('/razorpay.order', [PaymentController::class, 'order'])->name('razorpay.order');
Route::post('/payment-success', [PaymentController::class, 'success'])->name('razorpay.success');
Route::get('/PaymentList', [PaymentController::class, 'PaymentList'])->name('PaymentList');
Route::post('/payment/verify', [PaymentController::class, 'verify'])->name('payment.verify');


Route::get('proforma'                          ,[ProformaInvoiceController::class, 'index'])->name('proforma.index');
Route::get('proforma/add'                      , [ProformaInvoiceController::class, 'create'])->name('proforma.add');
Route::post('proforma/store'                   , [ProformaInvoiceController::class, 'store'])->name('proforma.store');
Route::get('proforma/{id}/download'            , [ProformaInvoiceController::class, 'download'])->name('proforma.download');
Route::get('proforma/print/{id}'               , [ProformaInvoiceController::class, 'printInvoice'])->name('proforma.print');
Route::get('get-hsn-details/{id}'             , [ProformaInvoiceController::class, 'getHsnDetails'])->name('get.hsn.details');
Route::get('/proforma/fetch-machine-records/{customer_id}', [ProformaInvoiceController::class, 'getMachineRecords']);
Route::get('/proforma/get-machine-details/{id}', [ProformaInvoiceController::class, 'getMachineDetails']); 
Route::get('/proforma/fetch-material-rate/{material}', [ProformaInvoiceController::class, 'getMaterialRate']);


});
Route::get('/clear-app-cache', function () {
 
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
 
    return "Cleared config, cache, route, and view.";
});

 
 












































