@extends($layout)
@section('title', 'Make a Donation')
@section('page-title', 'Make a Donation')
@section('breadcrumb')
    <li class="breadcrumb-item active">Donate</li>
@endsection

@section('content')
<div class="container py-4">
<div class="row justify-content-center">
<div class="col-lg-6">

    <div class="text-center mb-4">
        <div style="font-size:48px; color:#1f8582; margin-bottom:12px;">
            <i class="fas fa-heart"></i>
        </div>
        <h4 style="font-weight:700; color:#1a1a2e;">Support P2P Counselling</h4>
        <p class="text-muted">Your donation helps us keep counselling accessible for those who need it most.</p>
    </div>

    @if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
    </div>
    @endif

    <div class="card" style="border-radius:14px; border:none; box-shadow:0 4px 20px rgba(0,0,0,.08);">
        <div class="card-body p-4">
            <form action="{{ route('counselee.donations.store') }}" method="POST">
                @csrf

                <label class="d-block text-center mb-2" style="font-weight:700; color:#333; font-size:14px;">
                    Choose an amount (INR)
                </label>

                <div class="amount-grid mb-3">
                    @foreach([100, 250, 500, 1000, 2500, 5000] as $preset)
                    <button type="button" class="amount-btn" data-value="{{ $preset }}">₹{{ $preset }}</button>
                    @endforeach
                </div>

                <div class="form-group mb-3">
                    <label class="form-label" style="font-size:12px; font-weight:600; color:#555; text-transform:uppercase; letter-spacing:.4px;">
                        Or enter a custom amount
                    </label>
                    <input type="number" name="amount" id="amountInput" value="{{ old('amount') }}" min="10" max="200000" step="1" required
                           class="form-control" style="border-radius:8px; border:1.5px solid #e0e4ec; font-size:15px; padding:10px 12px; font-weight:700;"
                           placeholder="Enter amount">
                </div>

                @if(!$counselee)
                <div class="form-group mb-3">
                    <label class="form-label" style="font-size:12px; font-weight:600; color:#555; text-transform:uppercase; letter-spacing:.4px;">Your Name</label>
                    <input type="text" name="donor_name" value="{{ old('donor_name') }}" maxlength="150" required
                           class="form-control" style="border-radius:8px; border:1.5px solid #e0e4ec; font-size:13px; padding:8px 12px;">
                </div>
                <div class="form-group mb-3">
                    <label class="form-label" style="font-size:12px; font-weight:600; color:#555; text-transform:uppercase; letter-spacing:.4px;">Your Email</label>
                    <input type="email" name="donor_email" value="{{ old('donor_email') }}" maxlength="150" required
                           class="form-control" style="border-radius:8px; border:1.5px solid #e0e4ec; font-size:13px; padding:8px 12px;">
                </div>
                @else
                <div class="p-2 mb-3" style="background:#f8f9fc; border-radius:8px; font-size:12.5px; color:#666;">
                    <i class="fas fa-user mr-1"></i> Donating as <strong>{{ $counselee->full_name }}</strong> ({{ $counselee->email }})
                </div>
                @endif

                <div class="mt-4">
                    <button type="submit" class="btn btn-block"
                            style="background:#1f8582; color:#fff; border-radius:7px; padding:12px 30px; font-weight:700; font-size:15px;">
                        <i class="fas fa-lock mr-2"></i> Proceed to Secure Payment
                    </button>
                </div>
                <p class="text-center text-muted mt-3" style="font-size:11.5px;">
                    <i class="fas fa-shield-alt mr-1"></i> Payments are securely processed by Instamojo. We never store your card details.
                </p>
            </form>
        </div>
    </div>

</div>
</div>
</div>

<style>
.amount-grid { display:flex; flex-wrap:wrap; gap:8px; justify-content:center; }
.amount-btn {
    padding:10px 18px; border:2px solid #e0e4ec; border-radius:8px; cursor:pointer;
    font-size:14px; font-weight:700; color:#1f8582; background:#fff; transition:.2s;
}
.amount-btn:hover, .amount-btn.selected { border-color:#1f8582; background:#e8f5e9; }
</style>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.amount-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.amount-btn').forEach(b => b.classList.remove('selected'));
        btn.classList.add('selected');
        document.getElementById('amountInput').value = btn.dataset.value;
    });
});
</script>
@endpush
