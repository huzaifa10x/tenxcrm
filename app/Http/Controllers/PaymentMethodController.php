<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $PaymentMethods = PaymentMethod::paginate(15);
        return view('payment-methods.index', compact('PaymentMethods'));
    }

    public function create()
    {
        return view('payment-methods.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        PaymentMethod::create($request->only('name'));

        return redirect()->route('payment-methods.index')->with('success', 'Method created successfully.');
    }

    public function edit(PaymentMethod $paymentMethod)
    {
        return view('payment-methods.edit', compact('paymentMethod'));
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $paymentMethod->update($request->only('name'));

        return redirect()->route('payment-methods.index')->with('success', 'Method updated successfully.');
    }
    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();

        return redirect()->route('payment-methods.index')->with('success', 'Method deleted successfully.');
    }
}
