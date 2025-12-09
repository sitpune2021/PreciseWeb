@extends('layouts.header')

@section('content')

<style>
    /* Smooth Fade & Scale Animation */
    @keyframes pop {
        0% { transform: scale(0.8); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }

    @keyframes slideUp {
        0% { opacity: 0; transform: translateY(20px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    .success-card {
        max-width: 450px;
        margin: auto;
        padding: 30px;
        border-radius: 15px;
        background: #ffffff;
        box-shadow: 0 5px 25px rgba(0,0,0,0.1);
        animation: slideUp 0.5s ease-out;
    }

    .success-icon {
        font-size: 65px;
        color: #28a745;
        animation: pop 0.4s ease-in-out;
    }

    .amount-box {
        background: #f0f3ff;
        padding: 15px;
        border-radius: 12px;
        margin-top: 15px;
        font-size: 16px;
        animation: slideUp 0.6s ease-out;
    }

    .amount-box p {
        margin-bottom: 6px;
        font-weight: 500;
    }

    .btn-success {
        animation: slideUp 0.7s ease-out;
        border-radius: 30px;
        padding: 8px 25px;
    }
</style>

<div class="main-content">
    <div class="page-content">
        <div class="container d-flex justify-content-center" style="margin-top: 60px; margin-bottom: 60px;">

            <div class="success-card text-center">

                <div class="success-icon">✓</div>

                <h2 class="mt-3 mb-2">Payment Successful</h2>
                <p class="text-muted">Your payment has been processed.</p>

                <div class="amount-box text-start mx-auto">
                    <p><strong>Payment ID:</strong> {{ $razorpay_payment_id }}</p>
                    <p><strong>Order ID:</strong> {{ $razorpay_order_id }}</p>
                    <p><strong>Amount:</strong> ₹{{ $amount }}</p>
                </div>

                <a href="{{ url('/') }}" class="btn btn-success mt-4">
                    Go to Dashboard
                </a>

            </div>

        </div>
    </div>
</div>

@endsection
