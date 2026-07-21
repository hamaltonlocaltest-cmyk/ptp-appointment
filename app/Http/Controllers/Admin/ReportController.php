<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AppointmentFeedback;
use App\Models\City;
use App\Models\Complaint;
use App\Models\Counselee;
use App\Models\Counselor;
use App\Models\CounselorAvailability;
use App\Models\CounselorLeave;
use App\Models\CounselType;
use App\Models\Country;
use App\Models\Donation;
use App\Models\State;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    // -----------------------------------------------------------------------
    // Landing page
    // -----------------------------------------------------------------------
    public function index()
    {
        return view('admin.reports.index');
    }

    // -----------------------------------------------------------------------
    // Shared helpers
    // -----------------------------------------------------------------------

    private function counselorOptions()
    {
        return Counselor::where('status', '!=', 'deleted')->orderBy('first_name')->get(['id', 'first_name', 'last_name']);
    }

    private function counselTypeOptions()
    {
        return CounselType::orderBy('name')->get(['id', 'name']);
    }

    private function cityOptions()
    {
        return City::active()->orderBy('name')->get(['id', 'name']);
    }

    private function stateOptions()
    {
        return State::active()->orderBy('name')->get(['id', 'name']);
    }

    private function countryOptions()
    {
        return Country::active()->orderBy('name')->get(['id', 'name']);
    }

    // [start, end] Carbon dates from date_from/date_to request params, or null if not given.
    private function dateRange(Request $request): array
    {
        $from = $request->filled('date_from') ? Carbon::parse($request->date_from)->startOfDay() : null;
        $to   = $request->filled('date_to') ? Carbon::parse($request->date_to)->endOfDay() : null;

        return [$from, $to];
    }

    private function streamCsv(string $filename, array $headers, iterable $rows)
    {
        return response()->streamDownload(function () use ($headers, $rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $headers);
            foreach ($rows as $row) {
                fputcsv($out, $row);
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    // =========================================================================
    // 1. Appointments Summary Report
    // =========================================================================

    public function appointmentsSummary()
    {
        return view('admin.reports.appointments-summary', [
            'counselors'   => $this->counselorOptions(),
            'counselTypes' => $this->counselTypeOptions(),
            'cities'       => $this->cityOptions(),
        ]);
    }

    private function appointmentsSummaryQuery(Request $request)
    {
        [$from, $to] = $this->dateRange($request);

        return Appointment::with(['counselee', 'counselor', 'counselType'])
            ->when($from, fn($q) => $q->whereDate('appointment_date', '>=', $from))
            ->when($to, fn($q) => $q->whereDate('appointment_date', '<=', $to))
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->when($request->filled('mode'), fn($q) => $q->where('mode', $request->mode))
            ->when($request->filled('counsel_type_id'), fn($q) => $q->where('counsel_type_id', $request->counsel_type_id))
            ->when($request->filled('counselor_id'), fn($q) => $q->where('counselor_id', $request->counselor_id))
            ->when($request->filled('city_id'), fn($q) => $q->whereHas('counselee', fn($qq) => $qq->where('city_id', $request->city_id)))
            ->orderByDesc('appointment_date')
            ->orderByDesc('start_time');
    }

    public function appointmentsSummaryData(Request $request)
    {
        $rows = $this->appointmentsSummaryQuery($request)->get()->map(fn($a) => [
            'date'        => $a->appointment_date->format('M j, Y'),
            'time'        => $a->formatted_time,
            'counselee'   => $a->counselee->full_name ?? 'Deleted Counsellee',
            'counselor'   => $a->counselor->full_name ?? 'Deleted Counselor',
            'counsel_type' => $a->counselType->name ?? '—',
            'mode'        => $a->mode,
            'status'      => ucfirst($a->status),
        ]);

        return response()->json(['data' => $rows]);
    }

    public function appointmentsSummaryExport(Request $request)
    {
        $rows = $this->appointmentsSummaryQuery($request)->get()->map(fn($a) => [
            $a->appointment_date->format('Y-m-d'),
            $a->formatted_time,
            $a->counselee->full_name ?? 'Deleted Counsellee',
            $a->counselor->full_name ?? 'Deleted Counselor',
            $a->counselType->name ?? '—',
            $a->mode,
            ucfirst($a->status),
        ]);

        return $this->streamCsv('appointments-summary.csv',
            ['Date', 'Time', 'Counsellee', 'Counselor', 'Counselling Area', 'Mode', 'Status'], $rows);
    }

    // =========================================================================
    // 2. Overdue / Uncompleted Appointments Report
    // =========================================================================

    public function overdueAppointments()
    {
        return view('admin.reports.overdue-appointments', [
            'counselors' => $this->counselorOptions(),
        ]);
    }

    private function overdueAppointmentsQuery(Request $request)
    {
        [$from, $to] = $this->dateRange($request);

        return Appointment::with(['counselee', 'counselor', 'counselType'])
            ->completable()
            ->when($from, fn($q) => $q->whereDate('appointment_date', '>=', $from))
            ->when($to, fn($q) => $q->whereDate('appointment_date', '<=', $to))
            ->when($request->filled('counselor_id'), fn($q) => $q->where('counselor_id', $request->counselor_id))
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->orderBy('appointment_date')
            ->orderBy('start_time');
    }

    public function overdueAppointmentsData(Request $request)
    {
        $rows = $this->overdueAppointmentsQuery($request)->get()->map(function ($a) {
            $endsAt = Carbon::parse($a->appointment_date->toDateString() . ' ' . $a->end_time);
            return [
                'date'         => $a->appointment_date->format('M j, Y'),
                'time'         => $a->formatted_time,
                'counselee'    => $a->counselee->full_name ?? 'Deleted Counsellee',
                'counselor'    => $a->counselor->full_name ?? 'Deleted Counselor',
                'counsel_type' => $a->counselType->name ?? '—',
                'status'       => ucfirst($a->status),
                'days_overdue' => $endsAt->diffInDays(now()),
            ];
        });

        return response()->json(['data' => $rows]);
    }

    public function overdueAppointmentsExport(Request $request)
    {
        $rows = $this->overdueAppointmentsQuery($request)->get()->map(function ($a) {
            $endsAt = Carbon::parse($a->appointment_date->toDateString() . ' ' . $a->end_time);
            return [
                $a->appointment_date->format('Y-m-d'),
                $a->formatted_time,
                $a->counselee->full_name ?? 'Deleted Counsellee',
                $a->counselor->full_name ?? 'Deleted Counselor',
                $a->counselType->name ?? '—',
                ucfirst($a->status),
                $endsAt->diffInDays(now()),
            ];
        });

        return $this->streamCsv('overdue-appointments.csv',
            ['Date', 'Time', 'Counsellee', 'Counselor', 'Counselling Area', 'Status', 'Days Overdue'], $rows);
    }

    // =========================================================================
    // 3. Cancellation Report
    // =========================================================================

    public function cancellations()
    {
        return view('admin.reports.cancellations', [
            'counselors'   => $this->counselorOptions(),
            'counselTypes' => $this->counselTypeOptions(),
        ]);
    }

    private function cancellationsQuery(Request $request)
    {
        [$from, $to] = $this->dateRange($request);

        return Appointment::with(['counselee', 'counselor', 'counselType'])
            ->where('status', 'cancelled')
            ->when($from, fn($q) => $q->whereDate('cancelled_at', '>=', $from))
            ->when($to, fn($q) => $q->whereDate('cancelled_at', '<=', $to))
            ->when($request->filled('cancelled_by'), fn($q) => $q->where('cancelled_by', $request->cancelled_by))
            ->when($request->filled('counselor_id'), fn($q) => $q->where('counselor_id', $request->counselor_id))
            ->when($request->filled('counsel_type_id'), fn($q) => $q->where('counsel_type_id', $request->counsel_type_id))
            ->orderByDesc('cancelled_at');
    }

    public function cancellationsData(Request $request)
    {
        $rows = $this->cancellationsQuery($request)->get()->map(fn($a) => [
            'appointment_date' => $a->appointment_date->format('M j, Y'),
            'counselee'        => $a->counselee->full_name ?? 'Deleted Counsellee',
            'counselor'        => $a->counselor->full_name ?? 'Deleted Counselor',
            'counsel_type'     => $a->counselType->name ?? '—',
            'cancelled_by'     => ucfirst($a->cancelled_by ?? '—'),
            'cancelled_at'     => optional($a->cancelled_at)->format('M j, Y g:i A') ?? '—',
        ]);

        return response()->json(['data' => $rows]);
    }

    public function cancellationsExport(Request $request)
    {
        $rows = $this->cancellationsQuery($request)->get()->map(fn($a) => [
            $a->appointment_date->format('Y-m-d'),
            $a->counselee->full_name ?? 'Deleted Counsellee',
            $a->counselor->full_name ?? 'Deleted Counselor',
            $a->counselType->name ?? '—',
            ucfirst($a->cancelled_by ?? '—'),
            optional($a->cancelled_at)->format('Y-m-d H:i') ?? '—',
        ]);

        return $this->streamCsv('cancellations.csv',
            ['Original Date', 'Counsellee', 'Counselor', 'Counselling Area', 'Cancelled By', 'Cancelled At'], $rows);
    }

    // =========================================================================
    // 4. Counselor Performance Report
    // =========================================================================

    public function counselorPerformance()
    {
        return view('admin.reports.counselor-performance', [
            'counselors'   => $this->counselorOptions(),
            'counselTypes' => $this->counselTypeOptions(),
        ]);
    }

    private function counselorPerformanceRows(Request $request)
    {
        [$from, $to] = $this->dateRange($request);
        $minSessions = (int) $request->input('min_sessions', 0);

        $counselors = Counselor::where('status', '!=', 'deleted')
            ->when($request->filled('counselor_id'), fn($q) => $q->where('id', $request->counselor_id))
            ->withCount([
                'appointments as total_sessions' => function ($q) use ($from, $to, $request) {
                    $q->when($from, fn($qq) => $qq->whereDate('appointment_date', '>=', $from))
                      ->when($to, fn($qq) => $qq->whereDate('appointment_date', '<=', $to))
                      ->when($request->filled('counsel_type_id'), fn($qq) => $qq->where('counsel_type_id', $request->counsel_type_id));
                },
                'appointments as completed_sessions' => function ($q) use ($from, $to, $request) {
                    $q->where('status', 'completed')
                      ->when($from, fn($qq) => $qq->whereDate('appointment_date', '>=', $from))
                      ->when($to, fn($qq) => $qq->whereDate('appointment_date', '<=', $to))
                      ->when($request->filled('counsel_type_id'), fn($qq) => $qq->where('counsel_type_id', $request->counsel_type_id));
                },
                'appointments as cancelled_sessions' => function ($q) use ($from, $to, $request) {
                    $q->where('status', 'cancelled')
                      ->when($from, fn($qq) => $qq->whereDate('appointment_date', '>=', $from))
                      ->when($to, fn($qq) => $qq->whereDate('appointment_date', '<=', $to))
                      ->when($request->filled('counsel_type_id'), fn($qq) => $qq->where('counsel_type_id', $request->counsel_type_id));
                },
            ])
            ->withAvg('feedbackReceived as avg_rating', 'rating')
            ->withCount('feedbackReceived as feedback_count')
            ->having('total_sessions', '>=', $minSessions)
            ->orderByDesc('total_sessions')
            ->get();

        return $counselors;
    }

    public function counselorPerformanceData(Request $request)
    {
        $rows = $this->counselorPerformanceRows($request)->map(fn($c) => [
            'counselor'         => $c->full_name,
            'total_sessions'    => $c->total_sessions,
            'completed_sessions' => $c->completed_sessions,
            'cancelled_sessions' => $c->cancelled_sessions,
            'completion_rate'   => $c->total_sessions > 0 ? round($c->completed_sessions / $c->total_sessions * 100, 1) . '%' : '—',
            'avg_rating'        => $c->avg_rating ? round($c->avg_rating, 2) . ' / 5' : '—',
            'feedback_count'    => $c->feedback_count,
        ]);

        return response()->json(['data' => $rows]);
    }

    public function counselorPerformanceExport(Request $request)
    {
        $rows = $this->counselorPerformanceRows($request)->map(fn($c) => [
            $c->full_name,
            $c->total_sessions,
            $c->completed_sessions,
            $c->cancelled_sessions,
            $c->total_sessions > 0 ? round($c->completed_sessions / $c->total_sessions * 100, 1) . '%' : '—',
            $c->avg_rating ? round($c->avg_rating, 2) : '—',
            $c->feedback_count,
        ]);

        return $this->streamCsv('counselor-performance.csv',
            ['Counselor', 'Total Sessions', 'Completed', 'Cancelled', 'Completion Rate', 'Avg Rating', 'Feedback Count'], $rows);
    }

    // =========================================================================
    // 5. Counselor Utilization Report
    // =========================================================================

    public function counselorUtilization()
    {
        return view('admin.reports.counselor-utilization', [
            'counselors' => $this->counselorOptions(),
            'cities'     => $this->cityOptions(),
        ]);
    }

    private function counselorUtilizationRows(Request $request): array
    {
        [$from, $to] = $this->dateRange($request);
        $from = $from ?: Carbon::today()->subDays(29);
        $to   = $to ?: Carbon::today()->endOfDay();

        $counselors = Counselor::where('status', '!=', 'deleted')
            ->when($request->filled('counselor_id'), fn($q) => $q->where('id', $request->counselor_id))
            ->when($request->filled('city_id'), fn($q) => $q->where('city_id', $request->city_id))
            ->with('availabilities', 'leaves')
            ->get();

        $results = [];

        foreach ($counselors as $counselor) {
            $availableHours = 0.0;
            $cursor = $from->copy();
            while ($cursor->lte($to)) {
                $dateStr = $cursor->toDateString();
                $onLeave = $counselor->leaves->contains(fn($l) => $l->start_date->toDateString() <= $dateStr && $l->end_date->toDateString() >= $dateStr);
                if (!$onLeave) {
                    $dayName = $cursor->format('l');
                    foreach ($counselor->availabilities->where('day', $dayName) as $slot) {
                        $availableHours += Carbon::createFromTimeString($slot->end_time)->diffInMinutes(Carbon::createFromTimeString($slot->start_time)) / 60;
                    }
                }
                $cursor->addDay();
            }

            $bookedMinutes = Appointment::where('counselor_id', $counselor->id)
                ->whereIn('status', ['confirmed', 'completed'])
                ->whereDate('appointment_date', '>=', $from)
                ->whereDate('appointment_date', '<=', $to)
                ->get()
                ->sum(fn($a) => Carbon::createFromTimeString($a->end_time)->diffInMinutes(Carbon::createFromTimeString($a->start_time)));

            $bookedHours = round($bookedMinutes / 60, 1);
            $availableHours = round($availableHours, 1);

            $results[] = [
                'counselor'       => $counselor->full_name,
                'available_hours' => $availableHours,
                'booked_hours'    => $bookedHours,
                'utilization'     => $availableHours > 0 ? round($bookedHours / $availableHours * 100, 1) : 0,
            ];
        }

        return $results;
    }

    public function counselorUtilizationData(Request $request)
    {
        $rows = collect($this->counselorUtilizationRows($request))->map(fn($r) => [
            'counselor'       => $r['counselor'],
            'available_hours' => $r['available_hours'] . ' hrs',
            'booked_hours'    => $r['booked_hours'] . ' hrs',
            'utilization'     => $r['utilization'] . '%',
        ]);

        return response()->json(['data' => $rows]);
    }

    public function counselorUtilizationExport(Request $request)
    {
        $rows = collect($this->counselorUtilizationRows($request))->map(fn($r) => [
            $r['counselor'], $r['available_hours'], $r['booked_hours'], $r['utilization'] . '%',
        ]);

        return $this->streamCsv('counselor-utilization.csv',
            ['Counselor', 'Available Hours', 'Booked Hours', 'Utilization %'], $rows);
    }

    // =========================================================================
    // 6. Feedback & Ratings Report
    // =========================================================================

    public function feedbackRatings()
    {
        return view('admin.reports.feedback-ratings', [
            'counselors'   => $this->counselorOptions(),
            'counselTypes' => $this->counselTypeOptions(),
        ]);
    }

    private function feedbackRatingsQuery(Request $request)
    {
        [$from, $to] = $this->dateRange($request);

        return AppointmentFeedback::with(['counselee', 'counselor', 'appointment.counselType'])
            ->when($from, fn($q) => $q->whereDate('submitted_at', '>=', $from))
            ->when($to, fn($q) => $q->whereDate('submitted_at', '<=', $to))
            ->when($request->filled('counselor_id'), fn($q) => $q->where('counselor_id', $request->counselor_id))
            ->when($request->filled('counsel_type_id'), fn($q) => $q->whereHas('appointment', fn($qq) => $qq->where('counsel_type_id', $request->counsel_type_id)))
            ->when($request->filled('rating'), fn($q) => $q->where('rating', '<=', $request->rating))
            ->when($request->input('has_comments') === '1', fn($q) => $q->whereNotNull('comments')->where('comments', '!=', ''))
            ->when($request->input('has_comments') === '0', fn($q) => $q->where(fn($qq) => $qq->whereNull('comments')->orWhere('comments', '')))
            ->orderByDesc('submitted_at');
    }

    public function feedbackRatingsData(Request $request)
    {
        $rows = $this->feedbackRatingsQuery($request)->get()->map(fn($f) => [
            'date'         => optional($f->submitted_at)->format('M j, Y') ?? '—',
            'counselee'    => $f->counselee->full_name ?? '—',
            'counselor'    => $f->counselor->full_name ?? '—',
            'counsel_type' => $f->appointment->counselType->name ?? '—',
            'rating'       => $f->rating . ' / 5',
            'comments'     => $f->comments ? \Illuminate\Support\Str::limit($f->comments, 80) : '—',
        ]);

        return response()->json(['data' => $rows]);
    }

    public function feedbackRatingsExport(Request $request)
    {
        $rows = $this->feedbackRatingsQuery($request)->get()->map(fn($f) => [
            optional($f->submitted_at)->format('Y-m-d') ?? '—',
            $f->counselee->full_name ?? '—',
            $f->counselor->full_name ?? '—',
            $f->appointment->counselType->name ?? '—',
            $f->rating,
            $f->comments ?? '',
        ]);

        return $this->streamCsv('feedback-ratings.csv',
            ['Date', 'Counsellee', 'Counselor', 'Counselling Area', 'Rating', 'Comments'], $rows);
    }

    // =========================================================================
    // 7. Complaints Report
    // =========================================================================

    public function complaints()
    {
        return view('admin.reports.complaints', [
            'counselors' => $this->counselorOptions(),
        ]);
    }

    private function complaintsQuery(Request $request)
    {
        [$from, $to] = $this->dateRange($request);

        return Complaint::with(['counselee', 'counselor'])
            ->when($from, fn($q) => $q->whereDate('created_at', '>=', $from))
            ->when($to, fn($q) => $q->whereDate('created_at', '<=', $to))
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->when($request->filled('filed_by'), fn($q) => $q->where('filed_by', $request->filed_by))
            ->when($request->filled('counselor_id'), fn($q) => $q->where('counselor_id', $request->counselor_id))
            ->orderByDesc('created_at');
    }

    public function complaintsData(Request $request)
    {
        $rows = $this->complaintsQuery($request)->get()->map(fn($c) => [
            'reference'   => $c->reference_number,
            'filed_by'    => ucfirst($c->filed_by) . ' — ' . ($c->filed_by === 'counselor' ? ($c->counselor->full_name ?? '—') : ($c->counselee->full_name ?? '—')),
            'subject'     => $c->subject,
            'status'      => ucfirst(str_replace('_', ' ', $c->status)),
            'filed_on'    => $c->created_at->format('M j, Y'),
            'resolution_time' => $c->resolved_at ? $c->created_at->diffInHours($c->resolved_at) . ' hrs' : '—',
        ]);

        return response()->json(['data' => $rows]);
    }

    public function complaintsExport(Request $request)
    {
        $rows = $this->complaintsQuery($request)->get()->map(fn($c) => [
            $c->reference_number,
            ucfirst($c->filed_by),
            $c->filed_by === 'counselor' ? ($c->counselor->full_name ?? '—') : ($c->counselee->full_name ?? '—'),
            $c->subject,
            ucfirst(str_replace('_', ' ', $c->status)),
            $c->created_at->format('Y-m-d'),
            $c->resolved_at ? $c->created_at->diffInHours($c->resolved_at) : '',
        ]);

        return $this->streamCsv('complaints.csv',
            ['Reference', 'Filed By', 'Filed By Name', 'Subject', 'Status', 'Filed On', 'Resolution Time (hrs)'], $rows);
    }

    // =========================================================================
    // 8. Counselling Area Demand Report
    // =========================================================================

    public function counsellingDemand()
    {
        return view('admin.reports.counselling-demand', [
            'states' => $this->stateOptions(),
            'counselTypes' => $this->counselTypeOptions(),
        ]);
    }

    private function counsellingDemandRows(Request $request)
    {
        [$from, $to] = $this->dateRange($request);

        $counselTypes = CounselType::when($request->filled('counsel_type_id'), fn($q) => $q->where('id', $request->counsel_type_id))
            ->orderBy('name')
            ->withCount([
                'appointments as booking_count' => function ($q) use ($from, $to, $request) {
                    $q->when($from, fn($qq) => $qq->whereDate('appointment_date', '>=', $from))
                      ->when($to, fn($qq) => $qq->whereDate('appointment_date', '<=', $to))
                      ->when($request->filled('state_id'), fn($qq) => $qq->whereHas('counselee', fn($qqq) => $qqq->where('state_id', $request->state_id)));
                },
            ])
            ->withCount('counselors as counselor_count')
            ->orderByDesc('booking_count')
            ->get();

        return $counselTypes;
    }

    public function counsellingDemandData(Request $request)
    {
        $rows = $this->counsellingDemandRows($request)->map(fn($t) => [
            'counsel_type'    => $t->name,
            'booking_count'   => $t->booking_count,
            'counselor_count' => $t->counselor_count,
            'ratio'           => $t->counselor_count > 0 ? round($t->booking_count / $t->counselor_count, 1) : ($t->booking_count > 0 ? '∞' : '—'),
        ]);

        return response()->json(['data' => $rows]);
    }

    public function counsellingDemandExport(Request $request)
    {
        $rows = $this->counsellingDemandRows($request)->map(fn($t) => [
            $t->name, $t->booking_count, $t->counselor_count,
            $t->counselor_count > 0 ? round($t->booking_count / $t->counselor_count, 1) : ($t->booking_count > 0 ? 'inf' : '0'),
        ]);

        return $this->streamCsv('counselling-area-demand.csv',
            ['Counselling Area', 'Bookings', 'Counselors Offering', 'Bookings per Counselor'], $rows);
    }

    // =========================================================================
    // 9. City Coverage Gap Report
    // =========================================================================

    public function cityCoverageGap()
    {
        return view('admin.reports.city-coverage-gap', [
            'states'    => $this->stateOptions(),
            'countries' => $this->countryOptions(),
        ]);
    }

    private function cityCoverageGapRows(Request $request)
    {
        $minCounselees = (int) $request->input('min_counselees', 1);

        $counseleeCityCounts = Counselee::where('status', '!=', 'deleted')
            ->whereNotNull('city_id')
            ->when($request->filled('state_id'), fn($q) => $q->where('state_id', $request->state_id))
            ->when($request->filled('country_id'), fn($q) => $q->where('country_id', $request->country_id))
            ->select('city_id', DB::raw('COUNT(*) as counselee_count'))
            ->groupBy('city_id')
            ->having('counselee_count', '>=', $minCounselees)
            ->with('city.state', 'city.country')
            ->get();

        $citiesWithInPersonCounselor = Counselor::where('status', 'active')
            ->whereIn('mode', ['In person', 'Both'])
            ->pluck('city_id')
            ->unique();

        return $counseleeCityCounts
            ->filter(fn($row) => !$citiesWithInPersonCounselor->contains($row->city_id) && $row->city)
            ->sortByDesc('counselee_count')
            ->values();
    }

    public function cityCoverageGapData(Request $request)
    {
        $rows = $this->cityCoverageGapRows($request)->map(fn($r) => [
            'city'             => $r->city->name ?? '—',
            'state'            => $r->city->state->name ?? '—',
            'country'          => $r->city->country->name ?? '—',
            'counselee_count'  => $r->counselee_count,
        ]);

        return response()->json(['data' => $rows]);
    }

    public function cityCoverageGapExport(Request $request)
    {
        $rows = $this->cityCoverageGapRows($request)->map(fn($r) => [
            $r->city->name ?? '—', $r->city->state->name ?? '—', $r->city->country->name ?? '—', $r->counselee_count,
        ]);

        return $this->streamCsv('city-coverage-gap.csv',
            ['City', 'State', 'Country', 'Counselees Without Local Counselor'], $rows);
    }

    // =========================================================================
    // 10. Registration & Growth Report
    // =========================================================================

    public function registrations()
    {
        return view('admin.reports.registrations', [
            'states'    => $this->stateOptions(),
            'countries' => $this->countryOptions(),
        ]);
    }

    private function registrationsRows(Request $request)
    {
        [$from, $to] = $this->dateRange($request);
        $role = $request->input('role');

        $applyCommon = function ($q) use ($from, $to, $request) {
            return $q->when($from, fn($qq) => $qq->whereDate('created_at', '>=', $from))
                ->when($to, fn($qq) => $qq->whereDate('created_at', '<=', $to))
                ->when($request->filled('status'), fn($qq) => $qq->where('status', $request->status))
                ->when($request->filled('country_id'), fn($qq) => $qq->where('country_id', $request->country_id))
                ->when($request->filled('state_id'), fn($qq) => $qq->where('state_id', $request->state_id));
        };

        $rows = collect();

        if ($role !== 'counselee') {
            $rows = $rows->merge(
                $applyCommon(Counselor::query())->get()->map(fn($c) => (object) [
                    'role' => 'Counselor', 'full_name' => $c->full_name, 'email' => $c->email,
                    'status' => $c->status, 'created_at' => $c->created_at,
                ])
            );
        }

        if ($role !== 'counselor') {
            $rows = $rows->merge(
                $applyCommon(Counselee::query())->get()->map(fn($c) => (object) [
                    'role' => 'Counselee', 'full_name' => $c->full_name, 'email' => $c->email,
                    'status' => $c->status, 'created_at' => $c->created_at,
                ])
            );
        }

        return $rows->sortByDesc('created_at')->values();
    }

    public function registrationsData(Request $request)
    {
        $rows = $this->registrationsRows($request)->map(fn($r) => [
            'role'       => $r->role,
            'name'       => $r->full_name,
            'email'      => $r->email,
            'status'     => ucfirst($r->status),
            'registered' => $r->created_at->format('M j, Y'),
        ]);

        return response()->json(['data' => $rows]);
    }

    public function registrationsExport(Request $request)
    {
        $rows = $this->registrationsRows($request)->map(fn($r) => [
            $r->role, $r->full_name, $r->email, ucfirst($r->status), $r->created_at->format('Y-m-d'),
        ]);

        return $this->streamCsv('registrations.csv',
            ['Role', 'Name', 'Email', 'Status', 'Registered On'], $rows);
    }

    // =========================================================================
    // 11. Donations Report
    // =========================================================================

    public function donations()
    {
        return view('admin.reports.donations');
    }

    private function donationsQuery(Request $request)
    {
        [$from, $to] = $this->dateRange($request);

        return Donation::with('counselee')
            ->when($from, fn($q) => $q->whereDate('created_at', '>=', $from))
            ->when($to, fn($q) => $q->whereDate('created_at', '<=', $to))
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->when($request->filled('min_amount'), fn($q) => $q->where('amount', '>=', $request->min_amount))
            ->when($request->filled('max_amount'), fn($q) => $q->where('amount', '<=', $request->max_amount))
            ->orderByDesc('created_at');
    }

    public function donationsData(Request $request)
    {
        $rows = $this->donationsQuery($request)->get()->map(fn($d) => [
            'donor'      => $d->donor_display_name,
            'amount'     => $d->currency . ' ' . number_format((float) $d->amount, 2),
            'status'     => ucfirst($d->status),
            'reference'  => $d->payment_reference ?: '—',
            'date'       => $d->created_at->format('M j, Y'),
        ]);

        return response()->json(['data' => $rows]);
    }

    public function donationsExport(Request $request)
    {
        $rows = $this->donationsQuery($request)->get()->map(fn($d) => [
            $d->donor_display_name, $d->currency, number_format((float) $d->amount, 2), ucfirst($d->status),
            $d->payment_reference ?: '', $d->created_at->format('Y-m-d'),
        ]);

        return $this->streamCsv('donations.csv',
            ['Donor', 'Currency', 'Amount', 'Status', 'Payment Reference', 'Date'], $rows);
    }

    // =========================================================================
    // 12. Counselor Leave Calendar
    // =========================================================================

    public function leaveCalendar()
    {
        return view('admin.reports.leave-calendar', [
            'counselors' => $this->counselorOptions(),
        ]);
    }

    private function leaveCalendarQuery(Request $request)
    {
        [$from, $to] = $this->dateRange($request);

        return CounselorLeave::with('counselor')
            ->when($from, fn($q) => $q->whereDate('end_date', '>=', $from))
            ->when($to, fn($q) => $q->whereDate('start_date', '<=', $to))
            ->when($request->filled('counselor_id'), fn($q) => $q->where('counselor_id', $request->counselor_id))
            ->when($request->filled('created_by'), fn($q) => $q->where('created_by', $request->created_by))
            ->orderByDesc('start_date');
    }

    public function leaveCalendarData(Request $request)
    {
        $rows = $this->leaveCalendarQuery($request)->get()->map(fn($l) => [
            'counselor'  => $l->counselor->full_name ?? 'Deleted Counselor',
            'start_date' => $l->start_date->format('M j, Y'),
            'end_date'   => $l->end_date->format('M j, Y'),
            'days'       => $l->start_date->diffInDays($l->end_date) + 1,
            'reason'     => $l->reason ?: '—',
            'filed_by'   => ucfirst($l->created_by),
        ]);

        return response()->json(['data' => $rows]);
    }

    public function leaveCalendarExport(Request $request)
    {
        $rows = $this->leaveCalendarQuery($request)->get()->map(fn($l) => [
            $l->counselor->full_name ?? 'Deleted Counselor',
            $l->start_date->format('Y-m-d'),
            $l->end_date->format('Y-m-d'),
            $l->start_date->diffInDays($l->end_date) + 1,
            $l->reason ?: '',
            ucfirst($l->created_by),
        ]);

        return $this->streamCsv('counselor-leave-calendar.csv',
            ['Counselor', 'Start Date', 'End Date', 'Days', 'Reason', 'Filed By'], $rows);
    }
}
