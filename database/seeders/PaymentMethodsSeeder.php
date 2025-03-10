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
            'cash',
            'check',
            'bank transfer',
            'other',
        ];

        foreach ($paymentMethods as $paymentMethod) {
            PaymentMethod::create([
                'name' => $paymentMethod
            ]);
        }
    }
}
