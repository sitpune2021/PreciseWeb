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
        $plans = PaymentPlan::where('is_active', 1)->get();
        return view('Payment.renew', compact('plans', 'client'));
    }
    public function order(Request $request)
    {
        $request->validate([
            'planId' => 'required|exists:payment_plan,id'
        ]);
        $plan = PaymentPlan::where('id', $request->planId)
            ->where('is_active', 1)
            ->firstOrFail();
        $basePrice = $plan->price;
        $gstAmount = round(($basePrice * $plan->gst) / 100);
        $totalAmount = $basePrice + $gstAmount;

        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        $razorpayOrder = $api->order->create([
            'receipt' => 'order_' . time(),
            'amount' => $totalAmount * 100,
            'currency' => 'INR'
        ]);

        $order = Order::create([
            'user_id' => Auth::id(),
            'plan_id' => $plan->id,
            'plan_title' => $plan->title,
            'plan_days' => $plan->days,
            'gst_percentage' => $plan->gst,
            'razorpay_order_id' => $razorpayOrder['id'],
            'amount' => $totalAmount,
            'payment_status' => 'pending',
            'plan_status' => 0
        ]);

        return view('checkout', [
            'orderId' => $razorpayOrder['id'],
            'amount' => $totalAmount * 100,
            'razorpayKey' => env('RAZORPAY_KEY'),
            'plan_id' => $plan->id
        ]);
    }
    public function success(Request $request)
    {
        $request->validate([
            'razorpay_order_id' => 'required',
            'razorpay_payment_id' => 'required',
            'razorpay_signature' => 'required'
        ]);

        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        try {
            $api->utility->verifyPaymentSignature([
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature
            ]);
        } catch (\Exception $e) {
            return redirect()->route('payment.failed')
                ->with('error', 'Payment verification failed');
        }

        DB::beginTransaction();

        try {

            $order = Order::where('razorpay_order_id', $request->razorpay_order_id)
                ->where('payment_status', 'pending')
                ->firstOrFail();

            $client = Client::where('login_id', Auth::id())->first();
            $plan   = PaymentPlan::findOrFail($order->plan_id);

            $planDays = $plan->days;

            if (!empty($client->plan_expiry) && Carbon::parse($client->plan_expiry)->isFuture()) {
                $expiry = Carbon::parse($client->plan_expiry)->addDays($planDays);
            } else {
                $expiry = Carbon::now()->addDays($planDays);
            }

            // Old plans inactive
            Order::where('user_id', Auth::id())->update(['plan_status' => 0]);

            $order->update([
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature'  => $request->razorpay_signature,
                'payment_status'      => 'completed',
                'plan_status'         => 1
            ]);

            $client->update([
                'plan_type'   => $order->plan_id,
                'plan_expiry' => $expiry,
                'status'      => 1
            ]);

            DB::commit();

            return view('Payment.success', compact('order'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong');
        }
    }
    public function failed(Request $request)
    {
        if ($request->order_id) {
            Order::where('razorpay_order_id', $request->order_id)
                ->where('payment_status', 'pending')
                ->update(['payment_status' => 'failed']);
        }
        return view('Payment.failed');
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
    public function AllPaymentList()
    {
        if (auth()->user()->user_type != 1) {
            abort(403);
        }

        $subscription = Order::with('user')
            ->orderBy('id', 'desc')
            ->get();

        return view('Payment.all_view', compact('subscription'));
    }
}
