<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        return view('services.index');
        // return response()->json(Service::latest()->get());
    }
    public function getServices()
    {
        return response()->json(Service::latest()->get());
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        Service::create($request->only('name'));
        return response()->json(['message' => 'Service added']);
    }

    public function show(Service $service)
    {
        return response()->json($service);
    }

    public function update(Request $request, Service $service)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $service->update($request->only('name'));
        return response()->json(['message' => 'Service updated']);
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return response()->json(['message' => 'Service deleted']);
    }
}
