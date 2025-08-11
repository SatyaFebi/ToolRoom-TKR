<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\ItemType;
use Illuminate\Http\Request;

class ItemTypeController extends Controller
{
    public function index()
    {
        return response()->json(ItemType::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        $type = ItemType::create($data);

        return response()->json($type, 201);
    }

    public function show(ItemType $itemType)
    {
        return response()->json($itemType);
    }

    public function update(Request $request, ItemType $itemType)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        $itemType->update($data);

        return response()->json($itemType);
    }

    public function destroy(ItemType $itemType)
    {
        $itemType->delete();

        return response()->json(null, 204);
    }
}
