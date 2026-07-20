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
use App\Http\Controllers\Admin\ComplaintController as AdminComplaintController;
use App\Http\Controllers\Admin\FeedbackController as AdminFeedbackController;
use App\Http\Controllers\Admin\DonationController as AdminDonationController;

// Counselor Controllers (Phase 4)
use App\Http\Controllers\Counselor\ComplaintController as CounselorComplaintController;
use App\Http\Controllers\Counselor\FeedbackController as CounselorFeedbackController;

// Counselee Controllers (Phase 4)
use App\Http\Controllers\Counselee\FeedbackController as CounseleeFeedbackController;
use App\Http\Controllers\Counselee\ComplaintController as CounseleeComplaintController;
use App\Http\Controllers\Counselee\DonationController as CounseleeDonationController;

// Location master (Phase 5)
use App\Http\Controllers\LocationController;
use App\Http\Controllers\Admin\CountryController as AdminCountryController;
use App\Http\Controllers\Admin\StateController as AdminStateController;
use App\Http\Controllers\Admin\CityController as AdminCityController;

// Counselor Leave module
use App\Http\Controllers\Counselor\LeaveController as CounselorLeaveController;
use App\Http\Controllers\Admin\CounselorLeaveController as AdminCounselorLeaveController;

// Reports (admin/super admin only)
use App\Http\Controllers\Admin\ReportController as AdminReportController;

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
| Location cascading dropdowns — public, guard-agnostic (used by
| registration, admin forms, and any authenticated form alike)
|--------------------------------------------------------------------------
*/
Route::prefix('locations')->name('locations.')->group(function () {
    Route::get('states/{country}', [LocationController::class, 'states'])->name('states');
    Route::get('cities/{state}',   [LocationController::class, 'cities'])->name('cities');
});

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

            Route::prefix('countries')->name('countries.')->group(function () {
                Route::get('/',                     [AdminCountryController::class, 'index'])->name('index');
                Route::get('/create',               [AdminCountryController::class, 'create'])->name('create');
                Route::post('/',                    [AdminCountryController::class, 'store'])->name('store');
                Route::get('/{country}/edit',       [AdminCountryController::class, 'edit'])->name('edit');
                Route::put('/{country}',            [AdminCountryController::class, 'update'])->name('update');
                Route::patch('/{country}/toggle',   [AdminCountryController::class, 'toggleStatus'])->name('toggle');
                Route::delete('/{country}',         [AdminCountryController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('states')->name('states.')->group(function () {
                Route::get('/',                   [AdminStateController::class, 'index'])->name('index');
                Route::get('/create',             [AdminStateController::class, 'create'])->name('create');
                Route::post('/',                  [AdminStateController::class, 'store'])->name('store');
                Route::get('/{state}/edit',       [AdminStateController::class, 'edit'])->name('edit');
                Route::put('/{state}',            [AdminStateController::class, 'update'])->name('update');
                Route::patch('/{state}/toggle',   [AdminStateController::class, 'toggleStatus'])->name('toggle');
                Route::delete('/{state}',         [AdminStateController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('cities')->name('cities.')->group(function () {
                Route::get('/',                  [AdminCityController::class, 'index'])->name('index');
                Route::get('/create',            [AdminCityController::class, 'create'])->name('create');
                Route::post('/',                 [AdminCityController::class, 'store'])->name('store');
                Route::get('/{city}/edit',       [AdminCityController::class, 'edit'])->name('edit');
                Route::put('/{city}',            [AdminCityController::class, 'update'])->name('update');
                Route::patch('/{city}/toggle',   [AdminCityController::class, 'toggleStatus'])->name('toggle');
                Route::delete('/{city}',         [AdminCityController::class, 'destroy'])->name('destroy');
            });
        });

        // Complaints
        Route::prefix('complaints')->name('complaints.')->group(function () {
            Route::get('/',             [AdminComplaintController::class, 'index'])->name('index');
            Route::get('/{complaint}',  [AdminComplaintController::class, 'show'])->name('show');
            Route::put('/{complaint}',  [AdminComplaintController::class, 'update'])->name('update');
        });

        // Feedback
        Route::prefix('feedback')->name('feedback.')->group(function () {
            Route::get('/',            [AdminFeedbackController::class, 'index'])->name('index');
            Route::get('/{feedback}',  [AdminFeedbackController::class, 'show'])->name('show');
        });

        // Donations
        Route::prefix('donations')->name('donations.')->group(function () {
            Route::get('/',                       [AdminDonationController::class, 'index'])->name('index');
            Route::get('/{donation}',             [AdminDonationController::class, 'show'])->name('show');
            Route::post('/{donation}/complete',   [AdminDonationController::class, 'markCompleted'])->name('complete');
        });

        // Counselor Leaves — admin applying/managing leave on behalf of any counselor
        Route::prefix('counselor-leaves')->name('counselor-leaves.')->group(function () {
            Route::get('/',                     [AdminCounselorLeaveController::class, 'index'])->name('index');
            Route::get('/create',               [AdminCounselorLeaveController::class, 'create'])->name('create');
            Route::post('/',                    [AdminCounselorLeaveController::class, 'store'])->name('store');
            Route::delete('/{counselorLeave}',  [AdminCounselorLeaveController::class, 'destroy'])->name('destroy');
        });

        // Reports
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [AdminReportController::class, 'index'])->name('index');

            $reports = [
                'appointments-summary'  => 'appointmentsSummary',
                'overdue-appointments'  => 'overdueAppointments',
                'cancellations'         => 'cancellations',
                'counselor-performance' => 'counselorPerformance',
                'counselor-utilization' => 'counselorUtilization',
                'feedback-ratings'      => 'feedbackRatings',
                'complaints'            => 'complaints',
                'counselling-demand'    => 'counsellingDemand',
                'city-coverage-gap'     => 'cityCoverageGap',
                'registrations'         => 'registrations',
                'donations'             => 'donations',
                'leave-calendar'        => 'leaveCalendar',
            ];

            foreach ($reports as $slug => $method) {
                Route::get($slug,                [AdminReportController::class, $method])->name($slug);
                Route::get($slug . '/data',      [AdminReportController::class, $method . 'Data'])->name($slug . '.data');
                Route::get($slug . '/export',    [AdminReportController::class, $method . 'Export'])->name($slug . '.export');
            }
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
            Route::get('/{appointment}',        [CounselorAppointmentController::class, 'show'])->name('show');
            Route::put('/{appointment}/notes',  [CounselorAppointmentController::class, 'updateNotes'])->name('notes.update');
            Route::post('/{appointment}/cancel', [CounselorAppointmentController::class, 'cancel'])->name('cancel');
            Route::post('/{appointment}/complete', [CounselorAppointmentController::class, 'complete'])->name('complete');

            Route::get('/{appointment}/reschedule',       [CounselorAppointmentController::class, 'editReschedule'])->name('reschedule.edit');
            Route::post('/{appointment}/reschedule/dates', [CounselorAppointmentController::class, 'getRescheduleDates'])->name('reschedule.dates');
            Route::post('/{appointment}/reschedule/slots', [CounselorAppointmentController::class, 'getRescheduleSlots'])->name('reschedule.slots');
            Route::post('/{appointment}/reschedule',       [CounselorAppointmentController::class, 'reschedule'])->name('reschedule');
        });

        // Feedback — read-only, feedback left on this counselor's own sessions
        Route::prefix('feedback')->name('feedback.')->group(function () {
            Route::get('/', [CounselorFeedbackController::class, 'index'])->name('index');
        });

        // Complaints — file a complaint, and view complaints filed about this counselor
        Route::prefix('complaints')->name('complaints.')->group(function () {
            Route::get('/',       [CounselorComplaintController::class, 'index'])->name('index');
            Route::get('/create', [CounselorComplaintController::class, 'create'])->name('create');
            Route::post('/',      [CounselorComplaintController::class, 'store'])->name('store');
        });

        // Leaves — mark dates unavailable for booking
        Route::prefix('leaves')->name('leaves.')->group(function () {
            Route::get('/',        [CounselorLeaveController::class, 'index'])->name('index');
            Route::get('/create',  [CounselorLeaveController::class, 'create'])->name('create');
            Route::post('/',       [CounselorLeaveController::class, 'store'])->name('store');
            Route::delete('/{leave}', [CounselorLeaveController::class, 'destroy'])->name('destroy');
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
            Route::get('/{appointment}',         [CounseleeAppointmentController::class, 'show'])->name('show');
            Route::post('/{appointment}/cancel', [CounseleeAppointmentController::class, 'cancel'])->name('cancel');

            Route::get('/{appointment}/reschedule',       [CounseleeAppointmentController::class, 'editReschedule'])->name('reschedule.edit');
            Route::post('/{appointment}/reschedule/slots', [CounseleeAppointmentController::class, 'getRescheduleSlots'])->name('reschedule.slots');
            Route::post('/{appointment}/reschedule',       [CounseleeAppointmentController::class, 'reschedule'])->name('reschedule');

            // Feedback for a completed session
            Route::prefix('/{appointment}/feedback')->name('feedback.')->group(function () {
                Route::get('/',  [CounseleeFeedbackController::class, 'create'])->name('create');
                Route::post('/', [CounseleeFeedbackController::class, 'store'])->name('store');
            });
        });

        // Complaints
        Route::prefix('complaints')->name('complaints.')->group(function () {
            Route::get('/',       [CounseleeComplaintController::class, 'index'])->name('index');
            Route::get('/create', [CounseleeComplaintController::class, 'create'])->name('create');
            Route::post('/',      [CounseleeComplaintController::class, 'store'])->name('store');
        });

        // Feedback — list of all feedback this counselee has submitted
        Route::prefix('feedback')->name('feedback.')->group(function () {
            Route::get('/', [CounseleeFeedbackController::class, 'index'])->name('index');
        });
    });

    // Donations — intentionally public (guest donations allowed), kept under the
    // 'counselee.' route name group for naming consistency with the rest of Phase 4.
    Route::prefix('donations')->name('donations.')->group(function () {
        Route::get('/',           [CounseleeDonationController::class, 'create'])->name('create');
        Route::post('/',          [CounseleeDonationController::class, 'store'])->name('store');
        Route::get('/callback',   [CounseleeDonationController::class, 'callback'])->name('callback');
        Route::post('/webhook',   [CounseleeDonationController::class, 'webhook'])->name('webhook');
    });
});
