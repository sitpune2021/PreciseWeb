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
        $client = Client::with('plan')->where('login_id', Auth::id())->first();
        $plans = PaymentPlan::all();

        
        return view('Payment.renew', compact('plans', 'client'));
    }

    public function order(Request $request)
    {

        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
        $price = $request->price ?? 1;
        $priceWithGST = round($price); // 18% GST

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
        $client = Client::where('login_id', Auth::id())->first();

        $order = Order::where('razorpay_order_id', $request->razorpay_order_id)->firstOrFail();
        $plan  = PaymentPlan::findOrFail($order->plan_id);

        $planDays = $plan->days;

        if (!empty($client->plan_expiry) && Carbon::parse($client->plan_expiry)->isFuture()) {

            $expiry = Carbon::parse($client->plan_expiry)->addDays($planDays);
        } else {

            $expiry = Carbon::now()->addDays($planDays);
        }
        Order::where('user_id', Auth::id())->update(['plan_status' => '0']);

        $order->update([
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'razorpay_signature'  => $request->razorpay_signature,
            'payment_status'      => 'completed',
            'plan_status'         => '1'
        ]);

        if ($plan->short_text === 'trial') {
            if ($client->is_trial_used == 1) {
                return back()->with('error', 'Trial plan can be used only once.');
            }
            $client->update(['is_trial_used' => 1]);
        }
        $client->update([
            'plan_type'   => $order->plan_id,
            'plan_expiry' => $expiry,
            'status'      => 1
        ]);

        return view('Payment.success', [
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'razorpay_signature' => $request->razorpay_signature,
            'razorpay_order_id' => $request->razorpay_order_id,
            'amount' => $order->amount,
            'payment_status' => "completed"
        ]);
    }

    public function PaymentList()
    {
        $adminId = Auth::id();

        $payments = Order::with('user')
            ->where('user_id', $adminId)
            ->orderBy('id', 'desc')
            ->get();

        return view('Payment.view', compact('payments'));
    }
}
