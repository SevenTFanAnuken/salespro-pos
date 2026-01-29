<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function store(Request $request)
{
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'nullable|string|max:20',
        'address' => 'nullable|string',
    ]);

    Supplier::create($data);

    return back()->with('success', 'Supplier added successfully!');
}
public function destroy($id)
{
    $item = Supplier::findOrFail($id); // Or Supplier
    $item->delete();
    
    return back()->with('success', 'Deleted successfully!');
}
public function update(Request $request, $id) {
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'nullable|string|max:20',
        'address' => 'nullable|string',
    ]);
    $item = Supplier::findOrFail($id);
    $item->update($data);
    return back()->with('success', 'Updated!');
}
}
