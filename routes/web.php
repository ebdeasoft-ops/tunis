<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CostCenterExport;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
// استيراد الـ Controllers بالكامل
use App\Http\Controllers\{
    AdminController, SupllierController, CustomersController, FinancialAccountsController,
    AcountsTypeController, ProductsDamageController, ProductsController, InvoicesController,
    AcountesController, CredittransactionsController, ExpensesController, ReportController,
    BranchsController, RoleController, UserController, SupprocessesController, AvtController,
    EmployeeController, ProductMovementAnotherBranchController, TransferMoneyToMainbranchController,
    DeliveryProductToTheCustomerController, CashWithdrawalFromTheBankController, DeliveryNoteController,
    SystemSettingController, LoansController, DliveryController, ProductsMixController
};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// الصفحة الرئيسية (تسجيل الدخول)
Route::get('/', function () {
    return view('auth.login');
});

// --------------------------------------------------------------------------
// مسارات عامة (Public / Guest Routes)
// --------------------------------------------------------------------------

Route::get('/get-pending-invoices', function () {
    $branchId = auth()->user()->branchs_id;
    $url = "http://elmoaadat.ebdea.online/api/purchases/getinvoicecount_by_branch_not_recive/{$branchId}";
    return Http::get($url)->json();
});

Route::get('export-cost-centers', function (Request $request) {
    return Excel::download(
        new CostCenterExport($request->query('start_at'), $request->query('end_at'), $request->query('cost_center')),
        'cost_centers_report_' . date('Y-m-d') . '.xlsx'
    );
});

Route::group(['prefix' => 'stock', 'as' => 'stock.'], function () {

    // مسار تحميل نموذج الإكسيل (Template)
    Route::get('/download-template', [SupprocessesController::class, 'downloadTemplate'])
         ->name('download_template');

    // مسار رفع ومعالجة ملف الإكسيل عبر AJAX
    Route::post('/import-ajax', [SupprocessesController::class, 'importStockAjax'])
         ->name('import_ajax');

});

Route::post('Purchase_returns_Data', [ProductsController::class, 'Purchase_returns_Data']);
Route::post('save_invoice_purchase_roken', [ProductsController::class, 'save_invoice_purchase_roken']);
Route::post('searchaboutproduct_location_function', [ProductsController::class, 'searchaboutproduct_location_function']);
Route::get('/confirm_purchase', [ProductsController::class, 'purchases_roken']);
Route::get('/getquotebybranch/{branch}', [InvoicesController::class, 'getquotebybranch']);
Route::post('/posttestajax', [BranchsController::class, 'posttestajax']);

// --------------------------------------------------------------------------
// مسارات محمية بالـ Authentication (Sanctum & Verified)
// --------------------------------------------------------------------------
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {

    // 1. إدارة الصلاحيات والمستخدمين
    Route::group(['middleware' => ['auth']], function () {
        Route::resource('roles', RoleController::class);
        Route::resource('users', UserController::class);
    });

    // 2. مجموعة مسارات Delivery Notes
    Route::controller(DeliveryNoteController::class)->prefix('delivery-notes')->as('delivery-notes.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
        Route::get('/print/{id}', 'print')->name('print');
        Route::delete('/delete/{id}', 'destroy')->name('delete');
        Route::get('/{id}/items', 'getItems')->name('get-items');
    });
    Route::get('/products-search', [DeliveryNoteController::class, 'searchProducts'])->name('products.search');
    Route::get('/get-customers-ajax', [DeliveryNoteController::class, 'getCustomers'])->name('customers.ajax');

    // 3. مجموعة مسارات المشتريات (Purchases & Base Products Controller)
    Route::controller(ProductsController::class)->group(function () {
        Route::group(['prefix' => 'purchases', 'as' => 'purchases.'], function () {
            Route::get('/download-template', 'downloadTemplate')->name('download_template');
            Route::post('/import-ajax', 'importPurchasesAjax')->name('import_ajax');
        });
// --- مسارات المبيعات (التي أضفتها أنت الآن) ---
    Route::group(['prefix' => 'sales', 'as' => 'sales.'], function () {
        Route::get('/download-template', 'downloadSalesTemplate')->name('download_template');
        Route::post('/import-ajax', 'importSalesAjax')->name('import_ajax');
    });
        // مسارات المشتريات والأسعار المضافة حديثاً
        Route::get('getproductspricetocustomer', 'showProductsPrice');
        Route::get('getproductsprice', 'getProductsPriceFromSupplier');
        Route::get('getproduct/{id}', 'show');
        Route::get('savepurchase/{id}/{payment}/{supplier}/{shipping}/{date}/{another_bank}', 'savepurchase');
        Route::get('getProductdJsonDecode/{id}', 'getProductdJsonDecode');
        Route::get('updateorder_purchase/{id}', 'updateorder_purchase');
        Route::get('Purchase_returns', 'Purchase_returns');
        Route::get('purchases', 'purchases');
        Route::post('printavaliableproduct', 'create');
        Route::get('printavaliableproductprice', 'printProductPriceToCustomer');
        Route::post('printproductprice', 'printProductPrice');
        Route::post('print_all_products_price', 'print_all_products_price');
        Route::post('save_purchase_order ', 'save_purchase_order');
        Route::post('Addproducttopurchases', 'Addproducttopurchases');
        Route::post('purchaseproduct_update', 'update');
        Route::post('returnAllpurchase', 'returnAllpurchase');
        Route::post('purchaseproduct_delete', 'destroy');
        Route::get('goToReceipt', 'goToReceipt');
        Route::get('update_offer_price_supplier/{id}', 'update_offer_price_supplier');
        Route::post('order_price_from_suppliers', 'order_price_from_suppliers');
        Route::post('report_offer_price_customer', 'AddproductPriceToCustomer');
        Route::post('AddproductPriceToCustomer', 'AddproductPriceToCustomer');
        Route::post('print_order_perice_to_customer', 'print_order_perice_to_customerByPost');
        Route::get('report_offer_price_customer', 'print_order_perice_to_customer');
        Route::get('set_customer_quotation/{id}/{customer}', 'set_customer_quotation');
        Route::post('updatePurchase', 'updatePurchase');
        Route::post('updatePurchaseOrder', 'updatePurchaseOrder');
        Route::post('updatePurchaseOrderToIncrease', 'updatePurchaseOrderToIncrease');
        Route::get('/makeTotalDiscontpurchases/{idInvoice}/{discountvalue}', 'makeTotalDiscontpurchases');
        Route::get('/makeTotalDiscontOferprice/{idInvoice}/{discountvalue}', 'makeTotalDiscontOferprice');
        Route::get('/cancelInvoiceDiscontpurcgases/{idInvoice}', 'cancelInvoiceDiscontpurcgases');
        Route::post('increasePurchase', 'increasePurchase');
        Route::post('uploadfilepurchases', 'uploadfilepurchases');
        Route::post('updateproductalldatapurchases', 'updateproductalldatapurchases');
        Route::get('get_all_products_in_orderto_supplier/{order_id}', 'get_all_products_in_orderto_supplier');
        Route::get('getinvoicesbyspplluer/{order_id}', 'getinvoicesbyspplluer');
        Route::get('changePaymethodPurchase/{id}/{paymendMethod}', 'changePaymethodIPurchases');

        // المسارات القديمة التابعة لنفس الكنترولر
        Route::get('/itemcards/search', 'search')->name('itemcards.search');
        Route::get('/clientnamesearch/search', 'clientnamesearch')->name('clientnamesearch.search');
        Route::get('/suppliernamesearch/search', 'suppliernamesearch')->name('suppliernamesearch.search');
        Route::get('/searchfinancial_accounts/search', 'searchfinancial_accounts')->name('searchfinancial_accounts.search');
        Route::get('/getByCodenew/{barcode}', 'getByCodenew')->name('getByCodenew');
        Route::get('/generate_barcode/{id}', 'generate_barcode')->name('admin.itemcard.generate_barcode');
        Route::post('save_invoice_purchase', 'save_invoice_purchase');
        Route::post('save_invoice_qutation', 'save_invoice_qutation');
        Route::get('show_or_not_number/{id}/{statuse}', 'show_or_not_number');
        Route::get('preinvoiceprint/{id}', 'print_preinvoice_to_customer');
        Route::get('delete_offer_price/{id}', 'delete_offer_price');
        Route::get('delete_purchase_invoice/{id}', 'delete_purchase_invoice');
        Route::get('OfferPricesTocustomer_for_update/{id}', 'OfferPricesTocustomer_for_update');
        Route::get('/export-products/{branch_id}', 'exportAllBranchs')->name('products.export');
        Route::get('previous_deliver_Invoices', 'previous_deliver_Invoices');
        Route::get('getAllinvices_deliveryajax', 'getAllinvices_deliveryajax');
        Route::get('getinvoicesbycustomerdelivery/{date}', 'getinvoicesbycustomerdelivery');
        Route::get('searchaboutinvoiceByIdfunction_delivery/{date}', 'searchaboutinvoiceByIdfunction_delivery');
        Route::get('replaceproducts/{branch_id}/{productId}', 'replaceproducts');
        Route::get('make_Note/{id}/{note}', 'make_Note');
        Route::get('find_account/{id}', 'find_account');
        Route::get('/updateofficebyidforupdate/{id}', 'updateofficebyidforupdate');
        Route::post('/product_branchs_id_ajax', 'product_branchs_id_ajax');
        Route::post('/updateproductallDataofferprice', 'updateproductallDataofferprice');
        Route::get('/generate_pdf_qoute/{id}', 'generate_pdf_qoute');
        Route::get('/updatepurchasesbyid/{id}', 'updatepurchasesbyid');
        Route::get('getAllinvicesapurchasesjax', 'getAllinvicesapurchasesjax');
        Route::get('searchaboutinvoiceByIdfunctionpurchases/{date}', 'searchaboutinvoiceByIdfunctionpurchases');
        Route::get('getinvoicesbycustomer/{date}', 'getinvoicesbycustomer');
        Route::get('goToSale', 'goToSale');
        Route::get('goToSaleBypage', 'goToSaleByPage');
        Route::get('searchaboutproduct/{searchtext}', 'searchaboutproduct');
        Route::get('/updatequtation/{id}', 'updatequtation');
        Route::get('product_mix', 'product_mix');
        Route::get('showAllproductpaginate', 'showAllproductpaginate');
        Route::get('getproductbyid/{id}', 'getproductbyid');
        Route::get('searchAllproductpaginate/{searchtext}', 'searchAllproductpaginate');
        Route::get('searchAllproductpaginatenew/{searchtext}', 'searchAllproductpaginatenew');
        Route::post('searchAllproductpaginatenew_by_post', 'searchAllproductpaginatenew_by_post');
        Route::get('searchAllInvoicespaginatenew/{date}', 'searchAllInvoicespaginatenew');
        Route::get('searchaboutinvoiceByIdfunction/{date}', 'searchaboutinvoiceByIdfunction');
        Route::get('searchaboutinvoice_pendding_ByIdfunction/{date}', 'searchaboutinvoice_pendding_ByIdfunction');
        Route::get('getinvoices_bending_bycustomer/{date}', 'getinvoices_bending_bycustomer');
        Route::get('getinvoices_bending_bydate/{date}', 'getinvoices_bending_bydate');
        Route::get('searchaboutReciptByIdfunction/{date}', 'searchaboutReciptByIdfunction');
        Route::get('searchAllRecieptspaginatenew/{date}', 'searchAllRecieptspaginatenew');
        Route::get('Allproductpaginatenew', 'Allproductpaginatenew');
        Route::get('getAllinvicesajax', 'getAllinvicesajax');
        Route::get('getAllRecieptsjax', 'getAllRecieptsjax');
        Route::get('searchChooseProductpaginatenew/{searchtext}/{branch_id}', 'searchChooseProductpaginatenew');
        Route::get('ChooseProductpaginatenew/{branch_id}', 'ChooseProductpaginatenew');
        Route::get('searchChooseProductpaginatenewSale/{searchtext}/{branch_id}', 'searchChooseProductpaginatenewSale');
        Route::post('searchChooseProductpaginatenewSaleBypost', 'searchChooseProductpaginatenewSaleBypost');
        Route::post('searchChooseProductpaginatenewpurchaseBypost', 'searchChooseProductpaginatenewpurchaseBypost');
        Route::get('ChooseProductpaginatenewSale/{branch_id}', 'ChooseProductpaginatenewSale');
        Route::get('showAllproductpaginatepurchase/{branchId}', 'showAllproductpaginatepurchase');
        Route::get('searchAllproductpaginatepurchase/{branchId}/{searchtext}', 'searchAllproductpaginatepurchase');
        Route::get('searchaboutproductwithBranchId/{searchtext}/{branchId}', 'searchaboutproductwithBranchId');
        Route::post('ChooseProductpaginatenewupdate', 'ChooseProductpaginatenewupdate');
        Route::get('printReturnpurchases/{id}', 'printReturnpurchases');
        Route::get('ShowAllNotifications', 'ShowAllNotifications');
        Route::get('showAllProducts', 'showAllProducts');
        Route::get('showAllProducts_IN_Wherehouse', 'showAllProducts_IN_Wherehouse');
        Route::get('previousPurchasesInvoices', 'previousPurchasesInvoices');
        Route::get('previousSalesInvoices', 'previousSalesInvoices');
        Route::get('previousRecieptInvoices', 'previousRecieptInvoices');
        Route::get('printOrderPriceFromSupplier/{id}', 'printOrderPriceFromSupplier');
        Route::post('printOrderPriceFromSupplier', 'printOrderPriceFromSupplierBypost');
        Route::get('getproductsquntitytocustomer', 'index');
        Route::get('/deleteitem/{id}', 'deleteitem');
        Route::post('/product_group_ajax', 'product_group_ajax');
        Route::post('/product_sale_group_ajax', 'product_sale_group_ajax');
        Route::get('/detproductbycode/{idInvoice}/{branch}', 'detproductbycode');
        Route::get('getallpurshasesfromsupplier/{id}', 'getallpurshasesfromsupplier');
        Route::get('operationproducts/{branch_id}/{productId}', 'operationproducts');
        Route::get('openfile/{path}', 'openfilefile');
    });

    // 4. مجموعة تحويل الأموال إلى الفرع الرئيسي (Transfer Money To Main Branch)
    Route::controller(TransferMoneyToMainbranchController::class)->group(function () {
        Route::post('Transfertomainbranch', 'store');
        Route::post('updateTransfertomainbranch', 'updateTransfertomainbranch');
        Route::post('updateTransfertomainbranchnotconfirm', 'updateTransfertomainbranchnotconfirm');
        Route::get('confirmTransfarToMainBranch/{id}', 'show');
        Route::get('rejectTransfarToMainBranch/{id}', 'rejectTransfarToMainBranch');
        Route::get('pendingtransfers', 'pendingtransfers');
        Route::post('print_Transfer_Main_Branch', 'print_Transfer_Main_Branch');
    });

    // 5. الحسابات والعمليات النقدية والبنكية (Accounts & Cash Box)
    Route::controller(AcountesController::class)->group(function () {
        Route::get('Statement_of_Changes_in_Equity_Report', 'Statement_of_Changes_in_Equity_Report');
        Route::get('get_all_kid_yaomy_jax', 'get_all_kid_yaomy_jax');
        Route::get('search_by_decoumentNo_kid_yomy/{id}', 'search_by_decoumentNo_kid_yomy');
        Route::get('Cash_withdrawal_from_the_bank', 'Cash_withdrawal_from_the_bank');
        Route::get('voncher', 'voncher');
        Route::get('get_all_send_kabd_jax', 'get_all_send_kabd_jax');
        Route::get('get_all_send_serf_jax', 'get_all_send_serf_jax');
        Route::get('opining_balnce_ajax', 'opining_balnce_ajax');
        Route::get('convertcashboxToBank', 'convertcashboxToBank');
        Route::get('Transfertomainbranch', 'transferMainBranch');
        Route::get('confirmTransfertomainbranch', 'confirmTransfertomainbranch');
        Route::get('cashEcprnse', 'cashEcprnse');
        Route::get('income', 'income');
        Route::get('go_to_bank', 'go_to_bank');
        
        Route::post('changesInEquity', 'changesInEquity')->name('reports.changes_in_equity');
        Route::post('Add_blance_from_bank', 'Add_blance_from_bank');
        Route::post('updateAdd_blance_from_bank', 'updateAdd_blance_from_bank');
        Route::post('convertcashboxToBank', 'SearchconvertcashboxToBank');
        Route::post('printconvertcashboxToBank', 'printconvertcashboxToBank');
        Route::get('reciept_decoument', 'reciept_decoument');
        Route::get('Transfer_cash_to_next_day', 'Transfer_cash_to_next_day');
        Route::post('/Transfercashto_the_next_day', 'Transfercashto_the_next_day');
        Route::post('/updatedecoumentcashNextDay', 'updatedecoumentcashNextDay');
        Route::post('/print_reciept', 'print_voucher');
        Route::post('/print_reciept_ducoument', 'print_reciept_ducoument');
        Route::post('/print_expansedecoument', 'print_expansedecoument');
        Route::get('/generate_pdf_reciept_ducoument/{id}', 'generate_pdf');
    });

    // 6. السحوبات النقدية من البنك (Cash Withdrawal From Bank)
    Route::controller(CashWithdrawalFromTheBankController::class)->group(function () {
        Route::post('Cash_withdrawal_from_the_bank', 'Cash_withdrawal_from_the_bank');
        Route::post('printwithdrawal_from_the_bank', 'printwithdrawal_from_the_bank');
    });

    // 7. مجموعة السندات والقيود اليومية والافتتاحية (Credit Transactions)
    Route::controller(CredittransactionsController::class)->group(function () {
        Route::get('get_And_Delete_delyrecord/{id}', 'get_And_Delete_delyrecord');
        Route::get('Opening_entry', 'Opening_entry');
        Route::get('search_by_decoumentNo_send_abd/{id}', 'search_by_decoumentNo_send_abd');
        Route::get('search_by_decoumentNo_send_serf/{id}', 'search_by_decoumentNo_send_serf');
        Route::post('create_daily_record', 'daily_record');
        Route::post('updatedelyrecord', 'updatedelyrecord');
        Route::post('create_Opening_entry', 'create_Opening_entry');
        Route::get('Daily_record', 'index');
        Route::get('save_Daily_record/{id}', 'save_Daily_record');
        Route::get('save_Opening_entry/{id}', 'save_Opening_entry');
        Route::get('getAndUpdatevoncher/{id}', 'getAndUpdatevoncher');
        Route::get('delete_voncher/{id}', 'delete_voncher');
        Route::get('getAndUpdate_reciptdecument/{id}', 'getAndUpdate_reciptdecument');
        Route::get('getAndUpdate_delyrecord/{id}', 'getAndUpdate_delyrecord');
        Route::get('delete_record_by_id/{id}', 'delete_record_by_id');
        Route::post('Credittransactions', 'create');
        Route::post('updateVoncher', 'updateVoncher');
        Route::post('reciepttransactions', 'store');
        Route::post('updaterecieptdecoument', 'updaterecieptdecoument');
        Route::post('print_daily_record', 'print_daily_record');
        Route::post('print_Opening_entry', 'print_Opening_entry');

        // المسارات السابقة للـ الكنترولر
        Route::post('/vocher.store', 'store_kabt_Decument')->name('vocher.store');
        Route::delete('/voucher/delete-full/{sent_abd_count}', 'destroy_full_voucher');
        Route::get('voucher/edit/{sent_abd_count}', 'edit_full_voucher');
        Route::get('general_budget', 'general_budget');
        Route::post('/general_budget_search',  'budgetsheet')->name('budgetsheet');
        Route::get('getDetailsJS_Kabd/{serf_count}', 'getDetailsJS_Kabd');
        Route::post('/voucher-update-all', 'updateAllVoucher')->name('voucher.update.all');
        Route::get('get-receipt-details/{serf_count}', 'getReceiptDetails');
        Route::post('/receipt-update/{id}', 'update_Serf_Decument')->name('receipt.update');
        Route::delete('/receipt-delete/{id}', 'destroy_Serf_Decument')->name('receipt.delete');
        Route::get('/get-entry-details/{id}', 'getEntryDetails');
        Route::delete('/opening-entry/delete/{id}', 'delete_Opening_entry')->name('opening_entry.delete');
        Route::post('/opening-entry/store', 'create_Opening_entry_new')->name('opening_entry.store');
        Route::post('/save_opening_entry', 'store')->name('save_opening_entry');
        Route::post('/receipt-store', 'store_Serf_Decument')->name('receipt.store');
        Route::get('/get_latest_journals', 'getLatestJournals');
        Route::get('/get_journal_details/{id}', 'get_details');
        Route::get('/journal_delete/{id}', 'journal_delete')->name('journal.delete');
        Route::post('/journal/store', 'daily_record_new')->name('journal.store');
    });

    // 8. المصاريف والأعباء (Expenses)
    Route::controller(ExpensesController::class)->group(function () {
        Route::get('getAndUpdateExpenses/{id}', 'getAndUpdateExpenses');
        Route::post('Expenses', 'store');
        Route::post('updateExpenses', 'updateExpenses');
        Route::post('ExpensesOwner', 'ExpensesOwner');
    });

    // 9. مجموعة مسارات الفواتير والمبيعات (Invoices)
    Route::controller(InvoicesController::class)->group(function () {
        Route::get('/generate_pdf_customer_list', 'generate_pdf_customer_list');
        Route::get('/generate_return_sale_pdf/{id}', 'generate_return_sale_pdf');
        Route::post('AddInvoices', 'store');
        Route::post('Receipt', 'Receipt');
        Route::post('EditInvoices', 'edit');
        Route::post('updateproductallDataInvoices', 'updateproductallDataInvoices');
        Route::post('editRecipt', 'editRecipt');
        Route::post('returnAll', 'returnAll');
        Route::post('increaseProduct', 'increaseProduct');
        Route::get('/makeTotalDiscont/{idInvoice}/{discountvalue}', 'makeTotalDiscont');
        Route::get('/makenoteoninvoice/{idInvoice}/{notecontent}', 'makenoteoninvoice');
        Route::get('/confirmpaymentconfirmpayment/{inviceId}/{cashamount}/{bankamount}/{creaditamount}/{Bank_transfer}/{payment}/{customerId}/{numbershowstatus}/{date}/{anotherbank}/{p_o}', 'confirmpaymentconfirmpayment');
        Route::get('/updatepaymentconfirmpayment/{inviceId}/{cashamount}/{bankamount}/{creaditamount}/{Bank_transfer}/{payment}/{another_bank}', 'updatepaymentconfirmpayment');
        Route::get('/updatepaymentconfirmpaymentReciept/{inviceId}/{cashamount}/{bankamount}/{creaditamount}/{Bank_transfer}/{payment}', 'updatepaymentconfirmpaymentReciept');
        Route::get('/cancelInvoiceDiscont/{idInvoice}', 'cancelInvoiceDiscont');
        Route::get('/getproductbyCode/{code}', 'getproductbyCode');
        Route::post('/getByCode', 'getByCode');
        Route::get('showInvoiceRecent__pending/{id}', 'showInvoiceRecent__pending');
        Route::get('printInvoice/{id}', 'printInvoice');
        Route::get('saveInvoice/{id}', 'saveInvoice');
        Route::get('printreturnInvoice/{id}', 'printreturnInvoice');
        Route::get('returnsalesprinter/{id}', 'returnsalesprinter');
        Route::get('showInvoice/{id}', 'showInvoice');
        Route::get('showInvoiceRecent/{id}', 'showInvoiceRecent');
        Route::get('showRecieptRecent/{id}', 'showRecieptRecent');
        Route::get('getlastprice/{productId}/{customerId}', 'getlastprice');
        Route::get('getlastprice_offer_price/{productId}/{customerId}', 'getlastprice_offer_price');
        Route::post('printInvoice', 'printInvoice');
        Route::post('updateReciept', 'updateReciept');
        Route::post('return_sale', 'return_sale');
        Route::get('return_sale', 'index');
        Route::post('update_return_sale', 'update_return_Sale');
        Route::post('printReceiptToStorehouse', 'printReceiptToStorehouse');
        Route::get('changePaymethodIninvoice/{id}/{paymendMethod}', 'changePaymethodIninvoice');
        Route::get('changechustomer/{id}/{paymendMethod}', 'changechustomerInInvoice');
        Route::post('updatecustomerDataInvoice', 'updatecustomerDataInvoice');
        Route::post('updatecustomerDataRecipt', 'updatecustomerDataRecipt');
        Route::post('reciptprinter', 'reciptprinter');
        Route::get('/dashboard', 'dashboard')->name('dashboard');

        // المسارات السابقة للـ الكنترولر
        Route::post('save_delivery_sale', 'save_delivery_sale');
        Route::post('return_sale_delivery', 'return_sale_delivery');
        Route::get('/delivery_product_to_customer', 'delivery_product_to_customer');
        Route::get('/confirmpaymentconfirmpaymentdelivery_to_customer_withoud_tax_invoices/{inviceId}/{cashamount}/{bankamount}/{creaditamount}/{Bank_transfer}/{payment}/{customerId}/{numbershowstatus}/{date}', 'confirmpaymentconfirmpaymentdelivery_to_customer_withoud_tax_invoices');
        Route::get('showInvoiceRecentdelivery/{id}', 'showInvoiceRecentdelivery');
        Route::post('print_Invoice_withod_tax', 'print_Invoice_withod_tax');
        Route::post('update_return_sale_delivery', 'update_return_sale_delivery');
        Route::get('delivery_printreturnInvoice/{id}', 'delivery_print_return_Invoice_return');
        Route::get('/previousSales_not_sended_Invoices_all_branchs', 'previousSales_not_sended_Invoices_all_branchs');
        Route::get('/getAllinvicesajax_send_zatca_not_all_beanchs', 'getAllinvicesajax_send_zatca_not_all_beanchs');
        Route::get('previousSales_not_sended_Invoices', 'previousSales_not_sended_Invoices');
        Route::get('previousSales_sended_Invoices', 'previousSales_sended_Invoices');
        Route::get('getAllinvicesajax_send_zatca', 'getAllinvicesajax_send_zatca');
        Route::get('getAllinvicesajax_send_zatca_not', 'getAllinvicesajax_send_zatca_not');
        Route::get('dwonloadxml/{id}', 'dwonloadxml');
        Route::get('sent_to_zatca_return_items/{id}', 'sent_to_zatca_return_items');
        Route::get('sent_to_zatca/{id}', 'sent_to_zatca');
        Route::get('sendzatca_fromsale/{id}', 'sendzatca_fromsale');
        Route::get('/save_update_DateInvoice/{id}/{date}', 'save_update_DateInvoice');
        Route::post('save_invoice_sale', 'save_invoice_sale');
        Route::get('/delete_product/{id}', 'delete_product');
        Route::get('/generate_pdf/{id}', 'generate_pdf');
        Route::get('/pending_invoice/{id}', 'pending_invoice');
        Route::get('/sales_pending/{id}', 'sales_pending');
        Route::get('/update_pending_invoice/{id}', 'update_pending_invoice');
        Route::get('/update_sales_pending/{id}', 'update_sales_pending');
        Route::get('/get_invoice_peeding/{id}', 'get_invoice_peeding');
        Route::get('/pending_invoice_previes', 'pending_invoice_previes');
        Route::get('/geta_jax_Recent_Invoices_pending', 'geta_jax_Recent_Invoices_pending');
        Route::get('/PreviousQuotes', 'PreviousQuotes');
        Route::get('/searchpreviousquotes/{id}', 'searchPreviousQuotes');
        Route::get('/getquotebycustomer/{id}', 'getquotebycustomer');
        Route::get('/updateinvoicebyid/{id}', 'updateinvoicebyid');
        Route::get('/updateinvoicebyidforsaleupdate/{id}', 'updateinvoicebyidforsaleupdate');
        Route::get('/updatepaymentconfirmpaymentpurchases/{inviceId}/{cashamount}/{bankamount}/{creaditamount}/{Bank_transfer}/{payment}', 'updatepaymentconfirmpaymentpurchases');
        Route::get('/get_all_customer', 'get_all_customer');
        Route::get('/update_customer_name/{id}/{name}', 'update_customer_name');
        Route::get('/updatepaymentconfirmpayment_in_quotation/{inviceId}/{cashamount}/{bankamount}/{creaditamount}/{Bank_transfer}/{payment}', 'updatepaymentconfirmpayment_in_quotation');
    });

    // 10. طلبات وعمليات الموردين (Suppliers Management)
    Route::controller(SupllierController::class)->group(function () {
        Route::get('Purchase_order_of_resources', 'index');
        Route::get('getsupllier/{id}', 'show');
        Route::get('purchasesShow/{id}', 'purchasesShow');
        Route::post('printProductToSupllier', 'edit');
        Route::post('printProductToSupllierOrder', 'printProductToSupllierOrder');
        Route::get('printProductToSupllierOrder_pdf/{id}', 'printProductToSupllierOrder_pdf');
        Route::get('printProductToSupllier/{id}', 'prindorderToSupplier');
    });

    // 11. لوحة تحكم الإدارة والمستخدمين (Admin Controller / Users Management)
    Route::controller(AdminController::class)->group(function () {
        Route::get('getallusers', 'show');
        Route::get('updateuser/{id}', 'edit');
        Route::get('deleteuser/{id}', 'destroy');
    });

    // 12. إدارة العملاء الأساسية (Customers Management)
    Route::get('/getcustomer/{id}', [CustomersController::class, 'show']);

    // 13. مجموعة التقارير الشاملة (Reports)
    Route::controller(ReportController::class)->group(function () {
        Route::get('/account_statement', 'account_statement');
        Route::get('/our_backup_database', 'serverDBBackup');
        Route::get('/Daily_record_report', 'Daily_record_report');
        Route::get('/product_sales_purchases', 'product_sales_purchases');
        Route::post('/account_statement', 'search_account_statement');
        Route::post('/product_sales_purchases', 'search_product_sales_purchases');
        Route::post('/search_Daily_record_report', 'search_Daily_record_report');
        Route::get('/Bank_Statement', 'Bank_Statement');
        Route::post('/bankDecument', 'searchbankDecument');
        Route::get('/ConvertBoxtobankReport', 'ConvertBoxtobankReport');
        Route::post('/ConvertBoxtobankReport', 'searchConvertBoxtobankReport');
        Route::get('/transactionsToMasterBranch', 'transactionsToMasterBranch');
        Route::post('/searchtransactionsToMasterBranch', 'searchtransactionsToMasterBranch');
        Route::get('/Bank_Transfer', 'Bank_Transfer');
        Route::post('/Bank_Transfer', 'search_Bank_Transfer');
        Route::get('/print_Bank_Transfer/{branch}/{start}/{end}', 'print_Bank_Transfer');
        Route::get('/print_products_Transfer/{branchfrom}/{branchto}/{start}/{end}', 'print_products_Transfer');
        Route::get('/Transfer_products', 'products_Transfer');
        Route::post('/search_Transfer_products', 'search_products_Transfer');
        Route::get('/print_Transfer_products/{invoiceId}', 'print_Transfer_products');
        Route::get('/print_sales_and_purchases/{invoiceId}/{start}/{end}', 'print_sales_and_purchases');
        Route::post('/stockquantity', 'search_stockquantity');
        Route::get('/search_stockquantityPagination/{searchtext}/{branchId}', 'search_stockquantityPagination');
        Route::get('/stockquantityPagination/{branchId}/{chooseOperation}/{quantity}', 'stockquantityPagination');
        Route::get('/updatestockquentity', 'updatestockquentity');
        Route::post('/updatestockquentity', 'search_updatestockquentity');
        Route::get('/generate_customer_statment_pdf/{customerId}/{start}/{end}', 'generate_customer_statment_pdf');
        Route::get('/employeeـsales', 'employeeـsales');
        Route::get('/showallBranchs', 'showallBranchs');
        Route::get('/wherehouse', 'wherehouse');
        Route::get('/salesـprofits', 'salesـprofits');
        Route::post('/salesReport', 'salesReportsearch');
        Route::post('/salesـprofits', 'sales_profitssearch');
        Route::get('/salesReport', 'salesReport');
        Route::get('/Show_return_Sales_Details/{invoiceId}', 'Show_return_Sales_Details');
        Route::get('/printInvoicesReportdetails/{branch}/{paymethod}/{startat}/{endat}/{customer_id}', 'printReportsaleswithoud_deatails');
        Route::post('/search_Requestـoffersـfromـsuppliers', 'search_Requestـoffersـfromـsuppliers');
        Route::post('/employeeSalesSearch', 'employeeSalesSearch');
        Route::get('/printInvoicesReport/{branch}/{paymethod}/{startat}/{endat}/{customer_id}', 'printInvoicesReport');
        Route::get('/Invoices_export/{branch}/{paymethod}/{startat}/{endat}', 'printInvoicesReport_export');
        Route::get('/Invoices_purchases_export/{branch}/{paymethod}/{SUPPLIER}/{startat}/{endat}', 'Invoices_purchases_export');
        Route::get('/printInvoicesReport_export/{branch}/{paymethod}/{startat}/{endat}', 'printInvoicesReport_export');
        Route::get('/printInvoicesAllItemsWithReturned/{id}', 'printInvoicesAllItemsWithReturned');
        Route::get('/report_returns_sale', 'report_returns_sale');
        Route::post('/search_report_returns_sale', 'search_report_returns_sale');
        Route::get('/printreturnInvoicesReport/{branch}/{startat}/{endat}', 'print_return_Report');
        Route::get('/printReportProductSales/{branch}/{productId}/{startat}/{endat}', 'printReportProductSales');
        Route::get('/printReportemployeeSales/{userId}/{startat}/{endat}', 'printReportemployeeSales');
        Route::get('/printReportProfitSales/{branch_id}/{userId}/{startat}/{endat}', 'printReportProfitSales');
        Route::get('/print_report_order_from_supplier/{SupplierId}/{startat}/{endat}', 'print_report_order_from_supplier');
        Route::get('/printReportoffer_price_customer/{SupplierId}/{startat}/{endat}', 'printReportoffer_price_customer');
        Route::get('/Requestـoffersـfromـsuppliers', 'Requestـoffersـfromـsuppliers');
        Route::get('/product_sales', 'product_sales');
        Route::post('/product_sales', 'search_product_sales');
        Route::get('/report_offer_price_customer', 'report_offer_price_customer');
        Route::post('/show_offer_price_customer', 'show_offer_price_customer');
        Route::get('/Delivery_notes', 'Delivery_notes');
        Route::post('/Delivery_notes', 'search_Delivery_notes');
        Route::get('/printDelivery_notes/{orderId}/{startat}/{endat}', 'printDelivery_notes');
        Route::get('/Requestـaـquoteـfromـtheـsupplier', 'Requestـaـquoteـfromـtheـsupplier');
        Route::post('/Requestـaـquoteـfromـtheـsupplier', 'search_Requestـaـquoteـfromـtheـsupplier');
        Route::get('/print_Requestـaـquoteـfromـtheـsupplier/{branchId}/{supplier}/{startat}/{endat}', 'print_Requestـaـquoteـfromـtheـsupplier');
        Route::get('/Purchasesـfromـsuppliers', 'Purchasesـfromـsuppliers');
        Route::post('/Purchasesـfromـsuppliers', 'search_Purchases_from_suppliers');
        Route::get('/print_Purchasesـfromـsuppliers/{branch}/{pay}/{supplierId}/{startat}/{endat}', 'print_Purchasesـfromـsuppliers');
        Route::get('Refundـofـresourceـpurchases', 'Refundـofـresourceـpurchases');
        Route::post('/Refundـofـresourceـpurchases', 'search_Refundـofـresourceـpurchases');
        Route::get('/print_Refundـofـresourceـpurchases/{branch_id}/{startat}/{endat}', 'print_Refundـofـresourceـpurchases');
        Route::get('/purchasereports', 'purchasereports');
        Route::post('/purchasereports', 'search_purchasereports');
        Route::get('/print_purchasereports/{productId}/{startat}/{endat}', 'print_purchasereports');
        Route::get('/customerـpurchases', 'customerـpurchases');
        Route::get('/purchasproducttocustomer', 'purchasproducttocustomer');
        Route::post('/customerـpurchases', 'search_customerـpurchases');
        Route::post('/purchasproducttocustomer', 'searchpurchasproducttocustomer');
        Route::get('/print_customerـpurchases/{branchId}/{customerId}/{startat}/{endat}', 'print_customerـpurchases');
        Route::get('/credit_collection', 'credit_collection');
        Route::post('/credit_collection', 'search_credit_collection');
        Route::get('/print_credit_collection/{userId}/{startat}/{endat}', 'print_credit_collection');
        Route::get('/supplierlist', 'supplierList');
        Route::get('/Customerlist', 'Customerlist');
        Route::get('/print_supplierlist/{userId}/{startat}/{endat}', 'print_supplierlist');
        Route::get('/print_customeList', 'print_customeList');
        Route::get('/Stocktaking/{id}', 'Stocktaking');
        Route::get('/customerslist_export', 'customerslist_export');
        Route::get('/supplierlist_export', 'supplierlist_export');
        Route::get('/Stocktakingpdf', 'Stocktakingpdf');
        Route::get('/print_SupplierList', 'print_supplierList');
        Route::get('/Supplier_account_statement', 'Supplier_account_statement');
        Route::post('/Supplier_account_statement', 'search_Supplier_account_statement');
        Route::get('/Customer_account_statement', 'Customer_account_statement');
        Route::post('/Customer_account_statement', 'search_Customer_account_statement');
        Route::get('/TransFerCashTothenNextDay', 'TransFerCashTothenNextDay');
        Route::post('/TransFerCashTothenNextDay', 'search_TransFerCashTothenNextDay');
        Route::get('/Supplier_credit_payment', 'Supplier_credit_payment');
        Route::post('/Supplier_credit_payment', 'search_Supplier_credit_payment');
        Route::get('/print_Supplier_credit_payment/{supplierId}/{startat}/{endat}', 'print_Supplier_credit_payment');
        Route::get('/shift_detailes', 'shift_detailes');
        Route::post('/shift_detailes', 'search_shift_detailes');
        Route::get('/print_shift_detailes/{branch_id}/{paumethod}/{startat}/{endat}', 'print_shift_detailes');
        Route::get('/Expensesreport', 'Expenses');
        Route::post('/Expensesreport', 'search_Expenses');
        Route::get('/printExpensesReport/{branch_id}/{reson}/{startat}/{endat}', 'printExpensesReportlast');
        Route::get('/financial_accounts_Export', 'financial_accounts_Export');
        Route::get('/financial_accounts_Export_CSV', 'financial_accounts_Export_CSV');
        Route::get('/stockquantity', 'stockquantity');
        Route::get('/printstockquantity/{branch_id}/{display}/{quantity}/{loction}', 'printstockquantity');
        Route::get('/Best_selling_products', 'Best_selling_products');
        Route::post('/Best_selling_products', 'search_Best_selling_products');
        Route::get('/printBest_selling_products/{branch_id}/{startat}/{endat}', 'printBest_selling_products');
        Route::get('/VAT', 'VAT');
        Route::post('/VAT', 'search_VAT');
        Route::get('/print_VAT/{branch_id}/{startat}/{endat}', 'print_VAT');
        Route::get('/Customersـexceededـgraceـperiod', 'Customersـexceededـgraceـperiod');
        Route::get('/budgetsheet', 'budgetsheet');
        Route::post('/budgetsheet', 'search_budgetsheet');
        Route::get('/reports/budget/children', 'getAccountChildren')->name('reports.budget.children');
        // المسارات السابقة للـ الكنترولر
        Route::post('/export_sales_excel', 'exportExcel')->name('export.sales.excel');
        Route::post('/printInvoicesReport_export_post', 'printInvoicesReport_export_post')->name('invoices.export.excel');
        Route::get('/profit-loss-report', 'profitLossReport')->name('report.profit_loss');
        Route::post('/profit-loss-report-search', 'searchProfitLossReport')->name('report.profit_loss.search');
        Route::get('/delivery-profit-loss-report', 'deliveryprofitLossReport')->name('report.delivery_profit_loss');
        Route::post('/delivery-profit-loss-report-search', 'deliverysearchProfitLossReport')->name('report.delivery_profit_loss.search');
        Route::get('/profit_lose_export/{start}/{end}/{branch}', 'Profit_loss_export');
        Route::get('/profit_and_lost', 'profit_and_lost');
        Route::post('/profit_and_lost', 'search_profit_and_lost');
        Route::get('/sel_product_DELIVERY', 'sel_product_DELIVERY');
        Route::post('/salesReport_delivery', 'salesReport_delivery');
        Route::get('/report_returns_sale_delivery', 'report_delivery_return');
        Route::post('/report_returns_sale_delivery', 'search_report_returns_sale_delivery');
        Route::post('/account_statement_model', 'search_account_statement_modal');
        Route::get('/sales_product_by_date', 'sales_product_by_date');
        Route::post('/sales_product_by_date', 'search_sales_product_by_date');
        Route::get('/purchase_product_by_date', 'purchase_product_by_date');
        Route::post('/purchase_product_by_date', 'search_purchase_product_by_date');
        Route::get('/SalesPurchaseInPeriode', 'SalesPurchaseInPeriode');
        Route::post('/SalesPurchaseInPeriode', 'search_SalesPurchaseInPeriode');
        Route::get('/low_sell', 'low_sell');
        Route::post('/low_sell', 'low_sell_search');
        Route::get('/cost_center', 'cost_center');
        Route::post('/cost_center_search', 'cost_center_search');
        Route::get('/sales_and_return', 'sales_and_return');
        Route::post('/sales_and_return', 'search_sales_and_return');
        Route::get('/Customer_debt_restructuring', 'Customer_debt_restructuring');
        Route::post('/Customer_debt_restructuring', 'search_Customer_debt_restructuring');
        Route::get('/Supplier_debt_restructuring', 'Supplier_debt_restructuring');
        Route::post('/Supplier_debt_restructuring', 'search_Supplier_debt_restructuring');
        Route::get('/budgetsheet_general', 'budgetsheet_general');
        Route::get('/year_sales_report', 'year_sales_report');
    });

    // 14. العمليات المساعدة وإدخال البيانات (Supplementary Processes)
    Route::controller(SupprocessesController::class)->group(function () {
        Route::get('/show_groups', 'show_groups');
        Route::get('/addnewProduct', 'index');
        Route::post('/addnewProduct', 'create_addnewProduct');
        Route::post('/create_products_group', 'create_products_group');
        Route::post('/addnewProductajax', 'addnewProductajax');
        Route::post('/createnewcustomerajax', 'createnewcustomerajax'); // تم تصحيح الميثود للمطابقة مع الكنترولر الفعلي
        Route::get('/addnewcustomer', 'addnewcustomer');
        Route::post('/addnewcustomer', 'create_addnewcustomer');
        Route::get('/updatecustomer', 'Goupdatecustomer');
        Route::post('/updatecustomer', 'updatecustomer');
        Route::get('getcustomer/{id}', 'getcustomerdata');
        Route::get('/addnewsupplier', 'addnewsupplier');
        Route::post('/addnewsupplier', 'create_addnewsupplier');
        Route::post('/create_addnewsupplierajax', 'create_addnewsupplierajax');
        Route::get('/updatesupplier', 'Goupdatesupplier');
        Route::post('/updatesupplier', 'updatesupplier');
        Route::get('getsupplier/{id}', 'getsupplierdata');
        Route::get('/expenses_reason', 'expenses_reason');
        Route::post('/expenses_reason', 'create_expenses_reason');
        Route::get('/stockAdjastment', 'stockAdjastment');
        Route::post('/stockAdjastment', 'stock_update');
        Route::get('/product_movement', 'product_movement');
        Route::get('/product_damage', 'product_damage');
        Route::post('/product_damage_add', 'product_damage_add');
        Route::post('/product_movement', 'update_product_movement');
        Route::get('/createnewcustomers', 'createnewcustomers');
    });

    // 15. إدارة وإضافة الفروع (Branches)
    Route::controller(BranchsController::class)->group(function () {
        Route::get('/addbranch', 'index');
        Route::post('/addbranch', 'create');
        Route::get('/get-child-branches', 'getChildBranches')->name('get.child.branches');
        Route::post('/addwherehouse', 'addwherehouse');
        Route::get('/showbranches', 'show');
        Route::post('/updatebranch', 'updatebranch');
    });

    // 16. ضريبة القيمة المضافة (VAT - Avt Controller)
    Route::controller(AvtController::class)->group(function () {
        Route::get('/avt', 'index');
        Route::post('/update_vat', 'update');
        Route::post('/New_avt', 'store');
        Route::post('/destory_avt', 'destroy');
    });

    // 17. الموارد البشرية وشؤون الموظفين (HR & Employee Management)
    Route::controller(EmployeeController::class)->group(function () {
        Route::get('/createNewEmployee', 'index');
        Route::post('/createNewEmployee', 'create');
        Route::get('/allEmployees', 'show');
        Route::get('/addnewDepartment', 'addnewDepartment');
        Route::post('/addnewDepartment', 'store');
        Route::get('/updateEmployee/{id}', 'updateEmployee');
        Route::post('/updateEmployee', 'update');
        Route::get('/Increaseـor_deduction', 'Increaseـor_deduction');
        Route::post('/Increaseـor_deduction', 'Increaseـor_deduction_add');
        Route::get('/salarydecoument', 'salarydecoument');
        Route::post('/print_decument_salary', 'print_decument_salary');
    });

    // 18. شجرة وإعدادات الحسابات المالية (Financial Accounts)
    Route::controller(FinancialAccountsController::class)->group(function () {
        Route::post('/update_account_details', 'updateDetails');
        Route::post('/update_account_status', 'updateStatus');
        Route::post('/delete_account', 'destroyOrder');
        Route::get('/financial_accounts', 'index');
        Route::get('/tree', 'tree');
        Route::get('/create_acount', 'create_new_acount');
        Route::get('/getAllaccountsajax', 'ajax_choose_account');
        Route::get('/update_acount/{id}', 'update_acount');
        Route::post('/add_new_acount_finance', 'add_new_acount_finance');
        Route::get('/getfinancialaccount/{id}', 'getfinancialaccount');
        Route::get('/searchaboutaccountByname_numberfunction', 'searchaboutaccountByname_numberfunction');
        Route::get('/searchaboutaccountBytype_function/{text}', 'searchaboutaccountBytype_function');
        Route::get('/searchMaster_account_function/{text}', 'searchMaster_account_function');
    });

    // 19. مسارات خلطات المنتجات (Products Mix)
    Route::controller(ProductsMixController::class)->group(function () {
        Route::get('/getmixproduct/{code}', 'getmixproduct');
        Route::post('/Addmixproduct', 'store');
        Route::post('/updateproduct_mix_Increase', 'updateproduct_mix_Increase');
        Route::post('/updateproduct_mix_decrease', 'updateproduct_mix_decrease');
    });

    // 20. نوع الحسابات (Account Types)
    Route::get('/account_type', [AcountsTypeController::class, 'index']);

    // 21. توصيل وتوريد الموردين والمستندات (Delivery & Suppliers)
    Route::controller(DliveryController::class)->group(function () {
        Route::get('recent_delivers', 'previousdelivers');
        Route::get('getAlldeliversajax', 'getAlldeliversajax');
        Route::get('getAlldeliversajaxbycustomer/{id}', 'getAlldeliversajaxbycustomer');
        Route::get('/deliver_to_anoter_supplier', 'index');
        Route::get('/confirmdelivery', 'confirmdelivery');
        Route::post('/Addproduct_to_dlivery_supplier', 'store');
        Route::post('/updateproductallDatadelivery', 'updateproductallDatadelivery');
        Route::post('/print_delivery_to_anoter_supplier', 'print_delivery_to_anoter_supplier');
        Route::post('/print_delivery_invoice', 'print_delivery_invoice');
        Route::get('/deleteitemdelivery/{id}', 'deleteitemdelivery');
        Route::get('/getcustomerproductsdelivery/{id}', 'getcustomerproductsdelivery');
        Route::get('/getitems/{id}', 'getitems');
        Route::get('/deleteitemdeliveryconfirm/{id}', 'deleteitemdeliveryconfirm');
    });

    // 22. إعدادات النظام (System Settings)
    Route::controller(SystemSettingController::class)->group(function () {
        Route::get('/systemSetting', 'index');
        Route::get('/onbourding', 'onbourding');
        Route::post('/onbourding', 'store');
        Route::post('/updateCamData', 'update');
    });

    // 23. القروض (Loans)
    Route::controller(LoansController::class)->group(function () {
        Route::get('/Loans', 'index');
        Route::delete('/delete_Loans/{id}', 'destroy');
        Route::post('/Loans', 'store');
        Route::post('/update_Loans', 'edit');
    });

    // 24. تأكيد تسليم المنتجات للعملاء
    Route::controller(DeliveryProductToTheCustomerController::class)->group(function () {
        Route::get('confirm_delivery', 'index');
        Route::get('confirm_sales', 'confirm_sales');
        Route::post('search_confirm_delievery', 'store');
        Route::post('confirm_delivery_all', 'edit');
        Route::post('search_confirm_sales', 'search_confirm_sales');
        Route::post('confirm_sales_delivery_all', 'confirm_sales_delivery_all');
    });

    // 25. تالف المنتجات (Products Damage)
    Route::controller(ProductsDamageController::class)->group(function () {
        Route::get('ProductsDamageReport', 'index');
        Route::post('ProductsDamageReport', 'show');
    });

    // 26. حركة المنتجات بين الفروع (Product Movement)
    Route::controller(ProductMovementAnotherBranchController::class)->group(function () {
        Route::get('sendProduct', 'index');
        Route::get('my-drafts-movements',  'showMyDrafts');
        Route::get('reciveProduct', 'show');
        Route::get('deleteproduct/{id}', 'destroy');
        Route::get('findinvoiceMovmevt/{id}', 'findinvoiceMovmevt');
        Route::post('create_sendProduct', 'create');
        Route::post('reciveNewF', 'reciveNewF');
        Route::get('deleteproductrecive/{id}', 'deleteproduct');
        Route::post('create_reciveProduct', 'store');
        Route::post('print_Transfer_items', 'print_Transfer_items');
        Route::post('print_Recive_items', 'print_Recive_items');
        Route::get('reports/sent-movements', 'sentReport')->name('reports.sent');
        Route::get('reports/received-movements', 'receivedReport')->name('reports.received');
        Route::get('reports/product-movements-summary', 'productSummaryReport')->name('reports.summary');
        Route::get('reports/sent/excel',  'exportExcel')->name('reports.sent.excel');
        Route::get('reports/sent/pdf',  'exportPdf')->name('reports.sent.pdf');
        Route::get('reports/recive/excel',  'exportReceivedExcel')->name('reports.recive.excel');
        Route::get('reports/recive/pdf',  'exportReceivedPdf')->name('reports.recive.pdf');
    });

        Route::controller(UserController::class)->group(function () {
               Route::get('get-branch-employees/{branch_id}', 'getEmployeesByBranch');

    });


    // ملفات الشخصية التابعة للنظام المتعدد اللغات
    Route::get('/profile', function () {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        return view('index');
    })->name('profile');
});

