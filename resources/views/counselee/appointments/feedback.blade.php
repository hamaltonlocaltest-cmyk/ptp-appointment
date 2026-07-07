@extends('counselee.layouts.app')
@section('title', 'Leave Feedback')
@section('page-title', 'Leave Feedback')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('counselee.appointments.index') }}">My Appointments</a></li>
    <li class="breadcrumb-item active">Feedback</li>
@endsection

@section('content')
<div class="container py-4">
<div class="row justify-content-center">
<div class="col-lg-7">

    <div class="text-center mb-4">
        <div style="font-size:48px; color:#4a148c; margin-bottom:12px;">
            <i class="fas fa-comment-dots"></i>
        </div>
        <h4 style="font-weight:700; color:#1a1a2e;">How was your session?</h4>
        <p class="text-muted">Your feedback helps us and your counselor improve future sessions.</p>
    </div>

    @if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
    </div>
    @endif

    <div class="card" style="border-radius:14px; border:none; box-shadow:0 4px 20px rgba(0,0,0,.08);">
        <div class="card-body p-4">

            <div class="d-flex align-items-center mb-4 p-3"
                 style="background:#f3e9ff; border-radius:10px; border:1px solid #d4a8f0;">
                <div class="mr-3" style="width:48px; height:48px; border-radius:50%; background:#4a148c;
                     display:flex; align-items:center; justify-content:center; color:#fff; font-size:20px; font-weight:700; flex-shrink:0;">
                    {{ strtoupper(substr($appointment->counselor->first_name, 0, 1)) }}
                </div>
                <div>
                    <div style="font-weight:700; color:#4a148c; font-size:15px;">{{ $appointment->counselor->full_name }}</div>
                    <div style="font-size:12px; color:#7b1fa2;">
                        {{ $appointment->counselType->name }} &middot; {{ $appointment->appointment_date->format('M j, Y') }}
                    </div>
                </div>
            </div>

            <form action="{{ route('counselee.appointments.feedback.store', $appointment) }}" method="POST">
                @csrf

                <label class="d-block text-center mb-2" style="font-weight:700; color:#333; font-size:14px;">
                    Rate your experience
                </label>

                <div class="star-rating text-center mb-4" id="starRating">
                    <input type="hidden" name="rating" id="ratingInput" value="">
                    @for ($i = 1; $i <= 5; $i++)
                    <button type="button" class="star-btn" data-value="{{ $i }}" aria-label="{{ $i }} star{{ $i > 1 ? 's' : '' }}">
                        <i class="fas fa-star"></i>
                    </button>
                    @endfor
                    <div class="mt-2" id="ratingLabel" style="font-size:13px; color:#9e9e9e; font-weight:600; min-height:18px;"></div>
                </div>

                <div class="form-group">
                    <label class="form-label" style="font-size:12px; font-weight:600; color:#555; text-transform:uppercase; letter-spacing:.4px;">
                        Comments <span class="text-muted" style="font-weight:400; text-transform:none;">(optional)</span>
                    </label>
                    <textarea name="comments" rows="4" maxlength="1000" class="form-control"
                              style="border-radius:8px; border:1.5px solid #e0e4ec; font-size:13px; padding:10px 14px;"
                              placeholder="Tell us what went well or what could be better...">{{ old('comments') }}</textarea>
                </div>

                <div class="mt-4 d-flex justify-content-between">
                    <a href="{{ route('counselee.appointments.index') }}" class="btn btn-light"
                       style="border-radius:7px; border:1px solid #e0e4ec; padding:10px 22px;">
                        <i class="fas fa-arrow-left mr-1"></i> Back
                    </a>
                    <button type="submit" class="btn" id="submitFeedbackBtn" disabled
                            style="background:#4a148c; color:#fff; border-radius:7px; padding:10px 30px; font-weight:700; font-size:15px; opacity:.5;">
                        <i class="fas fa-paper-plane mr-2"></i> Submit Feedback
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>
</div>
</div>

<style>
.star-rating { display:flex; flex-direction:column; align-items:center; }
.star-btn {
    background:none; border:none; font-size:34px; color:#e0e4ec; cursor:pointer;
    padding:2px 6px; transition:.15s; line-height:1;
}
.star-btn:hover, .star-btn:focus-visible { transform:scale(1.15); outline:none; }
.star-btn.active { color:#f9a825; }
</style>
@endsection

@push('scripts')
<script>
const stars = document.querySelectorAll('.star-btn');
const ratingInput = document.getElementById('ratingInput');
const ratingLabel = document.getElementById('ratingLabel');
const submitBtn = document.getElementById('submitFeedbackBtn');
const labels = { 1: 'Poor', 2: 'Fair', 3: 'Good', 4: 'Very Good', 5: 'Excellent' };

function paintStars(value) {
    stars.forEach(s => s.classList.toggle('active', parseInt(s.dataset.value, 10) <= value));
}

stars.forEach(star => {
    star.addEventListener('mouseenter', () => paintStars(parseInt(star.dataset.value, 10)));
    star.addEventListener('mouseleave', () => paintStars(parseInt(ratingInput.value, 10) || 0));
    star.addEventListener('click', () => {
        const value = parseInt(star.dataset.value, 10);
        ratingInput.value = value;
        ratingLabel.textContent = labels[value];
        paintStars(value);
        submitBtn.disabled = false;
        submitBtn.style.opacity = '1';
    });
});
</script>
@endpush
