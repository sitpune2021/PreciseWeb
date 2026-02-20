<!DOCTYPE html>
<html>

<head>
    <title>Secure Razorpay Checkout</title>
</head>

<body>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    <script>
        var plan_id = "{{ $plan_id }}";

        var options = {
            "key": "{{ $razorpayKey }}",
            "amount": "{{ $amount }}",
            "currency": "INR",
            "name": "Your Company Name",
            "description": "Subscription Payment",
            "order_id": "{{ $orderId }}",

            "handler": function(response) {

                var form = document.createElement('form');
                form.method = "POST";
                form.action = "{{ route('razorpay.success') }}";

                form.innerHTML = `
                @csrf
                <input type="hidden" name="razorpay_payment_id" value="${response.razorpay_payment_id}">
                <input type="hidden" name="razorpay_order_id" value="${response.razorpay_order_id}">
                <input type="hidden" name="razorpay_signature" value="${response.razorpay_signature}">
                <input type="hidden" name="planId" value="${plan_id}">
            `;

                document.body.appendChild(form);
                form.submit();
            },

            "modal": {
                "ondismiss": function() {
                    // User cancelled payment
                    window.location.href = "{{ route('Payment.failed') }}?order_id={{ $orderId }}";
                }
            }
        };

        var rzp = new Razorpay(options);
        rzp.open();
    </script>

</body>

</html>