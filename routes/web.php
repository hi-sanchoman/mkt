<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ContactsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImagesController;
use App\Http\Controllers\OrganizationsController;
use App\Http\Controllers\ConversionsController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\FlatsController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\MeetingsController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SuppliersController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\RealizationController;
use App\Http\Controllers\RealizatorsController;
use App\Http\Controllers\ProfitController;
use App\Http\Controllers\WorkersController;
use Inertia\Inertia;


use App\Http\Controllers\MailController;

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

//Sklad

Route::get('store/sklad',[StoreController::class,'getSklad'])
    ->name('store.sklad')
    ->middleware('auth');

Route::get('store/freezer',[StoreController::class,'getFreezer'])
    ->name('store.freezer')
    ->middleware('auth');

Route::get('store/weight',[StoreController::class,'getWeight'])
    ->name('store.weight')
    ->middleware('auth');

Route::get('store/tara',[StoreController::class,'getTara'])
    ->name('store.tara')
    ->middleware('auth');

Route::get('sklad',[StoreController::class,'Swap'])
    ->name('sklad')
    ->middleware('auth');

Route::get('sklad/weight',[StoreController::class,'SwapWeight'])
    ->name('sklad.weight')
    ->middleware('auth');

Route::get('sklad/freezer',[StoreController::class,'SwapFreezer'])
    ->name('sklad.freezer')
    ->middleware('auth');

//Зарплата\Расходы\Долги

Route::get('get-company-naks',[ProfitController::class,'getCompanyNaks'])
    ->name('get-company-naks')
    ->middleware('auth');

Route::post('get-salary-month',[ProfitController::class,'getSalaryMonth'])
    ->name('get-salary-month')
    ->middleware('auth');



Route::post('get-owes-month',[ProfitController::class,'getOwesMonth'])
    ->name('get-owes-month')
    ->middleware('auth');

Route::get('rashod/{type}',[ProfitController::class,'getRashod'])
    ->name('rashod')
    ->middleware('auth');


Route::post('add-ostatok',[ProfitController::class,'addOstatok'])
    ->name('add-ostatok')
    ->middleware('auth');

Route::get('realization_report/{id}',[ProfitController::class,'realizationReport'])
    ->name('realization-report')
    ->middleware('auth');

Route::get('report/{id}',[ProfitController::class,'Report'])
    ->name('report')
    ->middleware('auth');

Route::post('update-totalreport',[ProfitController::class,'updateTotalReport'])
    ->name('update-totalreport')
    ->middleware('auth');

Route::post('save-total-report',[ProfitController::class,'saveTotalReport'])
    ->name('save-total-report')
    ->middleware('auth');

Route::get('get-workers',[ProfitController::class,'getWorkers'])
    ->name('get-workers')
    ->middleware('auth');

Route::post('dolg-start',[ProfitController::class,'dolgStart'])
    ->name('dolg-start')
    ->middleware('auth');

Route::post('pay-owe',[ProfitController::class,'payOwe'])
    ->name('pay-owe')
    ->middleware('auth');

Route::get('dolgi',[ProfitController::class,'dolgi'])
    ->name('dolgi')
    ->middleware('auth');

Route::get('zarplata',[ProfitController::class,'zarplata'])
    ->name('zarplata')
    ->middleware('auth');

Route::get('uchet',[ProfitController::class,'getUchet'])
    ->name('uchet')
    ->middleware('auth');

Route::get('get-work-users',[ProfitController::class,'getWorkUsers'])
    ->name('get-work-users')
    ->middleware('auth');

Route::get('workers', [WorkersController::class, 'index'])
    ->name('workers')
    ->middleware('auth');

Route::post('workers', [WorkersController::class, 'store'])
    ->name('workers.store')
    ->middleware('auth');

Route::post('workers/delete', [WorkersController::class, 'destroy'])
    ->name('workers.delete')
    ->middleware('auth');

Route::get('profit',[ProfitController::class, 'index'])
    ->name('profit')
    ->middleware('auth');

Route::post('send-expense',[ProfitController::class, 'sendExpense'])
    ->name('send-expense')
    ->middleware('auth');

Route::post('add-worker',[ProfitController::class, 'addWorker'])
    ->name('add-worker')
    ->middleware('auth');

Route::post('send-income',[ProfitController::class, 'sendIncome'])
    ->name('send-income')
    ->middleware('auth');

Route::post('give-salary',[ProfitController::class,'giveSalary'])
    ->name('give-salary')
    ->middleware('auth');

Route::post('save-salary',[ProfitController::class,'saveSalary'])
    ->name('save-salary')
    ->middleware('auth');

Route::get('end-month',[ProfitController::class,'endMonth'])
    ->name('end-month')
    ->middleware('auth');



//Реализация
Route::post('get-order',[RealizationController::class,'getMyOrder'])
    ->name('get-order')
    ->middleware('auth');

Route::post('realization-order',[RealizationController::class,'update'])
    ->name('realization-order')
    ->middleware('auth');

Route::post('save-realization',[RealizationController::class,'saveRealization'])
    ->name('save-realization')
    ->middleware('auth');

Route::post('confirm-realization',[RealizationController::class,'confirmRealization'])
    ->name('confirm-realization')
    ->middleware('auth');

Route::get('realization',[RealizationController::class, 'sales'])
    ->name('realization')
    ->middleware('auth');

Route::post('get-realizator',[RealizationController::class,'getRealization'])
    ->name('get-realizator')
    ->middleware('auth');

Route::post('realizator-order',[RealizationController::class,'getRealizatorOrder'])
    ->name('realizator-order')
    ->middleware('auth');

Route::get('realization-status',[RealizationController::class,'changeStatus'])
    ->name('realization-status')
    ->middleware('auth');

Route::get('dop-status',[RealizationController::class,'dopStatus'])
    ->name('dop-status')
    ->middleware('auth');

Route::post('decline-dop',[RealizationController::class,'declineDop'])
    ->name('decline-dop')
    ->middleware('auth');

Route::post('accept-dop',[RealizationController::class,'acceptDop'])
    ->name('accept-dop')
    ->middleware('auth');

Route::post('set-order-amount',[RealizationController::class,'setOrderAmount'])
    ->name('set-order-amount')
    ->middleware('auth');

Route::post("set-order-returned",[RealizationController::class,'setOrderReturned'])
    ->name('set-order-returned')
    ->middleware('auth');

Route::post("set-order-defect",[RealizationController::class,'setOrderDefect'])
    ->name('set-order-defect')
    ->middleware('auth');
        
Route::post("set-order-defect-sum",[RealizationController::class,'setOrderDefectSum'])
    ->name('set-order-defect-sum')
    ->middleware('auth');

Route::post("set-order-sold",[RealizationController::class,'setOrderSold'])
    ->name('set-order-sold')
    ->middleware('auth');

Route::post('add-reserve',[RealizationController::class,'addReserve'])
    ->name('add-reserve')
    ->middleware('auth');

//Реализаторы

Route::get('blank/{id}',[RealizatorsController::class,'blank'])
    ->name('blank')
    ->middleware('auth');

Route::get('realizators', [RealizatorsController::class,'index'])
    ->name('realizators')
    ->middleware('auth');

Route::get('realizators/history/{id}',[RealizatorsController::class,'history'])
    ->name('realizators.history')
    ->middleware('auth');

Route::post('save-nak',[RealizatorsController::class,'saveNak'])
    ->name('save-nak')
    ->middleware('auth');

Route::get('nak-status',[RealizatorsController::class,'nakStatus'])
    ->name('nak-status')
    ->middleware('auth');

Route::post('nak-report-by-month',[RealizatorsController::class,'getNakReportByMonth'])
    ->name('nak-report-by-month')
    ->middleware('auth');

//Продажи

Route::get('sales/orders',[RealizationController::class,'getOrders'])
    ->name('sales/orders')
    ->middleware('auth');


Route::get('sales/realizators',[RealizationController::class,'getRealizators'])
    ->name('sales/realizators')
    ->middleware('auth');


Route::get('sales/report',[RealizationController::class,'getReport'])
    ->name('sales/report')
    ->middleware('auth');


Route::get('sales/sold',[RealizationController::class,'getSold'])
    ->name('sales/sold')
    ->middleware('auth');

Route::get('sales', [RealizationController::class, 'index'])
    ->name('sales')
    ->middleware('auth');

Route::post('sales/order',[RealizationController::class, 'order'])
    ->name('sales/order')
    ->middleware('auth');

Route::post('sales/sold1',[RealizationController::class, 'sold1'])
    ->name('sales/sold1')
    ->middleware('auth');

Route::post('sales/defects',[RealizationController::class, 'defects'])
    ->name('sales/defects')
    ->middleware('auth');

Route::post('sales/naks',[RealizationController::class, 'naks'])
    ->name('sales/naks')
    ->middleware('auth');

Route::post('pay-nak', [RealizationController::class, 'payNak'])
    ->name('pay-nak')
    ->middleware('auth');


//Заказы

Route::get('realizators/new-order',[RealizatorsController::class,'newOrder'])
    ->name('realizators.new-order')
    ->middleware('auth');

Route::get('realizators/add-order',[RealizatorsController::class,'addOrder'])
    ->name('realizators.add-order')
    ->middleware('auth');

Route::get('realizators/nakladnie',[RealizatorsController::class,'mobNakladnie'])
    ->name('realizators.nakladnie')
    ->middleware('auth');

Route::get('realizators/avans',[RealizatorsController::class,'mobAvans'])
    ->name('realizators.avans')
    ->middleware('auth');

Route::get('realizators/istoriya',[RealizatorsController::class,'mobHistory'])
    ->name('realizators.avans')
    ->middleware('auth');


Route::get('order/today_t',[RealizationController::class,'todayT'])
    ->name('order/today')
    ->middleware('auth');


Route::get('order/week_t',[RealizationController::class,'weekT'])
    ->name('order/week')
    ->middleware('auth');


Route::get('order/month_t',[RealizationController::class,'monthT'])
    ->name('order/month')
    ->middleware('auth');


Route::get('order/year_t',[RealizationController::class,'yearT'])
    ->name('order/year')
    ->middleware('auth');


Route::get('order/today',[RealizationController::class,'today'])
    ->name('order/today')
    ->middleware('auth');


Route::get('order/week',[RealizationController::class,'week'])
    ->name('order/week')
    ->middleware('auth');


Route::get('order/month',[RealizationController::class,'month'])
    ->name('order/month')
    ->middleware('auth');


Route::get('order/year',[RealizationController::class,'year'])
    ->name('order/year')
    ->middleware('auth');

Route::post('orders/send',[RealizationController::class,'sendOrder'])
    ->name('orders/send')
    ->middleware('auth');

Route::post('realizators/orders/send',[RealizationController::class,'sendOrder'])
    ->name('realizators/orders/send')
    ->middleware('auth');


Route::post('orders/update',[RealizationController::class,'updateOrder'])
    ->name('orders/update')
    ->middleware('auth');

Route::post('realizators/orders/update',[RealizationController::class,'updateOrder'])
    ->name('realizators/orders/update')
    ->middleware('auth');

Route::post('orders/get',[RealizationController::class,'getOrder'])
    ->name('orders/get')
    ->middleware('auth');

Route::post('order/date',[RealizationController::class,'getOrderByDate'])
    ->name('order/date')
    ->middleware('auth');


//Склад

Route::post('add-new-product',[StoreController::class,'addNewProduct'])
    ->name('add-new-product')
    ->middleware('auth');    

Route::post('set-freezer-amount',[StoreController::class,'setFreezerAmount'])
    ->name('set-freezer-amount')
    ->middleware('auth');

Route::post('set-freezer-price',[StoreController::class,'setFreezerPrice'])
    ->name('set-freezer-price')
    ->middleware('auth');

Route::post('add-tara',[StoreController::class,'addTara'])
    ->name('add-tara')
    ->middleware('auth');

Route::get('store',[StoreController::class, 'index'])
    ->name('store')
    ->middleware('auth');

Route::post('store/add',[StoreController::class, 'add'])
    ->name('store/add')
    ->middleware('auth');

Route::post('store/addprice',[StoreController::class, 'addPrice'])
    ->name('store/addprice')
    ->middleware('auth');

Route::post('store/addweight',[StoreController::class,'addWeight'])
    ->name('store/addweight')
    ->middleware('auth');

Route::post('store/addtaraamount',[StoreController::class, 'addTaraAmount'])
    ->name('store/addtaraamount')
    ->middleware('auth');

Route::post('store/addtarainside',[StoreController::class, 'addTaraInside'])
    ->name('store/addtarainside')
    ->middleware('auth');

Route::post('store/addtaraprice',[StoreController::class, 'addTaraPrice'])
    ->name('store/addtaraprice')
    ->middleware('auth');

//Выработка
/*
Route::post()
    ->name('conversions/change',[ConversionsController::class, 'changeMonth'])
    ->middleware('auth');*/

//Priem

Route::post('supplies', [DashboardController::class, 'store'])
    ->name('supply.store')
    ->middleware('auth');

Route::post('supplies/date', [DashboardController::class, 'getSupplies'])
    ->name('supply.date')
    ->middleware('auth');

Route::post('supplies/get-month',[DashboardController::class,'getMonth'])
    ->name('supply.get-month')
    ->middleware('auth');

//Поставщики

Route::get('supp',[SuppliersController::class, 'index'])
->name('supp')
->middleware('auth');

Route::post('suppliers',[SuppliersController::class, 'store'])
    ->name('suppliers.store')
    ->middleware('auth');

Route::get('suppliers/create', [SuppliersController::class, 'create'])
    ->name('create-supplier')
    ->middleware('auth');

Route::post('suppliers/history', [SuppliersController::class, 'history'])
    ->name('supplier-history')
    ->middleware('auth');

Route::post('supplies/bydate',[SuppliersController::class, 'bydate'])
    ->name('supplies.bydate')
    ->middleware('auth');

Route::post('supplies/bymonth',[SuppliersController::class, 'getSuppliesByMonth'])
    ->name('supplies/bymonth')
    ->middleware('auth');

Route::post('supplies/byyear',[SuppliersController::class, 'getSuppliesByYear'])
    ->name('supplies/byyear')
    ->middleware('auth');    

Route::post('supplies/delete-postavka',[SuppliersController::class, 'deletePostavka'])
    ->name('supplies.delete-postavka')
    ->middleware('auth');

Route::post('supplies/bysuppliermonth',[SuppliersController::class,'getSuppliesBySupplier'])
    ->name('supplies/bysuppliermonth')
    ->middleware('auth');

//Переработка

Route::get('conversions/new',[ConversionsController::class,'NewConversion'])
    ->name('conversions.new')
    ->middleware('auth');

Route::get('conversions/end-month',[ConversionsController::class,'endMonth'])
    ->name('conversions.end-month')
    ->middleware('auth');

Route::get('conversions', [ConversionsController::class, 'index'])
    ->name('conversions')
    ->middleware('auth');

Route::post('conversions/save', [ConversionsController::class, 'save'])
    ->name('save-conversion')
    ->middleware('auth');

Route::post('conversions/change', [ConversionsController::class, 'change'])
    ->name('get-conversions')
    ->middleware('auth');

Route::post('conversions/create', [ConversionsController::class, 'createAssortment'])
    ->name('create-assortment')
    ->middleware('auth');

Route::get('conversions/{month}',[ConversionsController::class,'downloadReport'])
    ->name('conversions.month')
    ->middleware('auth');

// Auth

Route::get('login', [LoginController::class, 'showLoginForm'])
    ->name('login')
    ->middleware('guest');

Route::post('login', [LoginController::class, 'login'])
    ->name('login.attempt')
    ->middleware('guest');

Route::post('logout', [LoginController::class, 'logout'])
    ->name('logout');

// Dashboard

Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth');

Route::get('/new', [DashboardController::class, 'NewSupply'])
    ->name('dashboard.new')
    ->middleware('auth');

Route::post('/events/seen', [DashboardController::class, 'setEventSeen'])
    ->name('events.seen')
    ->middleware('auth');

// Users
Route::get('get-profile/{id}', [UsersController::class, 'getProfile']);

Route::get('users/profile',[UsersController::class,'profile'])
    ->name('users.profile')
    ->middleware('auth');

Route::get('users', [UsersController::class, 'index'])
    ->name('users')
    ->middleware('remember', 'auth');

Route::get('users/create', [UsersController::class, 'create'])
    ->name('users.create')
    ->middleware('auth');

Route::post('users', [UsersController::class, 'store'])
    ->name('users.store')
    ->middleware('auth');

Route::get('users/{user}/edit', [UsersController::class, 'edit'])
    ->name('users.edit')
    ->middleware('auth');

Route::get('users/{user}/edit2', [UsersController::class, 'edit2'])
    ->name('users.edit2')
    ->middleware('auth');

Route::put('users/{user}', [UsersController::class, 'update'])
    ->name('users.update')
    ->middleware('auth');

Route::delete('users/{user}', [UsersController::class, 'destroy'])
    ->name('users.destroy')
    ->middleware('auth');

Route::put('users/{user}/restore', [UsersController::class, 'restore'])
    ->name('users.restore')
    ->middleware('auth');

// Organizations

Route::get('organizations', [OrganizationsController::class, 'index'])
    ->name('organizations')
    ->middleware('remember', 'auth');

Route::get('organizations/create', [OrganizationsController::class, 'create'])
    ->name('organizations.create')
    ->middleware('auth');

Route::post('organizations', [OrganizationsController::class, 'store'])
    ->name('organizations.store')
    ->middleware('auth');

Route::post('/organizations/status', [OrganizationsController::class, 'status'])
    ->name('organizations.status')
    ->middleware('auth');
    
Route::get('organizations/{organization}/edit', [OrganizationsController::class, 'edit'])
    ->name('organizations.edit')
    ->middleware('auth');

Route::put('organizations/{organization}', [OrganizationsController::class, 'update'])
    ->name('organizations.update')
    ->middleware('auth');

Route::delete('organizations/{organization}', [OrganizationsController::class, 'destroy'])
    ->name('organizations.destroy')
    ->middleware('auth');

Route::put('organizations/{organization}/restore', [OrganizationsController::class, 'restore'])
    ->name('organizations.restore')
    ->middleware('auth');

Route::post('organizations/{message}/{id}', [OrganizationsController::class, 'comment'])
    ->name('organizations.comment')
    ->middleware('auth');

// Contacts

Route::get('contacts', [ContactsController::class, 'index'])
    ->name('contacts')
    ->middleware('remember', 'auth');

Route::get('contacts/create', [ContactsController::class, 'create'])
    ->name('contacts.create')
    ->middleware('auth');

Route::post('contacts', [ContactsController::class, 'store'])
    ->name('contacts.store')
    ->middleware('auth');

Route::get('contacts/{contact}/edit', [ContactsController::class, 'edit'])
    ->name('contacts.edit')
    ->middleware('auth');

Route::put('contacts/{contact}', [ContactsController::class, 'update'])
    ->name('contacts.update')
    ->middleware('auth');

Route::delete('contacts/{contact}', [ContactsController::class, 'destroy'])
    ->name('contacts.destroy')
    ->middleware('auth');

Route::put('contacts/{contact}/restore', [ContactsController::class, 'restore'])
    ->name('contacts.restore')
    ->middleware('auth');
// Flats

Route::get('flats', [FlatsController::class, 'index'])
    ->name('flats')
    ->middleware('auth');
//List

Route::get('list', [ListController::class, 'index'])
    ->name('list')
    ->middleware('auth');




//Post

Route::get('post',[PostController::class,'index'])
->name('post')
->middleware('auth');

// Images

Route::get('/img/{path}', [ImagesController::class, 'show'])->where('path', '.*');

// Mail

Route::get('mail/{driver?}/{folder?}/{page?}', [MailController::class, 'index'])
    ->name('mail')  
    ->middleware('auth');

Route::get('axios/mail/{driver}/{folder?}/{page?}', [MailController::class, 'axiosIndex'])
    ->middleware('auth');

Route::post('axios/send-mail', [MailController::class, 'send_mail'])->name('mail.send')->middleware('auth');


// 500 error

Route::get('500', function () {
    // Force debug mode for this endpoint in the demo environment
    if (App::environment('demo')) {
        Config::set('app.debug', true);
    }

    echo $fail;
});

Route::post('add-to-sklad',[StoreController::class,'addSklad'])
    ->name('add-to-sklad')
    ->middleware('auth');

Route::post('add-to-freezer',[StoreController::class,'addFreezer'])
    ->name('add-to-freezer')
    ->middleware('auth');


Route::resource('categories',CategoryController::class);

//create storage link

Route::get('generate', function (){
    Illuminate\Support\Facades\File::link(storage_path('app'), public_path('storage'));
    echo 'success';
});



