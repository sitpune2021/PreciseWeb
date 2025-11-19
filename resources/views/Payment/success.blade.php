@extends('layouts.header')

@section('content')

<style>
    .success-icon {
        font-size: 90px;
        color: #28a745;
        animation: pop 0.5s ease-in-out;
    }

    @keyframes pop {
        0% { transform: scale(0.5); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }

    .success-card {
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 0 15px #d5d5d5;
        animation: fadeIn 0.7s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .amount-box {
        background: #f4f7ff;
        padding: 15px;
        border-radius: 10px;
        margin-top: 10px;
        font-size: 18px;
    }
</style>

<div class="main-content">
    <div class="page-content">
        <div class="container" style="margin-top: 70px;">
            
            <div class="success-card text-center">

                <div class="success-icon">✓</div>

                <h2 class="mt-3">Payment Successful!</h2>
                <p class="text-muted">Your payment has been processed successfully.</p>

                <div class="amount-box text-start mx-auto" style="max-width: 350px;">
                   <p>Payment ID: {{ $razorpay_payment_id }}</p>
                  <p>Order ID: {{ $razorpay_order_id }}</p>
                  <p>Amount: ₹{{ $amount }}</p>

                </div>

                <a href="{{ url('/') }}" class="btn btn-success mt-4 px-4 py-2">
                    Go to Dashboard
                </a>
            </div>

        </div>
    </div>
</div>

@endsection
