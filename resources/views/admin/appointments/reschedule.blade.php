@extends('admin.layouts.app')
@section('title', 'Reschedule Appointment')
@section('page-title', 'Reschedule Appointment')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.appointments.index') }}">Appointments</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.appointments.show', $appointment) }}">#{{ $appointment->id }}</a></li>
    <li class="breadcrumb-item active">Reschedule</li>
@endsection

@section('content')
<div class="row">
<div class="col-lg-12">

    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between flex-wrap">
                <div>
                    <div style="font-size:11px; text-transform:uppercase; letter-spacing:.5px; color:#9e9e9e; font-weight:700;">Currently Scheduled</div>
                    <div style="font-weight:700; font-size:16px; color:#1a1a2e; margin-top:2px;">{{ $appointment->counselType->name }}</div>
                    <div style="font-size:13px; color:#555; margin-top:2px;">
                        <i class="fas fa-user mr-1"></i>{{ $appointment->counselee->full_name }}
                        &nbsp;·&nbsp;
                        <i class="fas fa-user-tie mr-1"></i>{{ $appointment->counselor->full_name }}
                        &nbsp;·&nbsp;
                        <i class="fas fa-calendar-alt mr-1"></i>{{ $appointment->appointment_date->format('l, M j, Y') }}
                        &nbsp;·&nbsp;
                        <i class="fas fa-clock mr-1"></i>{{ $appointment->formatted_time }}
                    </div>
                </div>
                <a href="{{ route('admin.appointments.show', $appointment) }}" class="btn btn-light btn-sm" style="border-radius:20px; border:1px solid #e0e4ec;">
                    <i class="fas fa-arrow-left mr-1"></i> Back
                </a>
            </div>
        </div>
    </div>

    @if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
    </div>
    @endif

    <style>
    .date-grid { display:flex; flex-wrap:wrap; gap:8px; }
    .date-btn {
        padding:8px 14px; border:2px solid #e0e4ec; border-radius:8px; cursor:pointer;
        font-size:13px; font-weight:600; color:#555; background:#fff; text-align:center; min-width:80px;
    }
    .date-btn:hover { border-color:#1f8582; color:#1f8582; }
    .date-btn.selected { border-color:#1f8582; background:#1f8582; color:#fff; }

    .slot-btn {
        padding:10px 16px; border:2px solid #e0e4ec; border-radius:8px; cursor:pointer;
        font-size:13px; font-weight:600; color:#555; background:#fff; text-align:center; width:100%;
    }
    .slot-btn:hover { border-color:#4a148c; color:#4a148c; }
    .slot-btn.selected { border-color:#4a148c; background:#4a148c; color:#fff; }

    .section-card { border-radius:12px; border:1px solid #e0e4ec; margin-bottom:20px; }
    .section-card .section-header {
        padding:14px 20px; background:#f8f9fc; border-bottom:1px solid #e0e4ec;
        border-radius:12px 12px 0 0; font-weight:700; color:#1f8582; font-size:14px;
    }
    .section-card .section-body { padding:20px; }
    .skeleton-box {
        background:linear-gradient(90deg, #eef0f5 25%, #f7f8fb 37%, #eef0f5 63%);
        background-size:400% 100%; animation:skeleton-shimmer 1.4s ease infinite; border-radius:8px;
    }
    @keyframes skeleton-shimmer { 0% { background-position:100% 50%; } 100% { background-position:0 50%; } }
    </style>

    <form id="rescheduleForm" action="{{ route('admin.appointments.reschedule', $appointment) }}" method="POST">
        @csrf
        <input type="hidden" name="appointment_date" id="hiddenDate">
        <input type="hidden" name="start_time" id="hiddenStart">
        <input type="hidden" name="end_time" id="hiddenEnd">
        <input type="hidden" name="counselor_id" id="hiddenCounselor">

        <div class="section-card">
            <div class="section-header"><i class="fas fa-calendar-alt mr-2"></i>Step 1 — Pick a New Date</div>
            <div class="section-body">
                <div id="dateLoading" class="date-grid">
                    @for ($i = 0; $i < 10; $i++)
                    <div class="skeleton-box" style="width:84px; height:52px;"></div>
                    @endfor
                </div>
                <div id="dateGrid" class="date-grid" style="display:none;"></div>
                <div id="noDateMsg" class="text-center text-muted py-4" style="display:none;">
                    No available dates found for this counselling area.
                </div>
            </div>
        </div>

        <div id="step2" class="section-card" style="display:none;">
            <div class="section-header"><i class="fas fa-clock mr-2"></i>Step 2 — Pick a New Time</div>
            <div class="section-body">
                <div id="slotLoading" class="row" style="display:none;">
                    @for ($i = 0; $i < 6; $i++)
                    <div class="col-md-4 col-6 mb-2"><div class="skeleton-box" style="height:42px;"></div></div>
                    @endfor
                </div>
                <div id="slotGrid" style="display:none;"></div>
                <div id="noSlotMsg" class="text-center text-muted py-4" style="display:none;">
                    No available slots for this date. Try another date.
                </div>
                <div id="counselorInfo" class="mt-3" style="display:none;">
                    <div style="display:inline-flex; align-items:center; gap:8px; background:#e6f4f4; border:1px solid #b3dbda; color:#1f8582; border-radius:8px; padding:10px 16px; font-size:13px; font-weight:600;">
                        <i class="fas fa-user-tie"></i>
                        <span>Assigned counselor: <strong id="counselorName"></strong></span>
                    </div>
                </div>
            </div>
        </div>

        <div id="step3" class="section-card" style="display:none;">
            <div class="section-header"><i class="fas fa-comment-alt mr-2"></i>Reason <span class="text-muted" style="font-weight:400;">(optional)</span></div>
            <div class="section-body">
                <textarea name="reason" rows="3" class="form-control" maxlength="500"
                          placeholder="Briefly note why this appointment is being rescheduled..."></textarea>
                <div class="mt-4 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary" style="border-radius:7px; padding:10px 30px; font-weight:700; font-size:15px;">
                        <i class="fas fa-calendar-check mr-2"></i> Confirm Reschedule
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
</div>
@endsection

@push('scripts')
<script>
const ROUTES = {
    dates: '{{ route("admin.appointments.reschedule.dates", $appointment) }}',
    slots: '{{ route("admin.appointments.reschedule.slots", $appointment) }}',
    csrf:  '{{ csrf_token() }}',
};

function loadDates() {
    document.getElementById('dateLoading').style.display = 'flex';
    document.getElementById('dateGrid').style.display = 'none';
    document.getElementById('noDateMsg').style.display = 'none';

    fetch(ROUTES.dates, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': ROUTES.csrf },
    })
    .then(r => r.json())
    .then(data => {
        document.getElementById('dateLoading').style.display = 'none';
        if (!data.dates || data.dates.length === 0) {
            document.getElementById('noDateMsg').style.display = '';
            return;
        }
        const grid = document.getElementById('dateGrid');
        grid.innerHTML = '';
        data.dates.forEach(d => {
            const dt  = new Date(d + 'T00:00:00');
            const day = dt.toLocaleDateString('en', { weekday: 'short' });
            const num = dt.toLocaleDateString('en', { month: 'short', day: 'numeric' });
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'date-btn';
            btn.innerHTML = `<div style="font-size:10px;">${day}</div><div>${num}</div>`;
            btn.onclick = () => selectDate(btn, d);
            grid.appendChild(btn);
        });
        grid.style.display = 'flex';
    });
}

function selectDate(btn, date) {
    document.querySelectorAll('.date-btn').forEach(b => b.classList.remove('selected'));
    btn.classList.add('selected');
    document.getElementById('hiddenDate').value = date;
    document.getElementById('step3').style.display = 'none';
    loadSlots(date);
}

function loadSlots(date) {
    const step2 = document.getElementById('step2');
    step2.style.display = '';
    document.getElementById('slotLoading').style.display = '';
    document.getElementById('slotGrid').style.display = 'none';
    document.getElementById('noSlotMsg').style.display = 'none';
    document.getElementById('counselorInfo').style.display = 'none';
    step2.scrollIntoView({ behavior: 'smooth', block: 'start' });

    fetch(ROUTES.slots, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': ROUTES.csrf },
        body: JSON.stringify({ date }),
    })
    .then(r => r.json())
    .then(data => {
        document.getElementById('slotLoading').style.display = 'none';
        if (!data.slots || data.slots.length === 0) {
            document.getElementById('noSlotMsg').style.display = '';
            return;
        }
        const grid = document.getElementById('slotGrid');
        grid.innerHTML = '';
        const row = document.createElement('div');
        row.className = 'row w-100 mx-0';
        data.slots.forEach(slot => {
            const col = document.createElement('div');
            col.className = 'col-md-4 col-6 mb-2';
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'slot-btn';
            btn.textContent = slot.display;
            btn.onclick = () => selectSlot(btn, slot);
            col.appendChild(btn);
            row.appendChild(col);
        });
        grid.appendChild(row);
        grid.style.display = '';
    });
}

function selectSlot(btn, slot) {
    document.querySelectorAll('.slot-btn').forEach(b => b.classList.remove('selected'));
    btn.classList.add('selected');
    document.getElementById('hiddenStart').value = slot.start_time;
    document.getElementById('hiddenEnd').value = slot.end_time;
    document.getElementById('hiddenCounselor').value = slot.counselor_id;
    document.getElementById('counselorName').textContent = slot.counselor_name;
    document.getElementById('counselorInfo').style.display = '';
    document.getElementById('step3').style.display = '';
    document.getElementById('step3').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

const rescheduleForm = document.getElementById('rescheduleForm');
if (rescheduleForm) {
    rescheduleForm.addEventListener('submit', function (e) {
        const btn = this.querySelector('button[type="submit"]');
        if (btn.disabled) { e.preventDefault(); return; }
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
    });
}

loadDates();
</script>
@endpush
