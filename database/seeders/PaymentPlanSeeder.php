<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentPlanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('payment_plan')->insert([
            [
                'title' => '7 DAYS FREE',
                'price' => 0,
                'short_text' => '7 Days Trial',
                'description' => 'Free for 7 days!',
                'days' => 7,
                'gst' => 18,
                'plan_status'=> 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => '1 MONTH',
                'price' => 499,
                'short_text' => '1 Month',
                'description' => 'Price includes GST',
                'days' => 30,
                'gst' => 18,
                'plan_status'=> 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => '6 MONTHS',
                'price' => 2499,
                'short_text' => '6 Months',
                'description' => 'Price includes GST',
                'days' => 180,
                'gst' => 18,
                'plan_status'=> 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => '1 YEAR',
                'price' => 3999,
                'short_text' => '1 Year',
                'description' => 'Price includes GST',
                'days' => 365,
                'gst' => 18,
                'plan_status'=> 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
