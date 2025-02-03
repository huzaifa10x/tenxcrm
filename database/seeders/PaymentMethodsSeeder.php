<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentMethodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $paymentMethods = [
            'PayPal',
            'Stripe',
            'Square',
            'Amazon Payments',
            'Google Wallet',
            'Skrill',
            'Payoneer',
            'cash',
            'check',
            'credit card',
            'debit card',
            'bank transfer',
            'money order',
            'purchase order',
            'invoice',
            'gift card',
            'store credit',
            'cryptocurrency',
            'other',
        ];

        // Seed the paymentMethods
        foreach ($paymentMethods as $paymentMethod) {
            PaymentMethod::create([
                'name' => $paymentMethod
            ]);
        }
    }
}
