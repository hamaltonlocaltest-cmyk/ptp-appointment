@extends('counselee.layouts.app')
@section('title', 'Book an Appointment')
@section('page-title', 'Book an Appointment')
@section('breadcrumb')
    <li class="breadcrumb-item active">Book Appointment</li>
@endsection

@section('content')
<div class="container py-4">

 
    <div class="d-flex align-items-center mb-4">
        <div>
            <h4 class="mb-0" style="color:#1a1a2e; font-weight:700;">Book an Appointment</h4>
            <p class="text-muted mb-0" style="font-size:13px;">
                Select your counselling area and preferred time. A counselor will be automatically matched for you.
            </p>
        </div>
    </div>

    @if($errors->any())
    <div class="alert alert-danger" role="alert">
        @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
    </div>
    @endif

    @if($availableTypes->isEmpty())
    <div class="row justify-content-center">
    <div class="col-lg-8">
    <div class="card text-center p-5">
        <i class="fas fa-calendar-times fa-3x mb-3" style="color:#ccc;"></i>
        <h5 class="text-muted">No Counselling Areas Available</h5>
        <p class="text-muted">There are currently no counselors available for your registered counselling areas. Please check back later.</p>
        <a href="{{ route('counselee.dashboard') }}" class="btn btn-primary mt-2">Back to Dashboard</a>
    </div>
    </div>
    </div>
    @else

<div class="row">
<div class="col-lg-8">

   
    <div class="d-flex align-items-center mb-4" id="progressSteps">
        @foreach(['Choose Type','Pick Date','Pick Time','Confirm'] as $i => $label)
        <div class="d-flex align-items-center" style="flex:1;">
            <div class="step-indicator {{ $i === 0 ? 'active' : '' }}" data-step="{{ $i + 1 }}"
                 role="button" tabindex="0" aria-label="Go to step {{ $i + 1 }}: {{ $label }}"
                 onclick="goToStep({{ $i + 1 }})" onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();goToStep({{ $i + 1 }});}">
                <div class="step-circle">{{ $i + 1 }}</div>
                <div class="step-label">{{ $label }}</div>
            </div>
            @if($i < 3)
            <div class="step-line" style="flex:1; height:2px; background:#e0e4ec; margin:0 4px; margin-bottom:16px;"></div>
            @endif
        </div>
        @endforeach
    </div>

    <style>
    .step-indicator { text-align:center; min-width:70px; cursor:default; outline:none; }
    .step-indicator.clickable { cursor:pointer; }
    .step-indicator.clickable:hover .step-circle,
    .step-indicator:focus-visible .step-circle { transform:scale(1.1); box-shadow:0 0 0 3px rgba(211,4,4,.15); }
    .step-circle {
        width:34px; height:34px; border-radius:50%; background:#e0e4ec; color:#999;
        font-weight:700; font-size:14px; display:inline-flex; align-items:center;
        justify-content:center; margin-bottom:4px; transition:.2s;
    }
    .step-label { font-size:11px; color:#aaa; font-weight:600; }
    .step-indicator.active .step-circle { background:#D30404; color:#fff; }
    .step-indicator.active .step-label { color:#D30404; }
    .step-indicator.done .step-circle { background:#009643; color:#fff; }
    .step-indicator.done .step-label { color:#009643; }

    .type-card {
        border:2px solid #e0e4ec; border-radius:10px; padding:16px; cursor:pointer;
        transition:.2s; text-align:center; height:100%; display:block; width:100%;
        background:#fff; font:inherit;
    }
    .type-card:hover, .type-card:focus-visible { border-color:#D30404; background:#fff5f5; outline:none; }
    .type-card.selected { border-color:#D30404; background:#fff5f5; }
    .type-card .type-icon { font-size:28px; margin-bottom:8px; }
    .type-card .type-name { font-weight:700; font-size:14px; color:#333; }

    .date-grid { display:flex; flex-wrap:wrap; gap:8px; }
    .date-btn {
        padding:8px 14px; border:2px solid #e0e4ec; border-radius:8px; cursor:pointer;
        font-size:13px; font-weight:600; color:#555; background:#fff; transition:.2s;
        text-align:center; min-width:80px; font:inherit;
    }
    .date-btn:hover, .date-btn:focus-visible { border-color:#D30404; color:#D30404; outline:none; }
    .date-btn.selected { border-color:#D30404; background:#D30404; color:#fff; }

    .slot-btn {
        padding:10px 16px; border:2px solid #e0e4ec; border-radius:8px; cursor:pointer;
        font-size:13px; font-weight:600; color:#555; background:#fff; transition:.2s; text-align:center;
        width:100%; font:inherit;
    }
    .slot-btn:hover, .slot-btn:focus-visible { border-color:#4a148c; color:#4a148c; outline:none; }
    .slot-btn.selected { border-color:#4a148c; background:#4a148c; color:#fff; }

    .counselor-badge {
        display:inline-flex; align-items:center; gap:8px; background:#f3e9ff;
        border:1px solid #d4a8f0; color:#4a148c; border-radius:8px; padding:10px 16px;
        font-size:13px; font-weight:600;
    }
    .section-card { border-radius:12px; border:1px solid #e0e4ec; margin-bottom:20px; }
    .section-card .section-header {
        padding:14px 20px; background:#f8f9fc; border-bottom:1px solid #e0e4ec;
        border-radius:12px 12px 0 0; font-weight:700; color:#1a237e; font-size:14px;
        display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:6px;
    }
    .section-card .section-body { padding:20px; }
    .back-link {
        background:none; border:none; color:#888; font-size:12px; font-weight:600; cursor:pointer;
        padding:2px 0;
    }
    .back-link:hover { color:#D30404; }

    .skeleton-box {
        background:linear-gradient(90deg, #eef0f5 25%, #f7f8fb 37%, #eef0f5 63%);
        background-size:400% 100%; animation:skeleton-shimmer 1.4s ease infinite; border-radius:8px;
    }
    @keyframes skeleton-shimmer { 0% { background-position:100% 50%; } 100% { background-position:0 50%; } }

    .section-card.step-enter { animation:stepFadeIn .35s ease; }
    @keyframes stepFadeIn { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:translateY(0); } }

    .date-btn { position:relative; }
    .date-tag {
        position:absolute; top:-8px; left:50%; transform:translateX(-50%);
        background:#009643; color:#fff; font-size:9px; font-weight:700; padding:1px 6px;
        border-radius:8px; white-space:nowrap; letter-spacing:.3px;
    }

    .slot-group-title {
        font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:#9e9e9e;
        display:flex; align-items:center; gap:8px; margin:16px 0 10px; width:100%;
    }
    .slot-group-title:first-child { margin-top:0; }
    .slot-group-title::after { content:''; flex:1; height:1px; background:#eee; }
    .slot-group-title i { color:#c7c7d1; }

    .fetch-error-box { text-align:center; padding:24px 0; }
    .retry-btn {
        border:1.5px solid #D30404; color:#D30404; background:#fff; border-radius:20px;
        font-size:12px; font-weight:700; padding:6px 18px; margin-top:10px; cursor:pointer; transition:.2s;
    }
    .retry-btn:hover { background:#D30404; color:#fff; }

    .result-count { font-size:11px; color:#aaa; font-weight:600; margin-bottom:12px; display:block; }

    .summary-row.jumpable { cursor:pointer; border-radius:8px; margin:0 -8px; padding:12px 8px; }
    .summary-row.jumpable:hover { background:#f8f9fc; }

    .summary-sidebar { position:sticky; top:20px; border:1px solid #e0e4ec; border-radius:12px; overflow:hidden; background:#fff; }
    .summary-sidebar .summary-header { background:#1a1a2e; color:#fff; padding:14px 18px; font-weight:700; font-size:14px; }
    .summary-sidebar .summary-body { padding:8px 18px 4px; }
    .summary-row { display:flex; align-items:flex-start; gap:10px; padding:12px 0; border-bottom:1px dashed #eee; }
    .summary-row:last-child { border-bottom:none; }
    .summary-row .summary-icon {
        width:28px; height:28px; border-radius:7px; background:#f3e9ff; color:#4a148c;
        display:flex; align-items:center; justify-content:center; font-size:12px; flex-shrink:0; margin-top:1px;
    }
    .summary-row .summary-text { min-width:0; }
    .summary-row .summary-label { font-size:10px; text-transform:uppercase; letter-spacing:.5px; color:#aaa; font-weight:700; }
    .summary-row .summary-value { font-size:13px; font-weight:700; color:#333; word-break:break-word; }
    .summary-row .summary-value.muted { color:#ccc; font-weight:600; }
    .summary-hint { font-size:11px; color:#aaa; text-align:center; padding:10px 18px 16px; }
    </style>

    <form id="bookingForm" action="{{ route('counselee.appointments.preview') }}" method="POST">
        @csrf
        <input type="hidden" name="counsel_type_id" id="hiddenTypeId">
        <input type="hidden" name="appointment_date" id="hiddenDate">
        <input type="hidden" name="start_time" id="hiddenStart">
        <input type="hidden" name="end_time" id="hiddenEnd">
        <input type="hidden" name="counselor_id" id="hiddenCounselor">

        
        <div id="step1" class="section-card">
            <div class="section-header">
                <span><i class="fas fa-hand-holding-heart mr-2" style="color:#D30404;"></i>Step 1 — What would you like counselling for?</span>
            </div>
            <div class="section-body">
                <div class="row">
                    @foreach($availableTypes as $type)
                    <div class="col-md-4 col-6 mb-3">
                        <button type="button" class="type-card" data-id="{{ $type->id }}" data-name="{{ $type->name }}"
                                aria-pressed="false" onclick="selectType(this)">
                            <div class="type-icon" style="color:{{ $type->color ?: '#D30404' }}">
                                <i class="{{ $type->icon ?: 'fas fa-heart' }}"></i>
                            </div>
                            <div class="type-name">{{ $type->name }}</div>
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

       
        <div id="step2" class="section-card" style="display:none;">
            <div class="section-header">
                <span><i class="fas fa-calendar-alt mr-2" style="color:#D30404;"></i>Step 2 — Pick a Date</span>
                <span style="font-weight:400; font-size:12px; color:#999;">Next 30 available days shown</span>
            </div>
            <div class="section-body" aria-live="polite">
                <button type="button" class="back-link mb-3" onclick="goToStep(1)"><i class="fas fa-arrow-left mr-1"></i>Change counselling type</button>
                <div id="dateLoading" class="date-grid">
                    @for ($i = 0; $i < 10; $i++)
                    <div class="skeleton-box" style="width:84px; height:52px;"></div>
                    @endfor
                </div>
                <div id="dateGrid" class="date-grid" style="display:none;"></div>
                <div id="noDateMsg" class="text-center text-muted py-4" style="display:none;">
                    <i class="fas fa-calendar-times fa-2x mb-2" style="color:#ddd; display:block;"></i>
                    No available dates found for this counselling type.<br>Please check back later.
                </div>
            </div>
        </div>

       
        <div id="step3" class="section-card" style="display:none;">
            <div class="section-header">
                <span><i class="fas fa-clock mr-2" style="color:#D30404;"></i>Step 3 — Pick a Time Slot</span>
            </div>
            <div class="section-body" aria-live="polite">
                <button type="button" class="back-link mb-3" onclick="goToStep(2)"><i class="fas fa-arrow-left mr-1"></i>Change date</button>
                <div id="slotLoading" class="row" style="display:none;">
                    @for ($i = 0; $i < 6; $i++)
                    <div class="col-md-4 col-6 mb-2"><div class="skeleton-box" style="height:42px;"></div></div>
                    @endfor
                </div>
                <div id="slotGrid" style="display:none;"></div>
                <div id="noSlotMsg" class="text-center text-muted py-4" style="display:none;">
                    <i class="fas fa-clock fa-2x mb-2" style="color:#ddd; display:block;"></i>
                    No available slots for this date.<br>Try choosing a different date.
                </div>
              
                <div id="counselorInfo" class="mt-3" style="display:none;">
                    <div class="counselor-badge">
                        <i class="fas fa-user-tie"></i>
                        <span>Your counselor: <strong id="counselorName"></strong></span>
                        <span class="badge badge-success ml-1" id="counselorMode"></span>
                    </div>
                </div>
            </div>
        </div>

       
        <div id="step4" class="section-card" style="display:none;">
            <div class="section-header">
                <span><i class="fas fa-sticky-note mr-2" style="color:#D30404;"></i>Step 4 — Any notes for your counselor? <span class="text-muted" style="font-weight:400;">(optional)</span></span>
            </div>
            <div class="section-body">
                <button type="button" class="back-link mb-3" onclick="goToStep(3)"><i class="fas fa-arrow-left mr-1"></i>Change time slot</button>
                <textarea name="notes" id="notesInput" rows="4" class="form-control" maxlength="500"
                          placeholder="Briefly describe what you'd like to discuss in your session..."></textarea>
                <div class="text-muted d-flex justify-content-between" style="font-size:12px; margin-top:4px;">
                    <span>Optional — helps your counselor prepare</span>
                    <span id="notesCount">0 / 500</span>
                </div>

                <div class="mt-4 p-3 d-lg-none" style="background:#f8f9fc; border-radius:8px; border:1px solid #e0e4ec;" id="bookingSummary">
                    <div style="font-weight:700; color:#1a237e; margin-bottom:10px;">
                        <i class="fas fa-info-circle mr-2"></i> Booking Summary
                    </div>
                    <div class="row" style="font-size:13px;">
                        <div class="col-6 mb-2"><span class="text-muted">Type:</span> <strong id="summaryType"></strong></div>
                        <div class="col-6 mb-2"><span class="text-muted">Date:</span> <strong id="summaryDate"></strong></div>
                        <div class="col-6 mb-2"><span class="text-muted">Time:</span> <strong id="summaryTime"></strong></div>
                        <div class="col-6 mb-2"><span class="text-muted">Counselor:</span> <strong id="summaryCounselor"></strong></div>
                        <div class="col-6 mb-2"><span class="text-muted">Mode:</span> <strong id="summaryMode"></strong></div>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-between">
                    <button type="button" class="btn btn-light" onclick="goToStep(3)"
                            style="border-radius:7px; border:1px solid #e0e4ec; padding:10px 22px;">
                        <i class="fas fa-arrow-left mr-1"></i> Back
                    </button>
                    <button type="submit" class="btn"
                            style="background:#D30404; color:#fff; border-radius:7px; padding:10px 30px; font-weight:700; font-size:15px;">
                        <i class="fas fa-calendar-check mr-2"></i> Confirm Booking
                    </button>
                </div>
            </div>
        </div>

    </form>
</div>


<div class="col-lg-4 d-none d-lg-block">
    <div class="summary-sidebar">
        <div class="summary-header"><i class="fas fa-clipboard-list mr-2"></i>Your Booking</div>
        <div class="summary-body">
            <div class="summary-row jumpable" data-jump="1" role="button" tabindex="0" aria-label="Jump to Step 1: Choose Type">
                <div class="summary-icon"><i class="fas fa-hand-holding-heart"></i></div>
                <div class="summary-text">
                    <div class="summary-label">Type</div>
                    <div class="summary-value muted" id="sideType">Not selected yet</div>
                </div>
            </div>
            <div class="summary-row jumpable" data-jump="2" role="button" tabindex="0" aria-label="Jump to Step 2: Pick Date">
                <div class="summary-icon"><i class="fas fa-calendar-alt"></i></div>
                <div class="summary-text">
                    <div class="summary-label">Date</div>
                    <div class="summary-value muted" id="sideDate">—</div>
                </div>
            </div>
            <div class="summary-row jumpable" data-jump="3" role="button" tabindex="0" aria-label="Jump to Step 3: Pick Time">
                <div class="summary-icon"><i class="fas fa-clock"></i></div>
                <div class="summary-text">
                    <div class="summary-label">Time</div>
                    <div class="summary-value muted" id="sideTime">—</div>
                </div>
            </div>
            <div class="summary-row">
                <div class="summary-icon"><i class="fas fa-user-tie"></i></div>
                <div class="summary-text">
                    <div class="summary-label">Counselor</div>
                    <div class="summary-value muted" id="sideCounselor">—</div>
                </div>
            </div>
        </div>
        <div class="summary-hint">Click a step above to jump back and change a selection.</div>
    </div>
</div>

</div>
</div>
@endif

@endsection

@push('scripts')
<script>
const ROUTES = {
    dates: '{{ route("counselee.appointments.dates") }}',
    slots: '{{ route("counselee.appointments.slots") }}',
    csrf:  '{{ csrf_token() }}',
};

let state = { typeId: null, typeName: null, date: null, start: null, end: null, counselorId: null, counselorName: null, mode: null, maxStep: 1 };

function setStep(n) {
    document.querySelectorAll('#progressSteps .step-indicator').forEach((el, i) => {
        el.classList.remove('active', 'done');
        if (i + 1 < n) el.classList.add('done');
        if (i + 1 === n) el.classList.add('active');
    });
    updateStepClickability();
}

function updateStepClickability() {
    document.querySelectorAll('#progressSteps .step-indicator').forEach((el) => {
        const n = parseInt(el.dataset.step, 10);
        el.classList.toggle('clickable', n <= state.maxStep);
    });
}


function goToStep(n) {
    if (n < 1 || n > state.maxStep) return;
    [1, 2, 3, 4].forEach(i => {
        const el = document.getElementById('step' + i);
        el.style.display = (i === n) ? '' : 'none';
        if (i === n) {
            el.classList.remove('step-enter');
            void el.offsetWidth; // restart animation
            el.classList.add('step-enter');
        }
    });
    setStep(n);
    document.getElementById('step' + n).scrollIntoView({ behavior: 'smooth', block: 'start' });
}


document.querySelectorAll('.summary-row.jumpable').forEach(row => {
    const jump = () => goToStep(parseInt(row.dataset.jump, 10));
    row.addEventListener('click', jump);
    row.addEventListener('keydown', e => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); jump(); } });
});

function updateSidebar() {
    document.getElementById('sideType').textContent = state.typeName || 'Not selected yet';
    document.getElementById('sideType').classList.toggle('muted', !state.typeName);

    if (state.date) {
        const dt = new Date(state.date + 'T00:00:00');
        document.getElementById('sideDate').textContent = dt.toLocaleDateString('en', { weekday: 'short', month: 'short', day: 'numeric' });
        document.getElementById('sideDate').classList.remove('muted');
    } else {
        document.getElementById('sideDate').textContent = '—';
        document.getElementById('sideDate').classList.add('muted');
    }

    if (state.start && state.end) {
        document.getElementById('sideTime').textContent = fmtTime(state.start) + ' – ' + fmtTime(state.end);
        document.getElementById('sideTime').classList.remove('muted');
    } else {
        document.getElementById('sideTime').textContent = '—';
        document.getElementById('sideTime').classList.add('muted');
    }

    document.getElementById('sideCounselor').textContent = state.counselorName || '—';
    document.getElementById('sideCounselor').classList.toggle('muted', !state.counselorName);
}


function selectType(card) {
    document.querySelectorAll('.type-card').forEach(c => { c.classList.remove('selected'); c.setAttribute('aria-pressed', 'false'); });
    card.classList.add('selected');
    card.setAttribute('aria-pressed', 'true');
    state.typeId   = card.dataset.id;
    state.typeName = card.dataset.name;
    document.getElementById('hiddenTypeId').value = state.typeId;

    // Reset anything chosen after this step
    state.date = state.start = state.end = state.counselorId = state.counselorName = state.mode = null;
    document.getElementById('step3').style.display = 'none';
    document.getElementById('step4').style.display = 'none';
    state.maxStep = 2;

    updateSidebar();
    loadDates();
}

function loadDates() {
    const step2 = document.getElementById('step2');
    step2.style.display = '';
    document.getElementById('dateLoading').style.display = 'flex';
    document.getElementById('dateGrid').style.display = 'none';
    document.getElementById('noDateMsg').style.display = 'none';
    step2.scrollIntoView({ behavior: 'smooth', block: 'start' });
    setStep(2);

    fetch(ROUTES.dates, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': ROUTES.csrf },
        body: JSON.stringify({ counsel_type_id: state.typeId }),
    })
    .then(r => r.json())
    .then(data => {
        document.getElementById('dateLoading').style.display = 'none';
        if (!data.dates || data.dates.length === 0) {
            const text = data.message
                ? data.message
                : 'No available dates found for this counselling type.<br>Please check back later.';
            document.getElementById('noDateMsg').innerHTML = '<i class="fas fa-calendar-times fa-2x mb-2" style="color:#ddd; display:block;"></i>' + text;
            document.getElementById('noDateMsg').style.display = '';
            return;
        }
        const today = new Date(); today.setHours(0,0,0,0);
        const grid = document.getElementById('dateGrid');
        grid.innerHTML = '';
        const count = document.createElement('span');
        count.className = 'result-count';
        count.style.width = '100%';
        count.textContent = data.dates.length + ' date' + (data.dates.length === 1 ? '' : 's') + ' available in the next 30 days';
        grid.appendChild(count);
        data.dates.forEach(d => {
            const dt  = new Date(d + 'T00:00:00');
            const day = dt.toLocaleDateString('en', { weekday: 'short' });
            const num = dt.toLocaleDateString('en', { month: 'short', day: 'numeric' });
            const diffDays = Math.round((dt - today) / 86400000);
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'date-btn';
            btn.dataset.date = d;
            btn.setAttribute('aria-pressed', 'false');
            const tag = diffDays === 1 ? '<span class="date-tag">Tomorrow</span>' : '';
            btn.innerHTML = `${tag}<div style="font-size:10px;color:inherit;">${day}</div><div>${num}</div>`;
            btn.onclick = () => selectDate(btn, d);
            grid.appendChild(btn);
        });
        grid.style.display = 'flex';
    })
    .catch(() => {
        document.getElementById('dateLoading').style.display = 'none';
        document.getElementById('noDateMsg').innerHTML = '<div class="fetch-error-box"><i class="fas fa-exclamation-triangle fa-2x mb-2" style="color:#e5a; display:block;"></i>Something went wrong loading dates.<br><button type="button" class="retry-btn" onclick="loadDates()">Try Again</button></div>';
        document.getElementById('noDateMsg').style.display = '';
    });
}


function selectDate(btn, date) {
    document.querySelectorAll('.date-btn').forEach(b => { b.classList.remove('selected'); b.setAttribute('aria-pressed', 'false'); });
    btn.classList.add('selected');
    btn.setAttribute('aria-pressed', 'true');
    state.date = date;
    document.getElementById('hiddenDate').value = date;

    state.start = state.end = state.counselorId = state.counselorName = state.mode = null;
    document.getElementById('step4').style.display = 'none';
    state.maxStep = 3;

    updateSidebar();
    loadSlots(date);
}

function loadSlots(date) {
    const step3 = document.getElementById('step3');
    step3.style.display = '';
    document.getElementById('slotLoading').style.display = '';
    document.getElementById('slotGrid').style.display = 'none';
    document.getElementById('noSlotMsg').style.display = 'none';
    document.getElementById('counselorInfo').style.display = 'none';
    step3.scrollIntoView({ behavior: 'smooth', block: 'start' });
    setStep(3);

    fetch(ROUTES.slots, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': ROUTES.csrf },
        body: JSON.stringify({ counsel_type_id: state.typeId, date }),
    })
    .then(r => r.json())
    .then(data => {
        document.getElementById('slotLoading').style.display = 'none';
        if (!data.slots || data.slots.length === 0) {
            const text = data.message
                ? data.message
                : 'No available slots for this date.<br>Try choosing a different date.';
            document.getElementById('noSlotMsg').innerHTML = '<i class="fas fa-clock fa-2x mb-2" style="color:#ddd; display:block;"></i>' + text;
            document.getElementById('noSlotMsg').style.display = '';
            return;
        }
        const grid = document.getElementById('slotGrid');
        grid.innerHTML = '';

        const count = document.createElement('span');
        count.className = 'result-count';
        count.textContent = data.slots.length + ' time slot' + (data.slots.length === 1 ? '' : 's') + ' available';
        grid.appendChild(count);

        const groups = { Morning: [], Afternoon: [], Evening: [] };
        data.slots.forEach(slot => {
            const hour = parseInt(slot.start_time.split(':')[0], 10);
            if (hour < 12) groups.Morning.push(slot);
            else if (hour < 17) groups.Afternoon.push(slot);
            else groups.Evening.push(slot);
        });
        const icons = { Morning: 'fa-mug-hot', Afternoon: 'fa-sun', Evening: 'fa-moon' };

        Object.keys(groups).forEach(label => {
            if (groups[label].length === 0) return;
            const title = document.createElement('div');
            title.className = 'slot-group-title';
            title.innerHTML = `<i class="fas ${icons[label]}"></i> ${label}`;
            grid.appendChild(title);

            const row = document.createElement('div');
            row.className = 'row w-100 mx-0';
            groups[label].forEach(slot => {
                const col = document.createElement('div');
                col.className = 'col-md-4 col-6 mb-2';
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'slot-btn';
                btn.setAttribute('aria-pressed', 'false');
                btn.textContent = slot.display;
                btn.onclick = () => selectSlot(btn, slot.start_time, slot.end_time, slot.counselor_id, slot.counselor_name, slot.mode);
                col.appendChild(btn);
                row.appendChild(col);
            });
            grid.appendChild(row);
        });

        grid.style.display = '';
    })
    .catch(() => {
        document.getElementById('slotLoading').style.display = 'none';
        document.getElementById('noSlotMsg').innerHTML = '<div class="fetch-error-box"><i class="fas fa-exclamation-triangle fa-2x mb-2" style="color:#e5a; display:block;"></i>Something went wrong loading time slots.<br><button type="button" class="retry-btn" onclick="loadSlots(state.date)">Try Again</button></div>';
        document.getElementById('noSlotMsg').style.display = '';
    });
}


function selectSlot(btn, start, end, counselorId, counselorName, mode) {
    document.querySelectorAll('.slot-btn').forEach(b => { b.classList.remove('selected'); b.setAttribute('aria-pressed', 'false'); });
    btn.classList.add('selected');
    btn.setAttribute('aria-pressed', 'true');
    state.start = start; state.end = end;
    state.counselorId = counselorId; state.counselorName = counselorName; state.mode = mode;
    document.getElementById('hiddenStart').value = start;
    document.getElementById('hiddenEnd').value = end;
    document.getElementById('hiddenCounselor').value = counselorId;
    document.getElementById('counselorName').textContent = counselorName;
    document.getElementById('counselorMode').textContent = mode;
    document.getElementById('counselorInfo').style.display = '';
    state.maxStep = 4;
    updateSidebar();

    // Show step 4 after short delay for UX
    setTimeout(() => {
        const step4 = document.getElementById('step4');
        step4.style.display = '';
        const dt = new Date(state.date + 'T00:00:00');
        document.getElementById('summaryType').textContent = state.typeName;
        document.getElementById('summaryDate').textContent = dt.toLocaleDateString('en', { weekday:'long', year:'numeric', month:'long', day:'numeric' });
        document.getElementById('summaryTime').textContent = fmtTime(start) + ' – ' + fmtTime(end);
        document.getElementById('summaryCounselor').textContent = counselorName;
        document.getElementById('summaryMode').textContent = mode;
        setStep(4);
        step4.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }, 300);
}

function fmtTime(t) {
    const [h, m] = t.split(':').map(Number);
    const p = h >= 12 ? 'PM' : 'AM';
    return `${h % 12 === 0 ? 12 : h % 12}:${m.toString().padStart(2,'0')} ${p}`;
}


const notesInput = document.getElementById('notesInput');
if (notesInput) {
    notesInput.addEventListener('input', () => {
        document.getElementById('notesCount').textContent = notesInput.value.length + ' / 500';
    });
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

updateSidebar();
</script>
@endpush
