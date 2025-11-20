<!DOCTYPE html>
<html>

<head>
    <title>Razorpay Checkout</title>
</head>

<body>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    <script>
        var plan_id = "{{ $plan_id }}";
        var options = {
            "key": "{{ $razorpayKey }}",
            "amount": "{{ $amount }}",
            "currency": "INR",
            "name": "Test Payment",
            "description": "Razorpay Test Order",
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
                    console.log("User closed the Razorpay popup");

                    fetch("{{ route('payment.verify') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            order_id: "{{ $orderId }}",
                            status: "cancelled"
                        })
                    });

                    window.location.href = "{{ route('Payment') }}";
                }
            }
        };

        var rzp1 = new Razorpay(options);
        rzp1.open();
    </script>

</body>

</html>