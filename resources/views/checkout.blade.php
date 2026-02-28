<!DOCTYPE html>
<html>

<head>
    <title>Secure Payment</title>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>

<body>
    <script>
        var options = {

            key: "{{ $razorpayKey }}",

            amount: "{{ $amount }}",

            currency: "INR",

            name: "Your Company",

            description: "Plan Payment",

            order_id: "{{ $orderId }}",

            handler: function(response) {

                var form = document.createElement('form');

                form.method = "POST";

                form.action = "{{ route('razorpay.success') }}";

                form.innerHTML = `
                @csrf
                <input type="hidden" name="razorpay_payment_id" value="${response.razorpay_payment_id}">
                <input type="hidden" name="razorpay_order_id" value="${response.razorpay_order_id}">
                <input type="hidden" name="razorpay_signature" value="${response.razorpay_signature}">
                `;
                document.body.appendChild(form);
                form.submit();
                },
                modal: {
                ondismiss: function() {
                window.location.href = "{{ route('Payment.failed') }}?order_id={{ $orderId }}";
                }
            }
        };
        var rzp = new Razorpay(options);

        rzp.open();
    </script>

</body>

</html>