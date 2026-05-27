<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inquiry;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class InquiryController extends Controller
{

public function index()
{
    $inquiries = Inquiry::latest()->get();

    return view('admin.inquery.index', compact('inquiries'));
}
public function store(Request $request)
{

    \Log::info($request->all());

    $validator = Validator::make($request->all(), [

        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'number' => 'required|digits_between:10,15',
        'subject' => 'required|max:255',
        'message' => 'required',
        'g-recaptcha-response' => 'required',

    ]);

    if ($validator->fails()) {

        \Log::info($validator->errors()->toArray());

        return response()->json([
            'status' => false,
            'errors' => $validator->errors()
        ], 422);
    }

    $response = Http::asForm()->post(
        'https://www.google.com/recaptcha/api/siteverify',
        [
            'secret' => '6LdP_P4sAAAAAHsMYenJ1mTWu9ez3Kt9VKYP0SPn',
            'response' => $request->input('g-recaptcha-response'),
            'remoteip' => $request->ip(),
        ]
    );

    $captcha = $response->json();

    \Log::info('Captcha Response', $captcha);

    if (!isset($captcha['success']) || !$captcha['success']) {

        return response()->json([
            'status' => false,
            'errors' => [
                'g-recaptcha-response' => [
                    'Captcha verification failed'
                ]
            ]
        ], 422);
    }

    Inquiry::create([
        'name' => $request->name,
        'email' => $request->email,
        'number' => $request->number,
        'subject' => $request->subject,
        'message' => $request->message,
    ]);

    return response()->json([
        'status' => true,
        'message' => 'Inquiry Submitted Successfully'
    ]);
}
}
