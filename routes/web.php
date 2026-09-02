<?php

use App\Http\Controllers\Admin\AdminCalendarController;
use App\Http\Controllers\Admin\AdminCustomerController;
use App\Http\Controllers\Admin\AdminNoteController;
use App\Http\Controllers\Admin\AdminVisitController;
use App\Http\Controllers\Admin\CustomerSatisfactionReportController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerSatisfactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResourceController;
use App\Models\CustomerSatisfactionInvitation;
use App\Models\Visit;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\SalesRepController;

require __DIR__ . '/auth.php';

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/shared-resource/{resource}', [ResourceController::class, 'publicResource'])->name('resources.public');

Route::get('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/customers/{customer}', [CustomerController::class, 'show'])->middleware('permission:customers.view')->name('customers.show');
    Route::get('/visits/{visit}', [CustomerController::class, 'getVisit'])->name('visits.get');
    Route::get('/resources', [ResourceController::class, 'index'])->name('resources.index');
    Route::get('/resources/create', [ResourceController::class, 'create'])->name('resources.create');
    Route::post('/resources', [ResourceController::class, 'store'])->name('resources.store');
    Route::get('/resources/{resource}', [ResourceController::class, 'show'])->name('resources.show');
    Route::get('/resources/{resource}/edit', [ResourceController::class, 'edit'])->name('resources.edit');
    Route::put('/resources/{resource}', [ResourceController::class, 'update'])->name('resources.update');
    Route::delete('/resources/{resource}', [ResourceController::class, 'destroy'])->name('resources.destroy');
    Route::post('/resources/{resource}/share', [ResourceController::class, 'share'])->name('resources.share');
    Route::get('/customers/{customer}/visits/load-more', [CustomerController::class, 'loadMoreVisits'])->name('customers.visits.loadMore');
    Route::get('/customers/{customer}/resources/load-more', [CustomerController::class, 'loadMoreResources'])->name('customers.resources.loadMore');

});

Route::middleware(['auth', 'role:sales_rep',])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/create-customers', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');

    Route::post('/customers/{customer}/stand/update-date', [CustomerController::class, 'updateStandDate'])->name('customers.stand.updateDate');
    Route::post('/customers/{customer}/visits/schedule', [CustomerController::class, 'scheduleVisit'])->name('customers.visits.schedule');
    Route::post('/customers/{customer}/visits/log', [CustomerController::class, 'logVisit'])->name('customers.visits.log');
    Route::post('/visits/{visit}/check-in', [CustomerController::class, 'checkInVisit'])->name('visits.checkIn');
    Route::post('/visits/{visit}/check-out', [CustomerController::class, 'checkOutVisit'])->name('visits.checkOut');
    Route::put('/visits/{visit}', [CustomerController::class, 'updateVisit'])->name('visits.update');
    Route::post('/visits/{visit}/reschedule', [CustomerController::class, 'reschedule'])->name('visits.reschedule');
    Route::post('/visits/{visit}/cancel', [CustomerController::class, 'cancel'])->name('visits.cancel');
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
    Route::get('/calendar/visits', [CalendarController::class, 'visits'])->name('calendar.visits');
});


Route::middleware(['auth', 'role:admin',])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/sales-reps', [SalesRepController::class, 'index'])->name('sales-reps.index');
    Route::get('/sales-reps/{user}', [SalesRepController::class, 'show'])->name('sales-reps.show');
    Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers.index');
    Route::post('/customers/{customer}/assign', [AdminCustomerController::class, 'assign'])->name('customers.assign');
    Route::get('/visits', [AdminVisitController::class, 'index'])->name('visits.index');
    Route::get('/visits/{visit}', [AdminVisitController::class, 'show'])->name('visits.show');
    Route::post('/customers/{customer}/notes', [AdminNoteController::class, 'storeCustomer'])->name('customers.notes.store');
    Route::post('/visits/{visit}/notes', [AdminNoteController::class, 'storeVisit'])->name('visits.notes.store');
    Route::delete('/notes/{adminNote}', [AdminNoteController::class, 'destroy'])->name('notes.destroy');
    Route::get('/calendar', [AdminCalendarController::class, 'index'])->name('calendar.index');
    Route::get('/customers/export', [AdminDashboardController::class, 'export'])->name('customers.export');
    Route::get('/satisfaction-reports', [CustomerSatisfactionReportController::class, 'index'])->name('satisfaction-reports.index');
});


Route::middleware(['auth', 'role:admin',])->prefix('admin/reports')->name('admin.reports.')->group(function () {
    Route::get('/location-verification', [ReportController::class, 'locationVerification'])->name('location-verification');
    Route::get('/', [ReportController::class, 'index'])->name('index');
    Route::get('/sales-performance', [ReportController::class, 'salesPerformance'])->name('sales-performance');
    Route::get('/visits', [ReportController::class, 'visits'])->name('visits');
    Route::get('/customer-coverage', [ReportController::class, 'customerCoverage'])->name('customer-coverage');
    Route::get('/distribution', [ReportController::class, 'distribution'])->name('distribution');

});


Route::get('/feedback/{token}', [CustomerSatisfactionController::class, 'show'])->name('satisfaction.show');
Route::post('/feedback/{token}', [CustomerSatisfactionController::class, 'store'])->name('satisfaction.store');


Route::get('/test-satisfaction', function () {

    $visit = Visit::with('customer')->where('customer_id', '01a05780-cc5f-7298-ac0d-816c3d2c931e')->firstOrFail();

    // Remove old test invitation for this visit
    CustomerSatisfactionInvitation::where('visit_id', $visit->id)->delete();

    $invitation = CustomerSatisfactionInvitation::create(['visit_id' => $visit->id, 'customer_id' => $visit->customer_id, 'sales_rep_id' => $visit->sales_rep_id, 'email' => $visit->customer?->email ?? 'test@example.com', 'token' => Str::random(64), 'expires_at' => now()->addDays(14),]);

    return redirect()->route('satisfaction.show', $invitation->token);

});
