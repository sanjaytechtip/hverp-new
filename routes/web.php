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

// Route::get('/', function () {
//     //return view('welcome');
// 	return redirect('admin/login');
// });

Route::get('send-email', [App\Http\Controllers\SendEmailController::class, 'index']);

Route::get('/', function () {
	return redirect('/login');
});

Route::get('/home', function () {
	return redirect('admin');
});

Route::get('/runCmd', function(){
    $exitcode = Artisan::call('make:controller testingOnetest --resource');
});

//Route::get('/', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login');
Route::get('admin/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login');

Route::get('admin/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login');
Route::get('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

Route::group(['prefix' => 'admin',  'middleware' => 'auth'], function()
{
	/* Testing Controller */
	Route::get('/testing', [App\Http\Controllers\TestingController::class, 'testing'])->name('testing');
	/* DashboardController */
	Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
	Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
	Route::get('/adduser', [App\Http\Controllers\User\UserController::class, 'create'])->name('adduser');
	Route::get('/userlist', [App\Http\Controllers\User\UserController::class, 'index'])->name('userlist');
	Route::post('/adduser', [App\Http\Controllers\User\UserController::class, 'store'])->name('adduserpost');	
	Route::get('/userdelete/{id}', [App\Http\Controllers\User\UserController::class, 'destroy'])->name('userdelete');
	Route::get('/useredit/{id}', [App\Http\Controllers\User\UserController::class, 'edit'])->name('useredit');
	Route::post('/usereditpost/{id}', [App\Http\Controllers\User\UserController::class, 'update'])->name('usereditpost');
	Route::any('create-form',[App\Http\Controllers\Form\FormController::class, 'createform'])->name('createform');
	Route::any('formadd',[App\Http\Controllers\Form\FormController::class, 'store'])->name('formadd');
	Route::any('formlist',[App\Http\Controllers\Form\FormController::class, 'index'])->name('formlist');
	Route::any('formedit/{id}',[App\Http\Controllers\Form\FormController::class, 'formedit'])->name('formedit');
	Route::any('form-edit-list/{id}',[App\Http\Controllers\Form\FormController::class, 'formeditlist'])->name('formeditlist');
	Route::any('view-form/{id}',[App\Http\Controllers\Form\FormController::class, 'viewform'])->name('viewform');
	Route::any('formpreview/{id}',[App\Http\Controllers\Form\FormController::class, 'formpreview'])->name('formpreview');
	Route::any('adddata/{id}',[App\Http\Controllers\Form\FormController::class, 'adddata'])->name('adddata');
	Route::any('listdata/{id}',[App\Http\Controllers\Form\FormController::class, 'listdata'])->name('listdata');
	Route::any('submit_opening_balance',[App\Http\Controllers\General\FormController::class, 'submit_opening_balance'])->name('submit_opening_balance');
	Route::any('item_details_barcode',[App\Http\Controllers\General\FormController::class, 'item_details_barcode'])->name('item_details_barcode');
	
	
	Route::any('item_details',[App\Http\Controllers\General\FormController::class, 'item_details'])->name('item_details');
	Route::any('item_view_details/{id}',[App\Http\Controllers\General\FormController::class, 'item_view_details'])->name('item_view_details');
	Route::any('image_delete',[App\Http\Controllers\General\FormController::class, 'image_delete'])->name('image_delete');
	
	
	Route::any('formupdate/{form_id}',[App\Http\Controllers\Form\FormController::class, 'formupdate'])->name('formupdate');
	Route::any('editdata/{table}/{id}/{form_id}',[App\Http\Controllers\Form\FormController::class, 'editdata'])->name('editdata');
	Route::any('edit_formupdate/{id}/{form_id}',[App\Http\Controllers\Form\FormController::class, 'edit_formupdate'])->name('edit_formupdate');
	Route::any('deletedata/{table}/{id}/{form_id}',[App\Http\Controllers\Form\FormController::class, 'deletedata'])->name('deletedata');
	Route::any('uniqueValueCheck',[App\Http\Controllers\Form\FormController::class, 'uniqueValueCheck'])->name('uniqueValueCheck');
	Route::any('uniqueValueCheckEdit',[App\Http\Controllers\Form\FormController::class, 'uniqueValueCheckEdit'])->name('uniqueValueCheckEdit');
	Route::any('formedit/{id}',[App\Http\Controllers\Form\FormController::class, 'formedit'])->name('formedit');
	Route::any('form-edit-list/{id}',[App\Http\Controllers\Form\FormController::class, 'formeditlist'])->name('formeditlist');
	Route::any('formdelete/{id}',[App\Http\Controllers\Form\FormController::class, 'destroy'])->name('formdelete');
	Route::any('emailchecker',[App\Http\Controllers\Form\FormController::class, 'emailchecker'])->name('emailchecker');
	Route::any('hsn_task_rate',[App\Http\Controllers\Form\FormController::class, 'hsn_task_rate'])->name('hsn_task_rate');
	Route::any('import_data_master',[App\Http\Controllers\Form\FormController::class, 'import_data_master'])->name('import_data_master');
	Route::any('import_data_master_items',[App\Http\Controllers\Form\FormController::class, 'import_data_master_items'])->name('import_data_master_items');
	Route::any('userassign',[App\Http\Controllers\Form\FormController::class, 'userassign'])->name('userassign');
	Route::any('userassignupdate',[App\Http\Controllers\Form\FormController::class, 'userassignupdate'])->name('userassignupdate');
	Route::any('export_data_master_item/{id}/{table_name}/{limit}',[App\Http\Controllers\Form\FormController::class, 'export_data_master_item'])->name('export_data_master_item');
	Route::any('ajax_item_details',[App\Http\Controllers\Form\FormController::class, 'ajax_item_details'])->name('ajax_item_details');
	Route::any('export_data_master/{id}/{table_name}/{limit}',[App\Http\Controllers\Form\FormController::class, 'export_data_master'])->name('export_data_master');
	Route::any('export_data_master_update',[App\Http\Controllers\Form\FormController::class, 'export_data_master_update'])->name('export_data_master_update');
	Route::any('import_data_master_update',[App\Http\Controllers\Form\FormController::class, 'import_data_master_update'])->name('import_data_master_update');
	Route::any('mrp_data_update',[App\Http\Controllers\Form\FormController::class, 'mrp_data_update'])->name('mrp_data_update');
	Route::any('ajax_user_status_update',[App\Http\Controllers\Form\FormController::class, 'ajax_user_status_update'])->name('ajax_user_status_update');
	Route::any('list_price_data_update',[App\Http\Controllers\Form\FormController::class, 'list_price_data_update'])->name('list_price_data_update');
	Route::any('ajax_get_item_tax_rate',[App\Http\Controllers\Form\FormController::class, 'ajax_get_item_tax_rate'])->name('ajax_get_item_tax_rate');
	
	
	
	

	/* CustomerController */
	Route::any('customer-list', [App\Http\Controllers\Customer\CustomerController::class, 'index'])->name('customerlist');
	Route::any('add-customer', [App\Http\Controllers\Customer\CustomerController::class, 'create'])->name('add_customer');
	Route::any('add-customer-data', [App\Http\Controllers\Customer\CustomerController::class, 'store'])->name('add_customer_data');
	Route::any('delete-customer/{id}', [App\Http\Controllers\Customer\CustomerController::class, 'destroy'])->name('delete_customer_data');
	Route::any('edit-customer/{id}', [App\Http\Controllers\Customer\CustomerController::class, 'edit'])->name('edit_customer');
	Route::any('export_data_master_customer/{type}', [App\Http\Controllers\Customer\CustomerController::class, 'export_data_master_customer'])->name('export_data_master_customer');
	Route::any('import_data_master_customer', [App\Http\Controllers\Customer\CustomerController::class, 'import_data_master_customer'])->name('import_data_master_customer');
	Route::any('ajax_customer_status_update',[App\Http\Controllers\Customer\CustomerController::class, 'ajax_customer_status_update'])->name('ajax_customer_status_update');
	

	/* VendorController */
	Route::any('vendor-list', [App\Http\Controllers\Vendor\VendorController::class, 'index'])->name('vendorlist');
	Route::any('add-vendor', [App\Http\Controllers\Vendor\VendorController::class, 'create'])->name('add_vendor');
	Route::any('add-vendor-data', [App\Http\Controllers\Vendor\VendorController::class, 'store'])->name('add_vendor_data');
	Route::any('delete-vendor/{id}', [App\Http\Controllers\Vendor\VendorController::class, 'destroy'])->name('delete_vendor_data');
	Route::any('edit-vendor/{id}', [App\Http\Controllers\Vendor\VendorController::class, 'edit'])->name('edit_vendor');
	Route::any('export_data_master_vendor/{type}', [App\Http\Controllers\Vendor\VendorController::class, 'export_data_master_vendor'])->name('export_data_master_vendor');
	Route::any('import_data_master_vendor', [App\Http\Controllers\Vendor\VendorController::class, 'import_data_master_vendor'])->name('import_data_master_vendor');
	Route::any('ajax_vendor_status_update',[App\Http\Controllers\Vendor\VendorController::class, 'ajax_vendor_status_update'])->name('ajax_vendor_status_update');
	
	/* Task FMS */
	Route::get('taskfmslist', [App\Http\Controllers\Fms\TaskFmsController::class, 'index'])->name('taskfmslist');
	Route::get('taskfmsdata', [App\Http\Controllers\Fms\TaskFmsController::class, 'taskview'])->name('taskfmsdata');
	Route::any('ajax_select_notes_data', [App\Http\Controllers\Fms\TaskFmsController::class, 'ajax_select_notes_data'])->name('ajax_select_notes_data');
	Route::any('ajax_date_update_data', [App\Http\Controllers\Fms\TaskFmsController::class, 'ajax_date_update_data'])->name('ajax_date_update_data');
	
	/* TaskFormController */
	Route::get('task-form-create', [App\Http\Controllers\General\TaskFormController::class, 'create'])->name('task_form_create');
	Route::get('task-form-create-multiple', [App\Http\Controllers\General\TaskFormController::class, 'create_multiple'])->name('task_form_create_multiple');
	Route::any('ajax_search_ticket_number', [App\Http\Controllers\General\TaskFormController::class, 'ajax_search_ticket_number'])->name('ajax_search_ticket_number');
	Route::any('ajax_search_task_category', [App\Http\Controllers\General\TaskFormController::class, 'ajax_search_task_category'])->name('ajax_search_task_category');
	Route::any('delete_task/{id}', [App\Http\Controllers\General\TaskFormController::class, 'delete_task'])->name('delete_task');
	Route::any('edit_task/{id}', [App\Http\Controllers\General\TaskFormController::class, 'edit_task'])->name('edit_task');
	Route::any('add_task_form', [App\Http\Controllers\General\TaskFormController::class, 'add_task_form'])->name('add_task_form');
	Route::any('add_task_form_multiple', [App\Http\Controllers\General\TaskFormController::class, 'add_task_form_multiple'])->name('add_task_form_multiple');
	Route::any('tasklist', [App\Http\Controllers\General\TaskFormController::class, 'tasklist'])->name('tasklist');
	Route::any('ajax_status_update', [App\Http\Controllers\Fms\TaskFmsController::class, 'ajax_status_update'])->name('ajax_status_update');
	Route::any('ajax_status_update_reopen', [App\Http\Controllers\Fms\TaskFmsController::class, 'ajax_status_update_reopen'])->name('ajax_status_update_reopen');
	
	/* Group of Company Management */
	Route::any('companylist', [App\Http\Controllers\General\CompanyController::class, 'index'])->name('companylist');
	Route::get('addcompany', [App\Http\Controllers\General\CompanyController::class, 'create'])->name('addcompany');
	Route::post('addcompanydata', [App\Http\Controllers\General\CompanyController::class, 'store'])->name('addcompanydata');
	Route::get('companyedit/{id}', [App\Http\Controllers\General\CompanyController::class, 'edit'])->name('companyedit');
	Route::post('editcompanydata/{id}', [App\Http\Controllers\General\CompanyController::class, 'update'])->name('editcompanydata');
	Route::get('companydelete/{id}', [App\Http\Controllers\General\CompanyController::class, 'destroy'])->name('companydelete');
	
	/* Company Management */
	Route::any('ajax_update_notes_data', [App\Http\Controllers\Fms\TaskFmsController::class, 'ajax_update_notes_data'])->name('ajax_update_notes_data');
	Route::get('addcompanydetails', [App\Http\Controllers\General\CompanyDetailController::class, 'create'])->name('addcompanydetails');
	Route::post('addcompanydetailsdata', [App\Http\Controllers\General\CompanyDetailController::class, 'store'])->name('addcompanydetailsdata');
	Route::any('ajax_insert_department_data', [App\Http\Controllers\Fms\TaskFmsController::class, 'ajax_insert_department_data'])->name('ajax_insert_department_data');
	
	/* Quotation */
	Route::any('quotationcreate', [App\Http\Controllers\Quotation\QuotationController::class,'create'])->name('quotationcreate');
    Route::any('quotation-edit/{id}', [App\Http\Controllers\Quotation\QuotationController::class,'edit'])->name('quotationedit');
    Route::any('quotation-revised/{id}/{revised_id}', [App\Http\Controllers\Quotation\QuotationController::class,'quotation_revised'])->name('quotation_revised');
    Route::any('quotation-list', [App\Http\Controllers\Quotation\QuotationController::class,'index'])->name('quotation_list');
    Route::any('quotation_transaction', [App\Http\Controllers\Quotation\QuotationController::class,'sale_quotation_transaction'])->name('quotation_transaction');
    Route::any('delete_quotation/{id}', [App\Http\Controllers\Quotation\QuotationController::class,'destroy'])->name('delete_quotation');
    Route::any('quotation-print/{id}', [App\Http\Controllers\Quotation\QuotationController::class,'quotation_print'])->name('quotation_print');
    Route::any('quotation-pdf/{id}', [App\Http\Controllers\Quotation\QuotationController::class,'quotation_pdf'])->name('quotation_pdf');
    Route::any('quotation-view/{id}', [App\Http\Controllers\Quotation\QuotationController::class,'view'])->name('quotation_view');
    Route::any('item_search_data', [App\Http\Controllers\Quotation\QuotationController::class,'item_search_data'])->name('item_search_data');
    Route::any('item_search_data_quotation', [App\Http\Controllers\Quotation\QuotationController::class,'item_search_data_quotation'])->name('item_search_data_quotation');
    Route::any('ajaxCustomerDetails/{customer_id}',[App\Http\Controllers\Quotation\QuotationController::class,'ajaxCustomerDetails'])->name('ajaxCustomerDetails');
    Route::any('ajaxCustomerDetails_delivery_date/{customer_id}',[App\Http\Controllers\Quotation\QuotationController::class,'ajaxCustomerDetails_delivery_date'])->name('ajaxCustomerDetails_delivery_date');
    Route::any('getmatcharticle', [App\Http\Controllers\Quotation\QuotationController::class,'getmatcharticle'])->name('getmatcharticle');

	/* SAP Code */
	Route::any('sapcodelist', [App\Http\Controllers\SapCode\SapCodeFormController::class,'index'])->name('sapcodelist');
	Route::any('sapcode-form-create', [App\Http\Controllers\SapCode\SapCodeFormController::class,'create'])->name('sapcode_form_create');
	Route::any('add_sapcode_form', [App\Http\Controllers\SapCode\SapCodeFormController::class,'add_sapcode_form'])->name('add_sapcode_form');
	Route::any('edit_sapcode/{id}', [App\Http\Controllers\SapCode\SapCodeFormController::class,'edit_sapcode'])->name('edit_sapcode');
	Route::any('delete_sapcode/{id}', [App\Http\Controllers\SapCode\SapCodeFormController::class,'delete_sapcode'])->name('delete_sapcode');
	Route::any('sapcode_data_update/{id}', [App\Http\Controllers\SapCode\SapCodeFormController::class,'sapcode_data_update'])->name('sapcode_data_update');
	Route::any('sapcode_search', [App\Http\Controllers\SapCode\SapCodeFormController::class,'sapcode_search'])->name('sapcode_search');

	/* Net Rate Management */
	Route::any('netratelist', [App\Http\Controllers\General\NetrateFormController::class,'netratelist'])->name('netratelist');
	Route::any('netrate-form-create', [App\Http\Controllers\General\NetrateFormController::class,'create'])->name('netrate_form_create');
	Route::any('add_netrate_form', [App\Http\Controllers\General\NetrateFormController::class,'add_netrate_form'])->name('add_netrate_form');
	Route::any('edit_netrate/{id}', [App\Http\Controllers\General\NetrateFormController::class,'edit_netrate'])->name('edit_netrate');
	Route::any('delete_netrate/{id}', [App\Http\Controllers\General\NetrateFormController::class,'delete_netrate'])->name('delete_netrate');
	Route::any('netrate_data_update', [App\Http\Controllers\General\NetrateFormController::class,'netrate_data_update'])->name('netrate_data_update');
	Route::any('net_rate_search', [App\Http\Controllers\General\NetrateFormController::class,'net_rate_search'])->name('net_rate_search');

	/* Discount Management */
	Route::any('discountlist', [App\Http\Controllers\General\DiscountFormController::class,'discountlist'])->name('discountlist');
	Route::any('discount-form-create', [App\Http\Controllers\General\DiscountFormController::class,'create'])->name('discount_form_create');
	Route::any('add_discount_form', [App\Http\Controllers\General\DiscountFormController::class,'add_discount_form'])->name('add_discount_form');
	Route::any('edit_discount/{id}', [App\Http\Controllers\General\DiscountFormController::class,'edit_discount'])->name('edit_discount');
	Route::any('discount_data_update', [App\Http\Controllers\General\DiscountFormController::class,'discount_data_update'])->name('discount_data_update');
	Route::any('delete_discount/{id}', [App\Http\Controllers\General\DiscountFormController::class,'delete_discount'])->name('delete_discount');
	Route::any('get_purchase_net_rate_data',[App\Http\Controllers\General\DiscountFormController::class,'get_purchase_net_rate_data'])->name('get_purchase_net_rate_data');
	Route::any('get_purchase_discount_data',[App\Http\Controllers\General\DiscountFormController::class,'get_purchase_discount_data'])->name('get_purchase_discount_data');
	
		
	/* Dispatch Order Controller */
	Route::any('order-to-dispatch', [App\Http\Controllers\General\OrderToDispatchController::class,'index'])->name('order_to_dispatch');
	Route::any('order_to_dispatch/email_send', [App\Http\Controllers\General\OrderToDispatchController::class,'email_send'])->name('email_send');
    Route::any('order_to_dispatch/item_display', [App\Http\Controllers\General\OrderToDispatchController::class,'item_display'])->name('item_display');
    Route::any('order_to_dispatch/ajaxOrderToDispatchItems', [App\Http\Controllers\General\OrderToDispatchController::class,'ajaxOrderToDispatchItems'])->name('ajaxOrderToDispatchItems');
    Route::any('add-replacement-order', [App\Http\Controllers\General\OrderToDispatchController::class,'add_replacement_order'])->name('add_replacement_order');
    Route::any('add-direct-order', [App\Http\Controllers\General\OrderToDispatchController::class,'add_direct_order'])->name('add_direct_order');
	Route::any('order_to_dispatch/make_oc', [App\Http\Controllers\General\OrderToDispatchController::class,'make_oc'])->name('make_oc');
	Route::any('ajax_oc_search_data', [App\Http\Controllers\General\OrderToDispatchController::class,'ajax_oc_search_data'])->name('ajax_oc_search_data');
	Route::any('ajaxOCItems', [App\Http\Controllers\General\OrderToDispatchController::class,'ajaxOCItems'])->name('ajaxOCItems');
	Route::any('order-to-make-oc/{id}', [App\Http\Controllers\General\OrderToDispatchController::class,'orderTomakeOC'])->name('orderTomakeOC');
	Route::any('order_to_dispatch/get_department', [App\Http\Controllers\General\OrderToDispatchController::class,'get_department'])->name('get_department');
	Route::any('order_to_dispatch/get_staff', [App\Http\Controllers\General\OrderToDispatchController::class,'get_staff'])->name('get_staff');
	Route::any('order_to_dispatch/get_staff_email', [App\Http\Controllers\General\OrderToDispatchController::class,'get_staff_email'])->name('get_staff_email');
	
	
   
	/* Sale Order Controller */
	Route::any('saleordercreate', [App\Http\Controllers\General\SaleOrderController::class,'create'])->name('saleordercreate');
	Route::any('saleorder-list', [App\Http\Controllers\General\SaleOrderController::class,'index'])->name('saleorder_list');
	Route::any('saleorder-edit/{id}', [App\Http\Controllers\General\SaleOrderController::class,'edit'])->name('saleorderedit');
	Route::any('saleorder-revised/{id}/{revised_id}', [App\Http\Controllers\General\SaleOrderController::class,'saleorder_revised'])->name('saleorder_revised');
	Route::any('delete_saleorder/{id}', [App\Http\Controllers\General\SaleOrderController::class,'destroy'])->name('delete_saleorder');
	Route::any('saleorder-print/{id}', [App\Http\Controllers\General\SaleOrderController::class,'saleorder_print'])->name('saleorder_print');
	Route::any('saleorder-pdf/{id}', [App\Http\Controllers\General\SaleOrderController::class,'saleorder_pdf'])->name('saleorder_pdf');
	Route::any('saleorder-view/{id}', [App\Http\Controllers\General\SaleOrderController::class,'view'])->name('saleorder_view');
    Route::any('item_search_data_so', [App\Http\Controllers\General\SaleOrderController::class,'item_search_data_so'])->name('item_search_data_so');
	Route::any('item_search_data_saleorder', [App\Http\Controllers\General\SaleOrderController::class,'item_search_data_saleorder'])->name('item_search_data_saleorder');
    Route::any('ajaxSearchQuotation/{customer_id}', [App\Http\Controllers\General\SaleOrderController::class,'ajaxSearchQuotation'])->name('ajaxSearchQuotation');
    Route::any('ajaxQuotationItems', [App\Http\Controllers\General\SaleOrderController::class,'ajaxQuotationItems'])->name('ajaxQuotationItems');
    Route::any('sale_transaction', [App\Http\Controllers\General\SaleOrderController::class,'sale_transaction'])->name('sale_transaction');
    Route::any('saleorder_ref_no_search', [App\Http\Controllers\General\SaleOrderController::class,'saleorder_ref_no_search'])->name('saleorder_ref_no_search');
    Route::any('so_net_rate_search', [App\Http\Controllers\General\SaleOrderController::class,'so_net_rate_search'])->name('so_net_rate_search');
    Route::any('ajaxSalesItems', [App\Http\Controllers\General\SaleOrderController::class,'ajaxSalesItems'])->name('ajaxSalesItems');
    Route::any('ajax_order_item_delete/{id}', [App\Http\Controllers\General\SaleOrderController::class,'ajax_order_item_delete'])->name('ajax_order_item_delete');
    Route::any('get-recurring-type-data', [App\Http\Controllers\General\SaleOrderController::class,'get_recurring_type_data'])->name('get_recurring_type_data');
    /*OC */
    Route::any('outwardchallanlist', [App\Http\Controllers\General\OutwardchallanController::class,'index'])->name('outwardchallanlist');
	Route::any('outwardchallanview/{id}', [App\Http\Controllers\General\OutwardchallanController::class,'details'])->name('outwardchallanview');
	/* Purchase Order */
	
	Route::any('purchaseordercreate', [App\Http\Controllers\General\PurchaseOrderController::class,'create'])->name('purchaseordercreate');
	Route::any('purchaseorder-list', [App\Http\Controllers\General\PurchaseOrderController::class,'index'])->name('purchaseorder_list');
	Route::any('purchaseorder-edit/{id}', [App\Http\Controllers\General\PurchaseOrderController::class,'edit'])->name('purchaseorderedit');
	Route::any('delete_purchaseorder/{id}', [App\Http\Controllers\General\PurchaseOrderController::class,'destroy'])->name('delete_purchaseorder');
	Route::any('purchaseorder-print/{id}', [App\Http\Controllers\General\PurchaseOrderController::class,'purchaseorder_print'])->name('purchaseorder_print');
	Route::any('purchaseorder-pdf/{id}', [App\Http\Controllers\General\PurchaseOrderController::class,'purchaseorder_pdf'])->name('purchaseorder_pdf');
	Route::any('purchaseorder-view/{id}', [App\Http\Controllers\General\PurchaseOrderController::class,'view'])->name('purchaseorder_view');
	Route::any('net_rate_search_purchase', [App\Http\Controllers\General\PurchaseOrderController::class,'net_rate_search_purchase'])->name('net_rate_search_purchase');
	Route::any('net_rate_search_purchase_post', [App\Http\Controllers\General\PurchaseOrderController::class,'net_rate_search_purchase_post'])->name('net_rate_search_purchase_post');
	Route::any('purchase_net_rate_search', [App\Http\Controllers\General\PurchaseOrderController::class,'purchase_net_rate_search'])->name('purchase_net_rate_search');
	Route::any('item_search_data_po', [App\Http\Controllers\General\PurchaseOrderController::class,'item_search_data_po'])->name('item_search_data_po');
	Route::any('purchase_net_rate_search_po', [App\Http\Controllers\General\PurchaseOrderController::class,'purchase_net_rate_search_po'])->name('purchase_net_rate_search_po');
	Route::any('purchase_net_rate_search_qu', [App\Http\Controllers\General\PurchaseOrderController::class,'purchase_net_rate_search_qu'])->name('purchase_net_rate_search_qu');
	Route::any('net_rate_search_po_details', [App\Http\Controllers\General\PurchaseOrderController::class,'net_rate_search_po_details'])->name('net_rate_search_po_details');
	Route::any('purchaseorder_ref_no_search', [App\Http\Controllers\General\PurchaseOrderController::class,'purchaseorder_ref_no_search'])->name('purchaseorder_ref_no_search');
	Route::any('pagination-ajax',[App\Http\Controllers\General\PurchaseOrderController::class,'ajaxPagination'])->name('ajax.pagination');
	Route::any('pagination-ajax2',[App\Http\Controllers\General\PurchaseOrderController::class,'ajaxPagination2'])->name('ajax.pagination2');
	Route::any('ajaxVendorDetails/{vendor_id}',[App\Http\Controllers\General\PurchaseOrderController::class,'ajaxVendorDetails'])->name('ajaxVendorDetails');
	Route::any('add-balance-stock', [App\Http\Controllers\General\BalanceStockController::class,'add_balance_stock'])->name('add_balance_stock');
	Route::any('opening-stock-list', [App\Http\Controllers\General\BalanceStockController::class,'index'])->name('opening_stock_list');
	Route::any('delete_opening_stock/{id}', [App\Http\Controllers\General\BalanceStockController::class,'delete_opening_stock'])->name('delete_opening_stock');
	Route::any('balance-stock-list', [App\Http\Controllers\General\BalanceStockController::class,'balance_stock_list'])->name('balance_stock_list');
	Route::any('delete_balance_stock/{id}', [App\Http\Controllers\General\BalanceStockController::class,'delete_balance_stock'])->name('delete_balance_stock');
	Route::any('ajax-rack-details', [App\Http\Controllers\General\BalanceStockController::class,'ajax_rack_details'])->name('ajax_rack_details');
	
	/* MRN */ 
	Route::any('mrncreate', [App\Http\Controllers\General\MRNController::class,'create'])->name('mrncreate');
	Route::any('mrn-list', [App\Http\Controllers\General\MRNController::class,'index'])->name('mrn_list');
	Route::any('item_search_data_mrn', [App\Http\Controllers\General\MRNController::class,'item_search_data_mrn'])->name('item_search_data_mrn');
	Route::any('mrn-edit/{id}', [App\Http\Controllers\General\MRNController::class,'edit'])->name('mrn_edit');
	Route::any('view_mrn/{id}', [App\Http\Controllers\General\MRNController::class,'view_mrn'])->name('view_mrn');
	Route::any('delete_mrn/{id}', [App\Http\Controllers\General\MRNController::class,'destroy'])->name('delete_mrn');
	Route::any('submit_inward_balance', [App\Http\Controllers\General\MRNController::class,'submit_inward_balance'])->name('submit_inward_balance');
	Route::any('stock-inward-list', [App\Http\Controllers\General\MRNController::class,'stock_inward_list'])->name('stock_inward_list');
	Route::any('rejected-qty-list', [App\Http\Controllers\General\MRNController::class,'rejected_qty_list'])->name('rejected_qty_list');
	Route::any('breakage-qty-list', [App\Http\Controllers\General\MRNController::class,'breakage_qty_list'])->name('breakage_qty_list');
	Route::any('shortage-qty-list', [App\Http\Controllers\General\MRNController::class,'shortage_qty_list'])->name('shortage_qty_list');
	Route::any('update_stock_inward', [App\Http\Controllers\General\MRNController::class,'update_stock_inward'])->name('update_stock_inward');
	Route::any('ajaxPurchaseItems', [App\Http\Controllers\General\MRNController::class,'ajaxPurchaseItems'])->name('ajaxPurchaseItems');
	Route::any('purchase_search_po', [App\Http\Controllers\General\MRNController::class,'purchase_search_po'])->name('purchase_search_po');
	Route::any('ajaxSearchPurchase/{id}', [App\Http\Controllers\General\MRNController::class,'ajaxSearchPurchase'])->name('ajaxSearchPurchase');
	Route::any('purchase_net_rate_search_mrn', [App\Http\Controllers\General\MRNController::class,'purchase_net_rate_search_mrn'])->name('purchase_net_rate_search_mrn');
	Route::any('stock_inward_image_remove', [App\Http\Controllers\General\MRNController::class,'stock_inward_image_remove'])->name('stock_inward_image_remove');
	Route::any('mrn_image_remove', [App\Http\Controllers\General\MRNController::class,'mrn_image_remove'])->name('mrn_image_remove');
	
	

	/* Purchase Discount Management */
	Route::any('purchase-discount-net-rate-list', [App\Http\Controllers\General\PurchaseDiscountFormController::class,'index'])->name('purchase_discount_net_rate_list');
	Route::any('purchase-discount-form-create', [App\Http\Controllers\General\PurchaseDiscountFormController::class,'create'])->name('purchase_discount_form_create');
	Route::any('purchase_add_discount_form', [App\Http\Controllers\General\PurchaseDiscountFormController::class,'purchase_add_discount_form'])->name('purchase_add_discount_form');
	Route::any('purchase-netrate-form-create', [App\Http\Controllers\General\PurchaseDiscountFormController::class,'purchase_netrate_form_create'])->name('purchase_netrate_form_create');
	Route::any('purchase_discount_data_update', [App\Http\Controllers\General\PurchaseDiscountFormController::class,'purchase_discount_data_update'])->name('purchase_discount_data_update');
	Route::any('purchase_delete_discount/{id}', [App\Http\Controllers\General\PurchaseDiscountFormController::class,'purchase_delete_discount'])->name('purchase_delete_discount');
	Route::any('purchase-net-rate-list', [App\Http\Controllers\General\PurchaseDiscountFormController::class,'purchase_net_rate_list'])->name('purchase_net_rate_list');
	Route::any('add-purchase-netrate-form', [App\Http\Controllers\General\PurchaseDiscountFormController::class,'add_purchase_netrate_form'])->name('add_purchase_netrate_form');
	Route::any('delete_purchase_netrate/{id}', [App\Http\Controllers\General\PurchaseDiscountFormController::class,'delete_purchase_netrate'])->name('delete_purchase_netrate');
	Route::any('purchase_netrate_data_update', [App\Http\Controllers\General\PurchaseDiscountFormController::class,'purchase_netrate_data_update'])->name('purchase_netrate_data_update');
	
	/* Challan Controller */
	Route::any('challan-item-list/{id}', [App\Http\Controllers\General\ChallanController::class,'challan_item_list'])->name('challan_item_list');
	Route::any('order_to_dispatch/make_challan', [App\Http\Controllers\General\ChallanController::class,'make_challan'])->name('make_challan');
	Route::any('challan-list', [App\Http\Controllers\General\ChallanController::class,'index'])->name('challan_list');
	Route::any('dispatch-planning', [App\Http\Controllers\General\ChallanController::class,'dispatch_planning'])->name('dispatch_planning');
	Route::any('update_dispatch_planning', [App\Http\Controllers\General\ChallanController::class,'update_dispatch_planning'])->name('update_dispatch_planning');
	Route::any('cronjob_everyday', [App\Http\Controllers\Cronjob\CronjobController::class,'cronjob_everyday'])->name('cronjob_everyday');
	Route::any('create_weekly_task', [App\Http\Controllers\Cronjob\CronjobController::class,'create_weekly_task'])->name('create_weekly_task');
	
	
	
	
});
Auth::routes();