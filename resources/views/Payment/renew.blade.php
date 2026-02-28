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

                    <h5 class="plan-title">Choose a Plan</h5>

                    <div class="row g-4">

                        @foreach($plans as $plan)

                        <div class="col-md-3">

                            <div class="plan-card text-center"
                                data-id="{{ $plan->id }}"
                                data-plan="{{ $plan->title }}"
                                data-price="{{ $plan->price }}"
                                data-days="{{ $plan->days }}"
                                data-short="{{ $plan->short_text }}"
                                data-gst="{{ $plan->gst }}">

                                <h6 class="fw-bold mb-1">{{ $plan->title }}</h6>

                                <h3 class="fw-bold text-primary">
                                    {{ $plan->price == 0 ? 'FREE' : '₹'.$plan->price }}
                                </h3>

                                <p class="text-muted small mb-1">{{ $plan->short_text }}</p>
                                <p class="text-muted small fst-italic">{{ $plan->description }}</p>


                                {{-- Current Active Plan --}}
                                @if ($client->plan_type == $plan->id)

                                <button class="button" disabled style="background:#fff;color:#880088;border:2px solid #15ec11;">
                                    Active
                                </button>

                                {{-- Free plan already used --}}
                                @elseif ($plan->price == 0 && $client->plan_type != null)

                                <button class="button" disabled style="background:#eee;color:#999;border:2px dashed #ccc;">
                                    Free Trial Used
                                </button>

                                {{-- Available plan --}}
                                @else

                                <button type="button" class="button openModalBtn">
                                    <i class="fas fa-crown"></i> Select Plan
                                </button>

                                @endif

                            </div>
                        </div>

                        @endforeach

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


<!-- Modal -->
<form method="POST" action="{{ route('razorpay.order') }}">
    @csrf

    <div class="modal fade" id="planModal">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Confirm Plan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <h5 id="modalPlanName" class="text-center mb-4"></h5>

                    <table class="table table-borderless">

                        <tr>
                            <th>Price</th>
                            <td class="text-end">₹<span id="modalPlanPrice"></span></td>
                        </tr>

                        <tr>
                            <th>GST</th>
                            <td class="text-end">₹<span id="modalPlanGST"></span></td>
                        </tr>

                        <tr>
                            <th>Total</th>
                            <td class="text-end text-success fw-bold">
                                ₹<span id="modalPlanTotal"></span>
                            </td>
                        </tr>

                        <tr>
                            <th>Period</th>
                            <td class="text-end">
                                <span id="modalPlanPeriod"></span>
                            </td>
                        </tr>

                        <tr>
                            <th>Expiry</th>
                            <td class="text-end">
                                <span id="modalPlanExpiry"></span>
                            </td>
                        </tr>

                    </table>

                    <input type="hidden" name="planId" id="planId">

                    <button class="btn btn-primary w-100">
                        Proceed to Payment
                    </button>

                </div>

            </div>
        </div>
    </div>

</form>


<script>
    document.querySelectorAll('.openModalBtn').forEach(btn => {

        btn.onclick = function() {

            let card = this.closest('.plan-card');

            let planId = card.dataset.id;
            let planName = card.dataset.plan;
            let price = parseFloat(card.dataset.price);
            let gstPercent = parseFloat(card.dataset.gst);
            let short = card.dataset.short;
            let days = parseInt(card.dataset.days); // important

            let gst = Math.round((price * gstPercent) / 100);
            let total = price + gst;

            let expiry = new Date();
            expiry.setDate(expiry.getDate() + days); // dynamic expiry

            document.getElementById('modalPlanName').innerText = planName;
            document.getElementById('modalPlanPrice').innerText = price;
            document.getElementById('modalPlanGST').innerText = gst;
            document.getElementById('modalPlanTotal').innerText = total;
            document.getElementById('modalPlanPeriod').innerText = short;
            document.getElementById('modalPlanExpiry').innerText = expiry.toLocaleDateString();

            document.getElementById('planId').value = planId;

            new bootstrap.Modal(document.getElementById('planModal')).show();
        }

    });
</script>

@endsection