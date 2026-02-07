<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::paginate(20);
        return view('warehouses.index', compact('warehouses'));
    }

    public function create()
    {
        return view('warehouses.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'capacity' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:active,inactive,maintenance',
            'description' => 'nullable|string',
        ]);
        $validated['is_active'] = $request->has('is_active');
        Warehouse::create($validated);
        return redirect()->route('warehouses.index')->with('success', 'تم إنشاء المستودع بنجاح');
    }

    public function show(Warehouse $warehouse)
    {
        return view('warehouses.show', compact('warehouse'));
    }

    public function edit(Warehouse $warehouse)
    {
        return view('warehouses.form', compact('warehouse'));
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'capacity' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:active,inactive,maintenance',
            'description' => 'nullable|string',
        ]);
        $validated['is_active'] = $request->has('is_active');
        $warehouse->update($validated);
        return redirect()->route('warehouses.index')->with('success', 'تم تحديث المستودع بنجاح');
    }

    public function destroy(Warehouse $warehouse)
    {
        $warehouse->delete();
        return redirect()->route('warehouses.index')->with('success', 'تم حذف المستودع بنجاح');
    }
}
