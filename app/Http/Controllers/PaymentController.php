<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Razorpay\Api\Api;
use App\Models\Order;
use App\Models\Client;
use App\Models\PaymentPlan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function Payment()
    {
        $client = Client::where('login_id', Auth::id())->first();
        $plans = PaymentPlan::all();
        return view('Payment.renew', compact('plans', 'client'));
    }

    public function order(Request $request)
    {

        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
        $price = $request->price ?? 1;
        $priceWithGST = round($price * 1.18); // 18% GST

        $razorpayOrder = $api->order->create([
            'receipt' => 'order_' . time(),
            'amount' => $priceWithGST * 100,
            'currency' => 'INR'
        ]);

        $order = Order::create([
            'user_id' => Auth::id(),
            'plan_id' => $request->planId ?? null,
            'razorpay_order_id' => $razorpayOrder['id'],
            'amount' => $priceWithGST,
            'payment_status' => 'pending',
            'plan_status' => '0'
        ]);

        return view('checkout', [
            'orderId' => $razorpayOrder['id'],
            'amount' => $razorpayOrder['amount'],
            'razorpayKey' => env('RAZORPAY_KEY'),
            'plan_id' => $request->planId
        ]);
    }

    public function success(Request $request)
    {
        $client = Auth::user()->client;
        $planType = $request->planId;

        $expiry = match ($planType) {
            '1month' => Carbon::now()->addMonth(),
            '3month' => Carbon::now()->addMonths(3),
            '1year' => Carbon::now()->addYear(),
            default => Carbon::now()->addDays(7)
        };

        $order = Order::where('razorpay_order_id', $request->razorpay_order_id)->first();

        if ($order) {
            // Update the order table
            $order->update([
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature'  => $request->razorpay_signature,
                'amount'              => $order->amount,
                'payment_status'      => 'completed',
            ]);

            $client_id = Auth::id();
            $plan_id =  $order->plan_id;

            Client::where('id', $client_id)->update([
                'plan_type'   => $plan_id,
                'plan_expiry' => $expiry,
                'status'      => 1
            ]);
        }
        return view('payment.success', [
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'razorpay_signature' => $request->razorpay_signature,
            'razorpay_order_id' => $request->razorpay_order_id,
            'amount' => $order->amount,
            'payment_status' => "completed"
        ]);
    }
    public function PaymentList()
    {
        $payments = Order::all();
        $orders = Client::all();
        return view('Payment.view', compact('payments', 'orders'));
    }
}
