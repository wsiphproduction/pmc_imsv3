<?php

######################################################################################
// Main Routes
Route::post('/ims/checklogin', 'Auth\LoginController@checklogin');
Route::get('/ims/dashboard', 'ProcessesController@dashboard')->name('ims.dashboard');
Route::get('/ims/dashboard2', 'ProcessesController@dashboard2');


//Route::view('/ims/login','auth.loginv2');
Route::view('/ims/login','auth.login');
Route::get('/ims/logout', 'Auth\LoginController@logout')->name('logout');

Route::post('/password/change', 'MaintenanceController@passwordChange');
######################################################################################

Route::group(['middleware' => ['authenticated']], function () {
// PO List
	Route::get('/ims/po/list','ProcessesController@po_list')->name('po.list');
	Route::get('/po-search','ProcessesController@search')->name('po.search');

	Route::post('/upload-files','ProcessesController@upload')->name('upload.files');
	Route::post('/manual_close_po','ProcessesController@manual_close_po')->name('manual.close.po');
	Route::post('/manual_delete_po','ProcessesController@manual_delete_po');
	Route::get('/ims/po/details/{id}','ProcessesController@details')->name('po.details');
	Route::post('/copyFile','ProcessesController@preview_file');
	Route::post('/drr/copyFile','ProcessesController@preview_drr_file');

	//Route::post('/manual_open_po','ProcessesController@manual_open_po');

//


// Accounting
	Route::get('/ims/accounting/payments','AccountingController@index')->name('accounting.payments');
	Route::get('/accounting-po-search','AccountingController@search')->name('accounting.po.search');
	Route::get('/filter-payments','AccountingController@search')->name('filter.payments');
	Route::post('/payment-schedule-update','AccountingController@update')->name('payment.schedule.update');

	Route::get('/ims/accounting/overdue','AccountingController@overdue')->name('accounting.overdue-payments');


	Route::post('/mark_as_paid','AccountingController@mark_as_paid');
	Route::get('/ims/payment-schedule/edit/{id}','AccountingController@edit');
	Route::post('/delete-payment','AccountingController@destroy')->name('payment.delete');

	Route::post('/payment/preview-file','AccountingController@preview_file');
//



// Purchasing
	// Route::get('/ims/purchasing','ProcessesController@purchasingIndex')->name('purchasing.index');
	Route::get('/ims/po/create','PurchasingController@addPO')->middleware('authenticated');
	Route::post('/ims/po/save','PurchasingController@savePO')->name('po.store');
	Route::get('/ims/po/edit/{id}','PurchasingController@editPO');
	Route::get('/ims/po/check_duplicate/{id}', 'PurchasingController@checkDuplicatePO');
	Route::post('/ims/po/update','PurchasingController@updatePO')->name('po.update');

	Route::post('/ims/upload', 'ProcessesController@uploadFile');
	
	// Supplier
	Route::get('/ims/supplier','PurchasingController@suppliers')->name('supplier.index');
	Route::get('/search-supplier','PurchasingController@search')->name('search.supplier');
	Route::get('/ims/supplier/create','PurchasingController@supplier_create')->name('supplier.create');
	Route::post('/ims/supplier/store','PurchasingController@supplier_store')->name('supplier.store');

	Route::get('/ims/supplier/edit/{id}','PurchasingController@supplier_edit');
	Route::post('/ims/supplier/update','PurchasingController@supplier_update');

	Route::get('/ims/purchasing/kpi','PurchasingController@kpi')->name('purchasing.kpi');

	Route::get('/ims/purchasing/manage-payment-terms','PurchasingController@payment_terms')->name('payment.terms');
//




// Logistics
	Route::get('/ims/logistics/dashboard','LogisticsController@dashboard')->name('logistics.dashboard');
	//Route::get('/ims/logistics','LogisticsController@index')->name('logistics.index');
	Route::get('/ims/logistics/shipment/create/{id}','LogisticsController@create')->name('create.shipment.schedule');
	Route::get('/ims/logistics/shipment-waybills/{id}','LogisticsController@waybills')->name('view.shipment.schedule');
	Route::get('/ims/shipment/edit/{id}','LogisticsController@edit');

	Route::delete('/ims/shipment/{id}', 'LogisticsController@delete')->name('delete.shipment.schedule');

	Route::get('/ims/logistics/shipment-summary','LogisticsController@shipment_summary')->name('shipment.summary');
	Route::get('/filter-shipment-summary','LogisticsController@shipment_summary_filter')->name('filter.shipment.summary');

	Route::get('/ims/logistics/shipment-kpi','LogisticsController@shipment_kpi')->name('shipment.kpi');
	Route::get('/filter-shipment-kpi','LogisticsController@filter')->name('filter.shipment.kpi');

	Route::get('/ims/logistics/rr-summary','LogisticsController@rr_summary')->name('rr.summary');

	Route::get('/ims/logistics/completion-date','LogisticsController@completion')->name('po.completion');
	Route::get('/filter-completion-date','LogisticsController@filter_completion')->name('filter.completion.date');

	Route::post('/logistics/schedule/store','LogisticsController@store')->name('shipment.schedule.store');
	Route::post('/addWaybill','LogisticsController@addWaybill')->name('create.waybill');
	Route::post('/inTransit','LogisticsController@inTransit');
	Route::post('/customClearing','LogisticsController@customClearing');
	Route::post('/pickUp','LogisticsController@pickUp');
	Route::post('/delivered','LogisticsController@delivered');
	Route::post('/shipment/update','LogisticsController@update')->name('shipment.update');

	Route::get('/chart/deliveries_per_origin', 'ChartController@deliveries_per_origin')->name('chart.deliveries.per.origin');

	//Route::post('/addRemarks','ProcessesController@addRemarks');
	//Route::post('/deleteWaybill','ProcessesController@deleteLogistics');
	//Route::post('/shipment/file-upload','ProcessesController@shipment_upload');
//



// MCD
	Route::get('/ims/receiving/create-services/{id}','McdController@create_services')->name('create.services');
	Route::get('/ims/receiving/summary','McdController@summary')->name('receiving.summary');
	Route::get('/filter-drr-summary','McdController@filter_drr_summary')->name('filter.drr.summary');

	Route::get('/ims/receiving/index','McdController@pendings')->name('receiving.index');
	Route::get('/ims/receiving/drr-create/{waybill}','McdController@create')->name('create.drr');
	Route::post('/ims/mcd/drr/store','McdController@drr_store')->name('drr.store');
//



// Reports
	Route::get('/ims/report/delivery-status','ReportsController@delivery_status')->name('delivery.status.report');
	Route::get('/filter-delivery-status','ReportsController@filter_delivery_status')->name('filter.delivery.status');

	Route::get('/ims/reports/po-status','ReportsController@po_status')->name('po.status');
	Route::get('/filter-po-status','ReportsController@filter_po_status')->name('filter.po.status');

	Route::get('/ims/report/overdue_completion','ReportsController@overdue_completion')->name('overdue.completion');
	Route::get('/filter-overdue-completion','ReportsController@filter_overdue_completion')->name('filter.overdue.completion');

	Route::get('/ims/report/overdue_shipments','ReportsController@overdue_shipments')->name('overdue.shipments');
	Route::get('/filter-overdue-shipments','ReportsController@filter_overdue_shipments')->name('filter.overdue.shipments');

	Route::get('/ims/report/overdue_payables','ReportsController@overdue_payables')->name('overdue.payables');

//

// Analytics
	Route::get('/ims/analytics/monthly', 'AnalyticsController@monthly')->middleware('authenticated');
	Route::get('/ims/analytics/monthly_all', 'AnalyticsController@monthly_all')->middleware('authenticated');
	Route::get('/ims/analytics/waybill_summary', 'AnalyticsController@waybill_summary')->middleware('authenticated');


// Maintenance Routes
	Route::get('/ims/settings/users','MaintenanceController@user_index')->name('users.index');
	Route::get('/ims/settings/users/create','MaintenanceController@user_create')->name('user.create');
	Route::post('/ims/settings/user-store','MaintenanceController@user_store')->name('user.store');
	Route::get('/ims/user/profile/{id}','MaintenanceController@profile')->name('user.profile');
	Route::post('/ims/user/change-password','MaintenanceController@change_password')->name('user.password-update');

	// Route::post('/user/edit', 'MaintenanceController@editUser');
	// Route::post('/deleteUser','MaintenanceController@destroyUser');

	// Route::get('/ims/maintenance/logs', 'MaintenanceController@logs')->middleware('authenticated');
	// Route::get('/filter/logs','MaintenanceController@filterLogs')->name('post');
//



// Export Routes
	Route::get('/export/overdue-po','ExportController@overdue_po')->name('export.overdue-po');
	Route::get('/export/overdue-po-status-with-daterange','ExportController@overdue_po')->name('export.overdue-po-with-daterange');

	Route::get('/export/po-status/{status}','ExportController@po_status')->name('export.overdue-po-status');
	Route::get('/export/po-status-with-daterange/{status}/{from}/{to}','ExportController@po_status')->name('export.overdue-po-status-with-daterange');

	Route::get('/export/deliveries','ExportController@delivery_status')->name('export.deliveries');
	Route::get('/export/delivery-status/{status}','ExportController@delivery_status')->name('export.delivery-status');
	Route::get('/export/delivery-status-with-daterange/{status}/{from}/{to}','ExportController@delivery_status')->name('export.delivery-status-with-daterange');

	Route::get('/export/overdue-payables','ExportController@overdue_payables')->name('export.overdue-payment');
	Route::get('/export/overdue-deliveries/{from}/{to}','ExportController@overdue_deliveries')->name('export.overdue-deliveries');
	Route::get('/export/all-overdue-deliveries','ExportController@overdue_deliveries')->name('export.overdue-deliveries-all');
	


});















// Maintenance Routes

Route::get('/ims/maintenance/users','MaintenanceController@users')->middleware('authenticated');
Route::post('/user/add', 'MaintenanceController@addUser');
Route::post('/user/edit', 'MaintenanceController@editUser');
Route::post('/deleteUser','MaintenanceController@destroyUser');




Route::post('/drr/copyFile','ProcessesController@drr_copy_file');
Route::post('/logistics/copyFile','ProcessesController@logistics_copy_file');


// Ajax Routes
Route::get('/remarks/list/{remarks}','RemarksController@list');
