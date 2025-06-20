@extends ('layouts.dashboardmain')

@section('container')
<div class="topup-container">
    <div class="topup-header">
        <a href="{{ url()->previous() }}" class="back-button">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h2>Top Up</h2>
    </div>
    
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <div class="topup-content">
        <div class="topup-card">
            <div class="balance-info">
                <span class="balance-label">Balance</span>
                <span class="balance-amount">Rp {{ number_format($customers->customer_balance, 0, ',', '.') }}</span>
            </div>
    
            <form action="/dashboard/topup/update" method="POST" class="topup-form">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="customer_balance">Top Up Amount</label>
                    <input type="number" id="customer_balance" name="customer_balance" 
                           class="form-control @error('customer_balance') is-invalid @enderror" 
                           value="{{ old('customer_balance') }}" required>
                    @error('customer_balance')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="payment-method">Payment Method</label>
                    <input type="text" id="payment-method" value="Bank Transfer" class="form-control" disabled>
                </div>
                
                <button type="submit" class="submit-button">Top Up</button>
            </form>
        </div>
    </div>
</div>
@endsection