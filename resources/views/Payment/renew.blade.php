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
                                    data-days="{{ $plan->days }}"
                                    data-short="{{ $plan->short_text }}">

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

                                    <button type="submit" class="button" disabled style=" background:#ffffff;color:#880088; border:2px solid #15ec11ff; text-shadow:none;cursor:default;">
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
            
            <form id="planConfirmForm" method="POST" action="{{ route('razorpay.order') }}">
                @csrf
                <div class="modal fade" id="planModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title">Confirm Plan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">

                                <h5 id="modalPlanName" class="fw-bold text-center mb-4 animate__animated animate__zoomIn" style="color: #fff;"></h5>

                                <div class="card payment-card shadow-lg animate__animated animate__fadeInUp">
                                    <div class="card__border"></div>
                                    <div class="card-body p-4">
                                        <table class="table table-borderless mb-0">
                                            <tbody>
                                                <tr class="card__list_item">
                                                    <th class="text-start">Price</th>
                                                    <td class="text-end">₹<span id="modalPlanPrice"></span></td>
                                                </tr>
                                                <tr class="card__list_item">
                                                    <th class="text-start">GST</th>
                                                    <td class="text-end">₹<span id="modalPlanGST"></span></td>
                                                </tr>
                                                <tr class="card__list_item">
                                                    <th class="text-start">Total Amount</th>
                                                    <td class="text-end fw-bold text-success">₹<span id="modalPlanTotal"></span></td>
                                                </tr>
                                                <tr class="card__list_item">
                                                    <th class="text-start">Period</th>
                                                    <td class="text-end"><span id="modalPlanPeriod"></span></td>
                                                </tr>
                                                <tr class="card__list_item">    
                                                    <th class="text-start">Expiry Date</th>
                                                    <td class="text-end"><span id="modalPlanExpiry"></span></td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <button class="button mt-3 w-100">Proceed to Payment</button>
                                    </div>
                                    <input type="hidden" name="price" id="planPrice">
                                    <input type="hidden" name="planId" id="planId">
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

    document.querySelectorAll('.openModalBtn').forEach(btn => {
        btn.addEventListener('click', function() {

            let card = this.closest('.plan-card');
            let planId = card.getAttribute('data-id');
            let planName = card.getAttribute('data-plan');
            let planPrice = parseFloat(card.getAttribute('data-price'));
            let planShort = card.getAttribute('data-short');

            let gst = Math.round(planPrice * 0.18);
            let total = planPrice + gst;

            let planStartDate = new Date();

            let expiryDate = new Date(planStartDate);

            let period = planShort.toLowerCase().replace(/\s+/g, '');

            if (period.includes('1month') || period.includes('monthly')) {
                expiryDate.setMonth(expiryDate.getMonth() + 1);
            }
            else if (period.includes('3month') || period.includes('quarter')) {
                expiryDate.setMonth(expiryDate.getMonth() + 3); 
            }
            else if (period.includes('6month') || period.includes('halfyear')) {
                expiryDate.setMonth(expiryDate.getMonth() + 6);
            }
            else if (period.includes('1year') || period.includes('yearly') || period.includes('12month')) {
                expiryDate.setFullYear(expiryDate.getFullYear() + 1);
            }
            else {
                expiryDate.setDate(expiryDate.getDate() + 7);
            }

            let day = String(expiryDate.getDate()).padStart(2, '0');
            let month = String(expiryDate.getMonth() + 1).padStart(2, '0');
            let year = expiryDate.getFullYear();
            let expiryStr = `${day}-${month}-${year}`;

            document.getElementById('modalPlanName').innerText = planName;
            document.getElementById('modalPlanPrice').innerText = planPrice.toFixed(2);
            document.getElementById('modalPlanGST').innerText = gst.toFixed(2);
            document.getElementById('modalPlanTotal').innerText = total.toFixed(2);

            document.getElementById('modalPlanPeriod').innerText = planShort;
            document.getElementById('modalPlanExpiry').innerText = expiryStr;

            document.getElementById('planPrice').value = total;
            document.getElementById('planId').value = planId;

            var modal = new bootstrap.Modal(document.getElementById('planModal'));
            modal.show();
        });
    });

});
</script>


@endsection