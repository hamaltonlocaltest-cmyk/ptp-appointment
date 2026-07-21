@extends('admin.layouts.app')
@section('title', 'Book Appointment')
@section('page-title', 'Book Appointment')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.appointments.index') }}">Appointments</a></li>
    <li class="breadcrumb-item active">Book Appointment</li>
@endsection

@section('content')

<div class="row">
<div class="col-lg-12">

    <p class="text-muted mb-4" style="font-size:13px;">
        Book an appointment on behalf of a counsellee. A counselor will be automatically matched based on availability.
    </p>

    @if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
    </div>
    @endif

    @if($counselees->isEmpty() || $counselTypes->isEmpty())
    <div class="card text-center p-5">
        <i class="fas fa-exclamation-circle fa-3x mb-3" style="color:#ccc;"></i>
        <h5 class="text-muted">Not enough data to book an appointment</h5>
        <p class="text-muted">You need at least one active counsellee and one active counselling type.</p>
    </div>
    @else

    <div class="d-flex align-items-center mb-4" id="progressSteps">
        @foreach(['Counsellee','Type','Date','Time','Confirm'] as $i => $label)
        <div class="d-flex align-items-center" style="flex:1;">
            <div class="step-indicator {{ $i === 0 ? 'active' : '' }}" data-step="{{ $i + 1 }}">
                <div class="step-circle">{{ $i + 1 }}</div>
                <div class="step-label">{{ $label }}</div>
            </div>
            @if($i < 4)
            <div class="step-line" style="flex:1; height:2px; background:#e0e4ec; margin:0 4px; margin-bottom:16px;"></div>
            @endif
        </div>
        @endforeach
    </div>

    <style>
    .step-indicator { text-align:center; min-width:60px; }
    .step-circle {
        width:34px; height:34px; border-radius:50%; background:#e0e4ec; color:#999;
        font-weight:700; font-size:14px; display:inline-flex; align-items:center;
        justify-content:center; margin-bottom:4px; transition:.2s;
    }
    .step-label { font-size:11px; color:#aaa; font-weight:600; }
    .step-indicator.active .step-circle { background:#1f8582; color:#fff; }
    .step-indicator.active .step-label { color:#1f8582; }
    .step-indicator.done .step-circle { background:#009643; color:#fff; }
    .step-indicator.done .step-label { color:#009643; }

    .section-card { border-radius:12px; border:1px solid #e0e4ec; margin-bottom:20px; }
    .section-card .section-header {
        padding:14px 20px; background:#f8f9fc; border-bottom:1px solid #e0e4ec;
        border-radius:12px 12px 0 0; font-weight:700; color:#1f8582; font-size:14px;
    }
    .section-card .section-body { padding:20px; }
    .back-link { background:none; border:none; color:#888; font-size:12px; font-weight:600; cursor:pointer; padding:2px 0; }
    .back-link:hover { color:#1f8582; }

    .counselee-list { max-height:320px; overflow-y:auto; border:1px solid #e0e4ec; border-radius:8px; background:#fff }
    .counselee-row {
        display:flex; align-items:center; gap:12px; padding:10px 14px; cursor:pointer;
        border-bottom:1px solid #f0f2f5; transition:.15s;
    }
    .counselee-row:last-child { border-bottom:none; }
    .counselee-row:hover { background:#f8f9fc; }
    .counselee-row.selected { background:#e6f4f4; }
    .counselee-row .cname { font-weight:600; font-size:14px; color:#087a7f; }
    .counselee-row .cemail { font-size:14px; color:#333; }

    .type-card {
        border:2px solid #e0e4ec; border-radius:10px; padding:16px; cursor:pointer;
        transition:.2s; text-align:center; height:100%; display:block; width:100%; background:#fff;
    }
    .type-card:hover { border-color:#1f8582; background:#f0f8f8; }
    .type-card.selected { border-color:#1f8582; background:#f0f8f8; }
    .type-card .type-icon { font-size:28px; margin-bottom:8px; color: #087a7f; }
    .type-card .type-name { font-weight:700; font-size:14px; color:#333; }

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
    .slot-btn:hover { border-color:#1f8582; color:#1f8582; }
    .slot-btn.selected { border-color:#1f8582; background:#1f8582; color:#fff; }

    .skeleton-box {
        background:linear-gradient(90deg, #eef0f5 25%, #f7f8fb 37%, #eef0f5 63%);
        background-size:400% 100%; animation:skeleton-shimmer 1.4s ease infinite; border-radius:8px;
    }
    @keyframes skeleton-shimmer { 0% { background-position:100% 50%; } 100% { background-position:0 50%; } }
    </style>

    <form id="bookingForm" action="{{ route('admin.appointments.store') }}" method="POST">
        @csrf
        <input type="hidden" name="counselee_id" id="hiddenCounselee">
        <input type="hidden" name="counsel_type_id" id="hiddenTypeId">
        <input type="hidden" name="appointment_date" id="hiddenDate">
        <input type="hidden" name="start_time" id="hiddenStart">
        <input type="hidden" name="end_time" id="hiddenEnd">
        <input type="hidden" name="counselor_id" id="hiddenCounselor">

       
        <div id="step1" class="section-card">
            <div class="section-header"><i class="fas fa-user mr-2"></i>Step 1 — Select Counsellee</div>
            <div class="section-body">
                <div class="search-box mb-3">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" id="counseleeSearch" class="form-control" placeholder="Search by name or email...">
                </div>
                <div class="counselee-list" id="counseleeList">
                    @foreach($counselees as $c)
                    <div class="counselee-row" data-id="{{ $c->id }}" data-name="{{ $c->full_name }}" data-email="{{ $c->email }}"
                         data-search="{{ strtolower($c->full_name . ' ' . $c->email) }}" onclick="selectCounselee(this)">
                        <div class="avatar-circle" style="width:32px; height:32px; font-size:12px; ">
                            {{ strtoupper(substr($c->first_name,0,1)) }}
                        </div>
                        <div>
                            <div class="cname">{{ $c->full_name }}</div>
                            <div class="cemail">{{ $c->email }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div id="noCounseleeMsg" class="text-center text-muted py-3" style="display:none;">No counsellees match your search.</div>
            </div>
        </div>

      
        <div id="step2" class="section-card" style="display:none;">
            <div class="section-header"><i class="fas fa-hand-holding-heart mr-2"></i>Step 2 — Counselling Area</div>
            <div class="section-body">
                <button type="button" class="back-link mb-3" onclick="goToStep(1)"><i class="fas fa-arrow-left mr-1"></i>Change counsellee</button>
                <div class="row">
                    @foreach($counselTypes as $type)
                    <div class="col-md-4 col-6 mb-3">
                        <button type="button" class="type-card" data-id="{{ $type->id }}" data-name="{{ $type->name }}" onclick="selectType(this)">
                            <div class="type-icon" >
                                <i class="{{ $type->icon ?: 'fas fa-heart' }}"></i>
                            </div>
                            <div class="type-name">{{ $type->name }}</div>
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

      
        <div id="step3" class="section-card" style="display:none;">
            <div class="section-header"><i class="fas fa-calendar-alt mr-2"></i>Step 3 — Pick a Date</div>
            <div class="section-body">
                <button type="button" class="back-link mb-3" onclick="goToStep(2)"><i class="fas fa-arrow-left mr-1"></i>Change counselling area</button>
                <div id="dateLoading" class="date-grid">
                    @for ($i = 0; $i < 10; $i++)
                    <div class="skeleton-box" style="width:84px; height:52px;"></div>
                    @endfor
                </div>
                <div id="dateGrid" class="date-grid" style="display:none;"></div>
                <div id="noDateMsg" class="text-center text-muted py-4" style="display:none;">No available dates found for this counselling area.</div>
            </div>
        </div>

       
        <div id="step4" class="section-card" style="display:none;">
            <div class="section-header"><i class="fas fa-clock mr-2"></i>Step 4 — Pick a Time Slot</div>
            <div class="section-body">
                <button type="button" class="back-link mb-3" onclick="goToStep(3)"><i class="fas fa-arrow-left mr-1"></i>Change date</button>
                <div id="slotLoading" class="row" style="display:none;">
                    @for ($i = 0; $i < 6; $i++)
                    <div class="col-md-4 col-6 mb-2"><div class="skeleton-box" style="height:42px;"></div></div>
                    @endfor
                </div>
                <div id="slotGrid" style="display:none;"></div>
                <div id="noSlotMsg" class="text-center text-muted py-4" style="display:none;">No available slots for this date.</div>
                <div id="counselorInfo" class="mt-3" style="display:none;">
                    <div style="display:inline-flex; align-items:center; gap:8px; background:#e6f4f4; border:1px solid #b3dbda; color:#1f8582; border-radius:8px; padding:10px 16px; font-size:13px; font-weight:600;">
                        <i class="fas fa-user-tie"></i>
                        <span>Assigned counselor: <strong id="counselorName"></strong></span>
                    </div>
                </div>
            </div>
        </div>

       
        <div id="step5" class="section-card" style="display:none;">
            <div class="section-header"><i class="fas fa-sticky-note mr-2"></i>Step 5 — Notes &amp; Confirm <span class="text-muted" style="font-weight:400;">(optional)</span></div>
            <div class="section-body">
                <button type="button" class="back-link mb-3" onclick="goToStep(4)"><i class="fas fa-arrow-left mr-1"></i>Change time slot</button>
                <textarea name="notes" rows="4" class="form-control" maxlength="500" placeholder="Any notes for the counselor..."></textarea>

                <div class="mt-4 p-3" style="background:#f8f9fc; border-radius:8px; border:1px solid #e0e4ec;">
                    <div style="font-weight:700; color:#1f8582; margin-bottom:10px;"><i class="fas fa-info-circle mr-2"></i>Booking Summary</div>
                    <div class="row" style="font-size:13px;">
                        <div class="col-6 mb-2"><span class="text-muted">Counsellee:</span> <strong id="summaryCounselee"></strong></div>
                        <div class="col-6 mb-2"><span class="text-muted">Type:</span> <strong id="summaryType"></strong></div>
                        <div class="col-6 mb-2"><span class="text-muted">Date:</span> <strong id="summaryDate"></strong></div>
                        <div class="col-6 mb-2"><span class="text-muted">Time:</span> <strong id="summaryTime"></strong></div>
                        <div class="col-6 mb-2"><span class="text-muted">Counselor:</span> <strong id="summaryCounselor"></strong></div>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary" style="border-radius:7px; padding:10px 30px; font-weight:700; font-size:15px;">
                        <i class="fas fa-calendar-check mr-2"></i> Confirm Booking
                    </button>
                </div>
            </div>
        </div>
    </form>

    @endif
</div>
</div>
@endsection

@push('scripts')
<script>
const ROUTES = {
    dates: '{{ route("admin.appointments.dates") }}',
    slots: '{{ route("admin.appointments.slots") }}',
    csrf:  '{{ csrf_token() }}',
};

let state = { counseleeId: null, typeId: null, typeName: null, date: null, start: null, end: null, counselorId: null, counselorName: null, maxStep: 1 };

function setStep(n) {
    document.querySelectorAll('#progressSteps .step-indicator').forEach((el, i) => {
        el.classList.remove('active', 'done');
        if (i + 1 < n) el.classList.add('done');
        if (i + 1 === n) el.classList.add('active');
    });
}

function goToStep(n) {
    if (n < 1 || n > state.maxStep) return;
    [1, 2, 3, 4, 5].forEach(i => {
        document.getElementById('step' + i).style.display = (i === n) ? '' : 'none';
    });
    setStep(n);
    document.getElementById('step' + n).scrollIntoView({ behavior: 'smooth', block: 'start' });
}

// Step 1: counsellee search + select
document.getElementById('counseleeSearch').addEventListener('input', function () {
    const term = this.value.toLowerCase();
    let visible = 0;
    document.querySelectorAll('.counselee-row').forEach(row => {
        const match = row.dataset.search.includes(term);
        row.style.display = match ? '' : 'none';
        if (match) visible++;
    });
    document.getElementById('noCounseleeMsg').style.display = visible === 0 ? '' : 'none';
});

function selectCounselee(row) {
    document.querySelectorAll('.counselee-row').forEach(r => r.classList.remove('selected'));
    row.classList.add('selected');
    state.counseleeId = row.dataset.id;
    document.getElementById('hiddenCounselee').value = state.counseleeId;
    document.getElementById('summaryCounselee').textContent = row.dataset.name;
    state.maxStep = 2;
    goToStep(2);
}

// Step 2: type selection
function selectType(card) {
    document.querySelectorAll('.type-card').forEach(c => c.classList.remove('selected'));
    card.classList.add('selected');
    state.typeId = card.dataset.id;
    state.typeName = card.dataset.name;
    document.getElementById('hiddenTypeId').value = state.typeId;
    document.getElementById('summaryType').textContent = state.typeName;

    state.date = state.start = state.end = state.counselorId = state.counselorName = null;
    document.getElementById('step4').style.display = 'none';
    document.getElementById('step5').style.display = 'none';
    state.maxStep = 3;
    loadDates();
}

function loadDates() {
    document.getElementById('step3').style.display = '';
    document.getElementById('dateLoading').style.display = 'flex';
    document.getElementById('dateGrid').style.display = 'none';
    document.getElementById('noDateMsg').style.display = 'none';
    setStep(3);
    document.getElementById('step3').scrollIntoView({ behavior: 'smooth', block: 'start' });

    fetch(ROUTES.dates, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': ROUTES.csrf },
        body: JSON.stringify({ counsel_type_id: state.typeId, counselee_id: state.counseleeId }),
    })
    .then(r => r.json())
    .then(data => {
        document.getElementById('dateLoading').style.display = 'none';
        if (!data.dates || data.dates.length === 0) {
            document.getElementById('noDateMsg').textContent = data.message || 'No available dates found for this counselling area.';
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
    state.date = date;
    document.getElementById('hiddenDate').value = date;
    document.getElementById('summaryDate').textContent = new Date(date + 'T00:00:00').toLocaleDateString('en', { weekday:'long', year:'numeric', month:'long', day:'numeric' });

    state.start = state.end = state.counselorId = state.counselorName = null;
    document.getElementById('step5').style.display = 'none';
    state.maxStep = 4;
    loadSlots(date);
}

function loadSlots(date) {
    document.getElementById('step4').style.display = '';
    document.getElementById('slotLoading').style.display = '';
    document.getElementById('slotGrid').style.display = 'none';
    document.getElementById('noSlotMsg').style.display = 'none';
    document.getElementById('counselorInfo').style.display = 'none';
    setStep(4);
    document.getElementById('step4').scrollIntoView({ behavior: 'smooth', block: 'start' });

    fetch(ROUTES.slots, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': ROUTES.csrf },
        body: JSON.stringify({ counsel_type_id: state.typeId, date, counselee_id: state.counseleeId }),
    })
    .then(r => r.json())
    .then(data => {
        document.getElementById('slotLoading').style.display = 'none';
        if (!data.slots || data.slots.length === 0) {
            document.getElementById('noSlotMsg').textContent = data.message || 'No available slots for this date.';
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
    state.start = slot.start_time; state.end = slot.end_time;
    state.counselorId = slot.counselor_id; state.counselorName = slot.counselor_name;
    document.getElementById('hiddenStart').value = slot.start_time;
    document.getElementById('hiddenEnd').value = slot.end_time;
    document.getElementById('hiddenCounselor').value = slot.counselor_id;
    document.getElementById('counselorName').textContent = slot.counselor_name;
    document.getElementById('counselorInfo').style.display = '';
    state.maxStep = 5;

    setTimeout(() => {
        document.getElementById('step5').style.display = '';
        document.getElementById('summaryTime').textContent = fmtTime(slot.start_time) + ' – ' + fmtTime(slot.end_time);
        document.getElementById('summaryCounselor').textContent = slot.counselor_name;
        setStep(5);
        document.getElementById('step5').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }, 250);
}

function fmtTime(t) {
    const [h, m] = t.split(':').map(Number);
    const p = h >= 12 ? 'PM' : 'AM';
    return `${h % 12 === 0 ? 12 : h % 12}:${m.toString().padStart(2,'0')} ${p}`;
}

const bookingForm = document.getElementById('bookingForm');
if (bookingForm) {
    bookingForm.addEventListener('submit', function (e) {
        const btn = this.querySelector('button[type="submit"]');
        if (btn.disabled) { e.preventDefault(); return; }
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
    });
}

// Pre-select counsellee if passed via ?counselee_id=
@if($selectedCounseleeId)
const preselected = document.querySelector('.counselee-row[data-id="{{ $selectedCounseleeId }}"]');
if (preselected) selectCounselee(preselected);
@endif
</script>
@endpush
