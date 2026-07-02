<?php

use Illuminate\Support\Facades\Route;

// Auth Controllers
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\CounselorAuthController;
use App\Http\Controllers\Auth\CounseleeAuthController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController  as AdminDashboard;
use App\Http\Controllers\Admin\CounselorController  as AdminCounselorController;
use App\Http\Controllers\Admin\CounseleeController  as AdminCounseleeController;

// Counselor Controllers
use App\Http\Controllers\Counselor\DashboardController as CounselorDashboard;
use App\Http\Controllers\Counselor\AppointmentController as CounselorAppointmentController;

// Counselee Controllers
use App\Http\Controllers\Counselee\DashboardController as CounseleeDashboard;
use App\Http\Controllers\Counselee\AppointmentController as CounseleeAppointmentController;

use App\Http\Controllers\Admin\CounselTypeController;
use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;

/*
|--------------------------------------------------------------------------
| Root
|--------------------------------------------------------------------------
*/
//Route::get('/', fn() => redirect()->route('admin.login'));
Route::get('/', function () {
    return view('landing');
})->name('landing');

/*
|--------------------------------------------------------------------------
| Super Admin — /admin
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {

    // Public routes
    Route::get('login',  [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('login.post');

    // Protected routes
    Route::middleware('superadmin')->group(function () {

        Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');

        // Dashboard
        Route::get('dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

        // Counselors CRUD
       Route::prefix('counselors')->name('counselors.')->group(function () {
    Route::get('/',                    [AdminCounselorController::class, 'index'])->name('index');
    Route::get('/create',              [AdminCounselorController::class, 'create'])->name('create');
    Route::post('/',                   [AdminCounselorController::class, 'store'])->name('store');
    Route::get('/{counselor}',         [AdminCounselorController::class, 'show'])->name('show');
    Route::get('/{counselor}/edit',    [AdminCounselorController::class, 'edit'])->name('edit');
    Route::put('/{counselor}',         [AdminCounselorController::class, 'update'])->name('update');
    Route::patch('/{counselor}/toggle',[AdminCounselorController::class, 'toggleStatus'])->name('toggle');
    Route::delete('/{counselor}',      [AdminCounselorController::class, 'destroy'])->name('destroy');
});
       // Counselees CRUD
Route::prefix('counselees')->name('counselees.')->group(function () {
    Route::get('/',                     [AdminCounseleeController::class, 'index'])->name('index');
    Route::get('/create',               [AdminCounseleeController::class, 'create'])->name('create');
    Route::post('/',                    [AdminCounseleeController::class, 'store'])->name('store');
    Route::get('/{counselee}',          [AdminCounseleeController::class, 'show'])->name('show');
    Route::get('/{counselee}/edit',     [AdminCounseleeController::class, 'edit'])->name('edit');
    Route::put('/{counselee}',          [AdminCounseleeController::class, 'update'])->name('update');
    Route::delete('/{counselee}',       [AdminCounseleeController::class, 'destroy'])->name('destroy');
    Route::post('/{counselee}/toggle', [AdminCounseleeController::class, 'toggleStatus'])->name('toggle');
});

        // Appointments
        Route::prefix('appointments')->name('appointments.')->group(function () {
            Route::get('/',                     [AdminAppointmentController::class, 'index'])->name('index');
            Route::get('/create',               [AdminAppointmentController::class, 'create'])->name('create');
            Route::post('/dates',               [AdminAppointmentController::class, 'getAvailableDates'])->name('dates');
            Route::post('/slots',               [AdminAppointmentController::class, 'getAvailableSlots'])->name('slots');
            Route::post('/',                    [AdminAppointmentController::class, 'store'])->name('store');
            Route::get('/{appointment}',        [AdminAppointmentController::class, 'show'])->name('show');
            Route::post('/{appointment}/cancel', [AdminAppointmentController::class, 'cancel'])->name('cancel');

            Route::get('/{appointment}/reschedule',       [AdminAppointmentController::class, 'editReschedule'])->name('reschedule.edit');
            Route::post('/{appointment}/reschedule/dates', [AdminAppointmentController::class, 'getRescheduleDates'])->name('reschedule.dates');
            Route::post('/{appointment}/reschedule/slots', [AdminAppointmentController::class, 'getRescheduleSlots'])->name('reschedule.slots');
            Route::post('/{appointment}/reschedule',       [AdminAppointmentController::class, 'reschedule'])->name('reschedule');
        });

        Route::prefix('masters')->name('masters.')->group(function () {
            Route::prefix('counsel-types')->name('counsel-types.')->group(function () {
                Route::get('/',                       [CounselTypeController::class, 'index'])->name('index');
                Route::get('/create',                 [CounselTypeController::class, 'create'])->name('create');
                Route::post('/',                      [CounselTypeController::class, 'store'])->name('store');
                Route::get('/{counselType}/edit',     [CounselTypeController::class, 'edit'])->name('edit');
                Route::put('/{counselType}',          [CounselTypeController::class, 'update'])->name('update');
                Route::patch('/{counselType}/toggle', [CounselTypeController::class, 'toggleStatus'])->name('toggle');
                Route::delete('/{counselType}',       [CounselTypeController::class, 'destroy'])->name('destroy');
            });
        });

    });
});

/*
|--------------------------------------------------------------------------
| Counselor — /counselor
|--------------------------------------------------------------------------
*/
Route::prefix('counselor')->name('counselor.')->group(function () {

    // Public routes
    Route::get('login',     [CounselorAuthController::class, 'showLogin'])->name('login');
    Route::post('login',    [CounselorAuthController::class, 'login'])->name('login.post');
    Route::get('register',  [CounselorAuthController::class, 'showRegister'])->name('register');
    Route::post('register', [CounselorAuthController::class, 'register'])->name('register.post');

    // Protected routes
    Route::middleware('counselor')->group(function () {
        Route::get('dashboard', [CounselorDashboard::class, 'index'])->name('dashboard');
        Route::post('logout',   [CounselorAuthController::class, 'logout'])->name('logout');

        // Appointments
        Route::prefix('appointments')->name('appointments.')->group(function () {
            Route::get('/',                     [CounselorAppointmentController::class, 'index'])->name('index');
            Route::post('/{appointment}/cancel', [CounselorAppointmentController::class, 'cancel'])->name('cancel');
            Route::post('/{appointment}/complete', [CounselorAppointmentController::class, 'complete'])->name('complete');

            Route::get('/{appointment}/reschedule',       [CounselorAppointmentController::class, 'editReschedule'])->name('reschedule.edit');
            Route::post('/{appointment}/reschedule/dates', [CounselorAppointmentController::class, 'getRescheduleDates'])->name('reschedule.dates');
            Route::post('/{appointment}/reschedule/slots', [CounselorAppointmentController::class, 'getRescheduleSlots'])->name('reschedule.slots');
            Route::post('/{appointment}/reschedule',       [CounselorAppointmentController::class, 'reschedule'])->name('reschedule');
        });
    });
});

/*
|--------------------------------------------------------------------------
| Counselee — /counselee
|--------------------------------------------------------------------------
*/
Route::prefix('counselee')->name('counselee.')->group(function () {

    // Public routes
    Route::get('login',     [CounseleeAuthController::class, 'showLogin'])->name('login');
    Route::post('login',    [CounseleeAuthController::class, 'login'])->name('login.post');
    Route::get('register',  [CounseleeAuthController::class, 'showRegister'])->name('register');
    Route::post('register', [CounseleeAuthController::class, 'register'])->name('register.post');

    // Protected routes
    Route::middleware('counselee')->group(function () {
        Route::get('dashboard', [CounseleeDashboard::class, 'index'])->name('dashboard');
        Route::post('logout',   [CounseleeAuthController::class, 'logout'])->name('logout');

        // Appointments
        Route::prefix('appointments')->name('appointments.')->group(function () {
            Route::get('/',          [CounseleeAppointmentController::class, 'index'])->name('index');
            Route::get('/book',      [CounseleeAppointmentController::class, 'create'])->name('create');
            Route::post('/dates',    [CounseleeAppointmentController::class, 'getAvailableDates'])->name('dates');
            Route::post('/slots',    [CounseleeAppointmentController::class, 'getAvailableSlots'])->name('slots');
            Route::post('/preview',  [CounseleeAppointmentController::class, 'preview'])->name('preview');
            Route::post('/',         [CounseleeAppointmentController::class, 'store'])->name('store');
            Route::post('/{appointment}/cancel', [CounseleeAppointmentController::class, 'cancel'])->name('cancel');

            Route::get('/{appointment}/reschedule',       [CounseleeAppointmentController::class, 'editReschedule'])->name('reschedule.edit');
            Route::post('/{appointment}/reschedule/slots', [CounseleeAppointmentController::class, 'getRescheduleSlots'])->name('reschedule.slots');
            Route::post('/{appointment}/reschedule',       [CounseleeAppointmentController::class, 'reschedule'])->name('reschedule');
        });
    });
});
