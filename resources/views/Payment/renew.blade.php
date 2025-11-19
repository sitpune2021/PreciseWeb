@extends('layouts.header')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="card shadow-sm">
                <div class="card-header">
                    <h4 class="card-title mb-0">Renew Plan</h4>
                </div>

                <div class="card-body plan-section">

                    <form id="renewPlanForm" method="POST" action="{{ route('razorpay.order') }}">
                        @csrf

                        <h5 class="plan-title">Choose a Plan</h5>

                        <div class="row g-4">
                            @foreach($plans as $plan)
                            <div class="col-md-3">
                                <div class="plan-card text-center"
                                    data-id="{{ $plan->id }}"
                                    data-plan="{{ $plan->title }}"
                                    data-price="{{ $plan->price }}"
                                    data-period="{{ $plan->short_text }}">

                                    <h6 class="fw-bold mb-1">{{ $plan->title }}</h6>

                                    <h3 class="fw-bold text-primary">
                                        @if($plan->price == 0)
                                        FREE
                                        @else
                                        ₹{{ $plan->price }}
                                        @endif
                                    </h3>

                                    <p class="text-muted small mb-1">{{ $plan->short_text }}</p>
                                    <p class="text-muted small fst-italic">{{ $plan->description }}</p>

                                    <input type="hidden" name="price" class="priceInput">
                                    <input type="hidden" name="plan" class="planInput">

                                    @if ($client->plan_type == $plan->id)


                                    <button type="submit"class="button" disabled style=" background:#ffffff;color:#880088; border:2px solid #15ec11ff; text-shadow:none;cursor:default;">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 24" style="fill:#880088; width:22px;">
                                            <path d="m18 0 8 12 10-8-4 20H4L0 4l10 8 8-12z"></path>
                                        </svg>
                                        Active
                                    </button>


                                    @else
                                    <button type="button" class="button openModalBtn">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 24">
                                            <path d="m18 0 8 12 10-8-4 20H4L0 4l10 8 8-12z"></path>
                                        </svg>
                                        Select Plan
                                    </button>


                                    @endif
                                </div>
                            </div>
                            @endforeach


                        </div>

                    </form>

                </div>
            </div>
            <!-- Select Plan Modal -->
            <form id="renewPlanForm" method="POST" action="{{ route('razorpay.order') }}">
                @csrf
                <div class="modal fade" id="planModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title">Confirm Plan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <h5 id="modalPlanName"></h5>
                                <input type="hidden" name="price" id="planPrice">
                                <input type="hidden" name="planId" id="planId">

                                <p class="mb-1"><strong>Price:</strong> ₹<span id="modalPlanPrice"></span></p>
                                <p class="mb-1"><strong>Period:</strong> <span id="modalPlanPeriod"></span></p>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" id="payBtn" class="btn btn-primary">Proceed to Payment</button>
                            </div>



                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>
</div>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // All "Select Plan" buttons
        document.querySelectorAll('.openModalBtn').forEach(btn => {
            btn.addEventListener('click', function() {

                let card = this.closest('.plan-card');
                let planId = card.getAttribute('data-id');
                let planName = card.getAttribute('data-plan');
                let planPrice = card.getAttribute('data-price');
                let planPeriod = card.getAttribute('data-period');

                // Set modal values
                document.getElementById('modalPlanName').innerText = planName;
                document.getElementById('modalPlanPrice').innerText = planPrice;
                document.getElementById('planPrice').value = planPrice;
                document.getElementById('planId').value = planId;

                document.getElementById('modalPlanPeriod').innerText = planPeriod;

                // Set form hidden fields
                card.querySelector('.priceInput').value = planPrice;
                card.querySelector('.planInput').value = planName;

                // Open Modal
                var modal = new bootstrap.Modal(document.getElementById('planModal'));
                modal.show();
            });
        });

    });


    $("#payBtnss").click(function() {


        let plan_name = $("#modalPlanName").text();
        let plan_price = $("#modalPlanPrice").text();
        let plan_id = $(".planInput").val();

        $.ajax({
            url: "{{ route('razorpay.order') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                plan_id: plan_id,
                price: plan_price
            },
            success: function(response) {

                if (response.status === "success") {

                    var options = {
                        "key": response.razorpayKey,
                        "amount": response.amount,
                        "currency": "INR",
                        "name": "Plan Purchase",
                        "description": plan_name,
                        "order_id": response.orderId,

                        "handler": function(paymentResponse) {

                            // Submit to success route
                            var form = $('<form>', {
                                'method': 'POST',
                                'action': "{{ route('razorpay.success') }}"
                            });

                            form.append('@csrf');
                            form.append(`<input type="hidden" name="razorpay_payment_id" value="${paymentResponse.razorpay_payment_id}">`);
                            form.append(`<input type="hidden" name="razorpay_order_id" value="${paymentResponse.razorpay_order_id}">`);
                            form.append(`<input type="hidden" name="razorpay_signature" value="${paymentResponse.razorpay_signature}">`);

                            $('body').append(form);
                            form.submit();
                        }
                    };

                    var rzp1 = new Razorpay(options);
                    rzp1.open();
                }
            }
        });

    });
</script>

@endsection