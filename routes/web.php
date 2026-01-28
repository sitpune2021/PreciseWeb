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
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\RolePermissionController;
use Illuminate\Support\Facades\Artisan;

Auth::routes();
Route::middleware(['auth','check.subscription'])->group(function () {
 
Route::get('/'                         , [HomeController::class, 'index'])->name('home');

 
// Client Routes
Route::get( 'client/add'                        , [ClientContoller::class, 'AddClient'])->name('AddClient');
Route::get( 'client/view'                       , [ClientContoller::class, 'ViewClient'])->name('ViewClient');
Route::post('client/store'                      , [ClientContoller::class, 'storeClient'])->name('storeClient');
Route::get( 'client/edit/{id}'                  , [ClientContoller::class, 'edit'])->name('editClient');
Route::put( 'client/update/{id}'                , [ClientContoller::class, 'update'])->name('updateClient');
Route::get( 'client/delete/{id}'                , [ClientContoller::class, 'destroy'])->name('deleteClient');
Route::post('client/updateStatus'               , [ClientContoller::class, 'updateClientStatus'])->name('updateClientStatus');
Route::post('client/updateplan'                 , [ClientContoller::class, 'updateClientPlan'])->name('updateClientPlan');
Route::post('client/renew'                      , [ClientContoller::class, 'renewPlan'])->name('client.renew');
 
// Customer Routes
Route::get( 'customer/add'                      , [CustomerContoller::class, 'AddCustomer'])->name('AddCustomer');
// Route::get( 'customer/view'                     , [CustomerContoller::class, 'ViewCustomer'])->name('ViewCustomer');
Route::post('customer/store'                    , [CustomerContoller::class, 'storeCustomer'])->name('storeCustomer');
Route::get( 'customer/edit/{id}'                , [CustomerContoller::class, 'edit'])->name('editCustomer');
Route::put( 'customer/update/{id}'              , [CustomerContoller::class, 'update'])->name('updateCustomer');
Route::get( 'customer/delete/{id}'              , [CustomerContoller::class, 'destroy'])->name('deleteCustomer');
Route::post('customer/updateStatus'             , [CustomerContoller::class, 'updateCustomerStatus'])->name('updateCustomerStatus');
Route::get( 'customers/view'                    , [CustomerContoller::class, 'ViewCustomer'])->name('ViewCustomer');
Route::post('customers/import'                  , [CustomerContoller::class, 'importCustomers'])->name('importCustomers');
Route::get( 'customers/export-sample'           , [CustomerContoller::class, 'exportSample'])->name('exportCustomers');
Route::get( 'customer/financial-years'          , [CustomerContoller::class, 'getFinancialYears'])->name('financial.years');

// Vender Routes                                            
Route::get( 'vendor/add'                        , [VendorContoller::class, 'AddVendor'])->name('AddVendor');
Route::post('vendor/store'                      , [VendorContoller::class, 'storeVendor'])->name('storeVendor');
Route::get( 'vendor/view'                       , [VendorContoller::class, 'ViewVendor'])->name('ViewVendor');
Route::get( 'vendor/edit/{id}'                  , [VendorContoller::class, 'edit'])->name('editVendor');
Route::put( 'vendor/update/{id}'                , [VendorContoller::class, 'update'])->name('updateVendor');
Route::get( 'vendor/delete/{id}'                , [VendorContoller::class, 'destroy'])->name('deleteVendor');
Route::get( 'vendor/trash'                      , [VendorContoller::class, 'trash'])->name('trashVendor');
Route::get( 'vendor/restore/{id}'               , [VendorContoller::class, 'restore'])->name('restoreVendor');

// Workorder Routes
Route::get( 'workorder/add'                     , [WorkOrderController::class, 'AddWorkOrder'])->name('AddWorkOrder');
Route::get( 'workorder/view'                    , [WorkOrderController::class, 'ViewWorkOrder'])->name('ViewWorkOrder');
Route::post('WorkEntry/store'                   , [WorkOrderController::class, 'storeWorkEntry'])->name('storeWorkEntry');
Route::get( 'workorder/edit/{id}'               , [WorkOrderController::class, 'edit'])->name('editWorkOrder');
Route::put( 'WorkEntry/update/{id}'             , [WorkOrderController::class, 'update'])->name('updateWorkEntry');
Route::get( 'workorder/delete/{id}'             , [WorkOrderController::class, 'destroy'])->name('deleteWorkOrder');
Route::get( 'get-projects/{customerId}'         , [WorkorderController::class, 'getProjects']);
Route::get( 'get-parts/{projectId}'             , [WorkorderController::class, 'getParts']);
Route::post('WorkOrder/import'                  , [WorkorderController::class, 'importWorkOrder'])->name('importWorkOrder');
Route::get( 'workorder/export-sample'           , [WorkorderController::class, 'exportSample'])->name('exportWorkOrder');
Route::get( 'workorder/trash'                   , [WorkOrderController::class, 'trash'])->name('trashWorkOrder');
Route::get( 'workorder/restore/{id}'            , [WorkOrderController::class, 'restore'])->name('restoreWorkOrder');

// Project Routes
Route::get( 'project/add'                       , [ProjectController::class, 'AddProject'])->name('AddProject');
Route::get( 'project/view'                      , [ProjectController::class, 'ViewProject'])->name('ViewProject');
Route::post('Project/store'                     , [ProjectController::class, 'storeProject'])->name('storeProject');
Route::get( 'project/edit/{id}'                 , [ProjectController::class, 'edit'])->name('editProject');
Route::put( 'project/update/{id}'               , [ProjectController::class, 'update'])->name('updateProject');
Route::get( 'project/delete/{id}'               , [ProjectController::class, 'destroy'])->name('deleteProject');
Route::get( 'get-project-quantity/{id}'         , [ProjectController::class, 'getProjectQuantity']);
Route::post('project/import'                    , [ProjectController::class, 'importProjects'])->name('importProjects');
Route::get( 'projects/export-sample'            , [ProjectController::class, 'exportSample'])->name('exportProjects');

// Operator Routes
Route::get( 'operator/add'                      , [OperatorController::class, 'AddOperator'])->name('AddOperator');
Route::post('operator/store'                    , [OperatorController::class, 'storeOperator'])->name('storeOperator');
Route::get( 'operator/edit/{id}'                , [OperatorController::class, 'edit'])->name('editOperator');
Route::put( 'operator/update/{id}'              , [OperatorController::class, 'update'])->name('updateOperator');
Route::post('operator/updatestatus'             , [OperatorController::class,  'updateOperatorStatus'])->name('updateOperatorStatus');
Route::get( 'operator/delete/{id}'              , [OperatorController::class, 'destroy'])->name('deleteOperator');
Route::get( 'operator/trash'                    , [OperatorController::class, 'trash'])->name('trashOperator');
Route::get( 'operator/restore/{id}'             , [OperatorController::class, 'restore'])->name('restoreOperator');

// Machine Routes
Route::get( 'machine/add'                       , [MachineController::class, 'AddMachine'])->name('AddMachine');
Route::post('machine/store'                     , [MachineController::class, 'storeMachine'])->name('storeMachine');
Route::get( 'machine/edit/{id}'                 , [MachineController::class, 'edit'])->name('editMachine');
Route::put( 'machine/update/{id}'               , [MachineController::class, 'update'])->name('updateMachine');
Route::get( 'machine/delete/{id}'               , [MachineController::class, 'destroy'])->name('deleteMachine');
Route::post('machine/statusupdate'              , [MachineController::class, 'updateStatus'])->name('machine.updateStatus');
Route::get( 'machine/trash'                     , [MachineController::class, 'trash'])->name('trashmachine');
Route::get( 'machine/restore/{id}'              , [MachineController::class, 'restore'])->name('restoremachine');

// Setting Routes
Route::get( 'setting/add'                       , [SettingController::class, 'AddSetting'])->name('AddSetting');
Route::post('setting/store'                     , [SettingController::class, 'storeSetting'])->name('storeSetting');
Route::get( 'setting/edit/{id}'                 , [SettingController::class, 'editSetting'])->name('editSetting');
Route::put( 'setting/update/{id}'               , [SettingController::class, 'updateSetting'])->name('updateSetting');
Route::get( 'setting/delete/{id}'               , [SettingController::class, 'destroy'])->name('deleteSetting');
Route::post('setting/updatestatus'              , [SettingController::class,  'updateSettingStatus'])->name('updateSettingStatus');
Route::get( 'setting/trash'                     , [SettingController::class, 'trash'])->name('trashSetting');
Route::get( 'setting/restore/{id}'              , [SettingController::class, 'restore'])->name('restoreSetting');

// HSN Routes
Route::get( 'hsn/add'                           , [HsncodeController::class, 'addHsn'])->name('addHsn');
Route::post('hsn/store'                         , [HsncodeController::class, 'store'])->name('storeHsn');
Route::get( 'hsn/edit/{id}'                     , [HsncodeController::class, 'edit'])->name('editHsn');
Route::put( 'hsn/update/{id}'                   , [HsncodeController::class, 'update'])->name('updateHsn');
Route::post('hsn/status'                        , [HsncodeController::class, 'updateStatus'])->name('updateHsnStatus');
Route::get( 'hsn/delete/{id}'                   , [HsncodeController::class, 'destroy'])->name('deleteHsn');
Route::get( 'hsn/trash'                         , [HsncodeController::class, 'trash'])->name('trashhsn');
Route::get( 'hsn/restore/{id}'                  , [HsncodeController::class, 'restore'])->name('restorehsn');

// MaterialType Routes
Route::get( 'materialtype/add'                  , [MaterialTypeController::class, 'AddMaterialType'])->name('AddMaterialType');
Route::post('materialtype/store'                , [MaterialTypeController::class, 'storeMaterialType'])->name('storeMaterialType');
Route::get( 'materialtype/edit/{id}'            , [MaterialTypeController::class, 'editMaterialType'])->name('editMaterialType');
Route::put( 'materialtype/update/{id}'          , [MaterialTypeController::class, 'updateMaterialType'])->name('updateMaterialType');
Route::get( 'materialtype/delete/{id}'          , [MaterialTypeController::class, 'destroy'])->name('deleteMaterialType');
Route::post('materialtype/updateStatus'         , [MaterialTypeController::class, 'updateMaterialStatus'])->name('updateMaterialStatus');
Route::get( 'materialtype/trash'                , [MaterialTypeController::class, 'trash'])->name('trashMaterialType');
Route::get( 'materialtype/restore/{id}'         , [MaterialTypeController::class, 'restore'])->name('restoreMaterialType');

//financial-year Routes
Route::get( 'financial-year/add'                , [FinancialYearController::class, 'AddFinancialYear'])->name('AddFinancialYear');
Route::post('financial-year/store'              , [FinancialYearController::class, 'storeFinancialYear'])->name('StoreFinancialYear');
Route::get( 'financial-year/edit/{id}'          , [FinancialYearController::class, 'edit'])->name('EditFinancialYear');
Route::put( 'financial-year/update/{id}'        , [FinancialYearController::class, 'update'])->name('UpdateFinancialYear');
Route::get( 'financial-year/delete/{id}'        , [FinancialYearController::class, 'destroy'])->name('DeleteFinancialYear');
Route::post('financial-year/status'             , [FinancialYearController::class, 'updateStatus'])->name('FinancialYearStatus');
Route::get( 'Financial-year/trash'              , [FinancialYearController::class, 'trash'])->name('trashFinancial');
Route::get( 'Financial-year/restore/{id}'       , [FinancialYearController::class, 'restore'])->name('restoreFinancial');

// Setupsheet Routes
Route::get( 'setupsheet/add'                    , [SetupSheetController::class, 'AddSetupSheet'])->name('AddSetupSheet');
Route::post('setupsheet/store'                  , [SetupSheetController::class, 'storeSetupSheet'])->name('storeSetupSheet');
Route::get( 'setupsheet/edit/{id}'              , [SetupSheetController::class, 'editSetupSheet'])->name('editSetupSheet');
Route::get( 'setupsheet/delete/{id}'            , [SetupSheetController::class, 'destroy'])->name('deleteSetupSheet');
Route::get( 'setupsheet/view'                   , [SetupSheetController::class, 'ViewSetupSheet'])->name('ViewSetupSheet');
Route::put( 'setupsheet/update/{encryptedId}'   , [SetupSheetController::class, 'update'])->name('updateSetupSheet');
Route::get( 'setupsheet-data/{partNo}'          , [SetupSheetController::class, 'getSetupSheetData']);
Route::get( 'get-customer-parts/{id}'           , [SetupSheetController::class, 'getCustomerParts'])->name('getCustomerParts');
Route::get( 'getPartsByCustomer/{id}'           , [WorkOrderController::class,  'getPartsByCustomer']);
Route::get( 'setupsheet/trash'                  , [SetupSheetController::class, 'trash'])->name('trashSetupSheet');
Route::get( 'setupsheet/restore/{id}'           , [SetupSheetController::class, 'restore'])->name('restoreSetupSheet');

// Machinerecord Routes 
Route::get( 'machinerecord/add'                 , [MachinerecordController::class, 'AddMachinerecord'])->name('AddMachinerecord');
Route::get( 'machinerecord/view'                , [MachinerecordController::class, 'ViewMachinerecord'])->name('ViewMachinerecord');
Route::post('machinerecord/store'               , [MachinerecordController::class, 'StoreMachinerecord'])->name('StoreMachinerecord');
Route::get( 'machinerecord/edit/{id}'           , [MachinerecordController::class, 'edit'])->name('EditMachinerecord');
Route::put( 'machinerecord/update/{id}'         , [MachinerecordController::class, 'update'])->name('UpdateMachinerecord');
Route::get( 'machinerecord/delete/{id}'         , [MachinerecordController::class, 'destroy'])->name('DeleteMachinerecord');
Route::get( 'machineRecord/trash'               , [MachinerecordController::class, 'trash'])->name('trashMachineRecord');
Route::get( 'machineRecord/restore/{id}'        , [MachinerecordController::class, 'restore'])->name('restoreMachineRecord');
Route::get( 'getinvoice-customer/{customer_id}' , [MachinerecordController::class, 'getInvoiceByCustomer']);

// MaterialReq Routes
Route::get( 'materialReq/add'                   , [MaterialReqController::class, 'AddMaterialReq'])->name('AddMaterialReq');
Route::get( 'materialReq/view'                  , [MaterialReqController::class, 'ViewMaterialReq'])->name('ViewMaterialReq');
Route::post('materialReq/store'                 , [MaterialReqController::class, 'storeMaterialReq'])->name('storeMaterialReq');
Route::get( 'materialReq/edit/{id}'             , [MaterialReqController::class, 'editMaterialReq'])->name('editMaterialReq');
Route::get( 'materialReq/delete/{id}'           , [MaterialReqController::class, 'destroy'])->name('deleteMaterialReq');
Route::put( 'materialReq/update/{id}'           , [MaterialReqController::class, 'updateMaterialReq'])->name('updateMaterialReq');
Route::get( 'materialReq/trash'                 , [MaterialReqController::class, 'trash'])->name('trashMaterialReq');
Route::get( 'materialReq/restore/{id}'          , [MaterialReqController::class, 'restore'])->name('restoreMaterialReq');
Route::get( '/get-material/{id}'                , [MaterialReqController::class, 'getMaterial']);

Route::get('/get-workorders-by-customer/{Id}'   , [MaterialReqController::class, 'getWorkOrdersByCustomer']);


// Materialorder Routes
Route::get( 'materialorder/add'                 , [MaterialorderController::class, 'AddMaterialorder'])->name('AddMaterialorder');
Route::get( 'materialorder/view'                , [MaterialorderController::class, 'ViewMaterialorder'])->name('ViewMaterialorder');
Route::post('materialorder/store'               , [MaterialorderController::class, 'storeMaterialorder'])->name('storeMaterialorder');
Route::get( 'materialorder/edit/{id}'           , [MaterialorderController::class, 'editMaterialorder'])->name('editMaterialorder');
Route::get( 'materialorder/delete/{id}'         , [MaterialorderController::class, 'destroy'])->name('deleteMaterialorder');
Route::put( 'materialorder/update/{id}'         , [MaterialorderController::class, 'update'])->name('updateMaterialorder');
Route::get( 'materialorder/trash'               , [MaterialorderController::class, 'trash'])->name('trashMaterialorder');
Route::get( 'materialorder/restore/{id}'        , [MaterialorderController::class, 'restore'])->name('restoreMaterialorder');
Route::get( '/get-customer-data/{id}'           , [MaterialorderController::class, 'getCustomerData'])->name('getCustomerData');
Route::get( '/get-material-req/{customer_id}'   , [MaterialorderController::class, 'getMaterialRequests']);
Route::get( '/get-material-req-details/{id}'    , [MaterialorderController::class, 'getMaterialRequestDetails']);
Route::get( '/mate-req/by-cust/{customer_id}'   , [MaterialorderController::class, 'getByCustomer']);
Route::get('/get-material-requests/{customer_id}', [MaterialorderController::class, 'getMaterialRequests']);
Route::get('/get-customer-wo/{id}', [MaterialorderController::class, 'getCustomerWo']);

//invoice Routes
Route::get( 'invoice/index'                     , [InvoiceController::class, 'index'])->name('invoice.index');
Route::get( 'invoice/view'                      , [InvoiceController::class, 'view'])->name('invoice.view');
Route::get( 'invoice/add'                       , [InvoiceController::class, 'create'])->name('invoice.add');
Route::post('invoice/store'                     , [InvoiceController::class, 'store'])->name('invoice.store');
Route::get( 'invoice/print/{id}'                , [InvoiceController::class, 'printInvoice'])->name('invoice.print');
Route::get( 'invoice/profarma/{id}'             , [InvoiceController::class, 'proprint'])->name('invoice.proprint');
Route::get( 'get-hsn-details/{id}'              , [InvoiceController::class, 'getHsnDetails'])->name('get.hsn.details');
Route::get( '/invoice/fe-machine-rec/{customer_id}' , [InvoiceController::class, 'getMachineRecords']);
Route::get( '/invoice/get-machine-det/{id}'         , [InvoiceController::class, 'getMachineDetails']); 
Route::get( '/invoice/fetch-mate-rate/{material}'   , [InvoiceController::class, 'getMaterialRate']);

// User Admin CRUD
Route::get('/users'                             , [UserAdminController::class, 'index']) ->name('ListUserAdmin');
Route::get('/user/add'                          , [UserAdminController::class, 'AddUserAdmin'])->name('AddUserAdmin');
Route::post('/user/store'                       , [UserAdminController::class, 'StoreUser'])->name('StoreUser');
Route::get('/user/edit/{id}'                    , [UserAdminController::class, 'edit'])->name('EditUserAdmin');
Route::put('/user/update/{id}'                  , [UserAdminController::class, 'update'])->name('UpdateUserAdmin');
Route::delete('/user/delete/{id}'               , [UserAdminController::class, 'destroy'])->name('DeleteUserAdmin');
Route::post('user/updatestatus'                 , [UserAdminController::class, 'userupdateStatus'])->name('updateStatus'); 
 
Route::get( 'rolepermission'                    , [RolePermissionController::class, 'RolePermission'])->name('RolePermission');
Route::post('rolepermission/store'              , [RolePermissionController::class, 'Store'])->name('Store');
Route::get('/get-role-permissions/{id}'         , [RolePermissionController::class, 'getRolePermissions']);

//Admin Setting Routes
Route::get( 'adminsetting/add'                  , [AdminSettingController::class, 'EditSetting'])->name('Setting');
Route::post('adminSetting/Update'               , [AdminSettingController::class, 'UpdateAdminSetting'])->name('UpdateAdminSetting');

//Subcreation Plan
Route::get( 'payment'                           , [PaymentController::class, 'Payment'])->name('Payment');
Route::post('razorpay/order'                    , [PaymentController::class, 'order'])->name('razorpay.order');
Route::post('payment/success'                   , [PaymentController::class, 'success'])->name('razorpay.success');
Route::get( 'Payment/view'                      , [PaymentController::class, 'PaymentList'])->name('PaymentList');
Route::post('payment/verify'                    , [PaymentController::class, 'verify'])->name('payment.verify');
Route::get( 'payment/view'                      , [PaymentController::class, 'AllPaymentList'])->name('AllPaymentList');

//Proforma Invoice
Route::get( 'proforma/index'                    , [ProformaInvoiceController::class, 'index'])->name('proforma.index');
Route::get( 'proforma/add'                      , [ProformaInvoiceController::class, 'create'])->name('proforma.add');
Route::post('proforma/store'                    , [ProformaInvoiceController::class, 'store'])->name('proforma.store');
Route::get( 'proforma/print/{id}'               , [ProformaInvoiceController::class, 'printInvoice'])->name('proforma.print');
Route::get( 'get-hsn-details/{id}'              , [ProformaInvoiceController::class, 'getHsnDetails'])->name('get.hsn.details');
// Route::get( 'proforma/fetch-machine-records/{customer_id}', [ProformaInvoiceController::class, 'getMachineRecords']);
Route::get('proforma/fetch-machine-records/{customer_id}', [ProformaInvoiceController::class, 'getMachineRecords']
)->name('proforma.fetch.machine.records');

Route::get( 'proforma/get-machine-details/{id}'           , [ProformaInvoiceController::class, 'getMachineDetails']); 
Route::get( 'proforma/fetch-material-rate/{material}'     , [ProformaInvoiceController::class, 'getMaterialRate']);
Route::get( 'proforma/convert/{id}'                       , [ProformaInvoiceController::class, 'convertToTax'])->name('proforma.convert');
Route::get( 'proforma/edit/{id}'                          , [ProformaInvoiceController::class, 'proformaEdit'])->name('proforma.edit');
Route::post('proforma/update/{id}'                        , [ProformaInvoiceController::class, 'proformaUpdate'])->name('proformaUpdate');


// Quotation Routes
Route::get( 'quotation/add'                               , [QuotationController::class, 'Addquotation'])->name('Addquotation');
Route::get( 'quotation/view'                              , [QuotationController::class, 'Viewquotation'])->name('Viewquotation');
Route::post('quotation/store'                             , [QuotationController::class, 'storequotation'])->name('storequotation');
Route::get( 'quotation/edit/{id}'                         , [QuotationController::class, 'editquotation'])->name('editquotation');
Route::get( 'quotation/delete/{id}'                       , [QuotationController::class, 'destroy'])->name('deletequotation');
Route::put( 'quotation/update/{id}'                       , [QuotationController::class, 'update'])->name('updatequotation');
Route::get('printquotation/{id}'                          , [QuotationController::class, 'printquotation'])->name('printquotation');
 


});
Route::get('/clear-app-cache', function () {
 
Artisan::call('config:clear');
Artisan::call('cache:clear');
Artisan::call('route:clear');
Artisan::call('view:clear');
 
    return "Cleared config, cache, route, and view.";
});

 
 












































