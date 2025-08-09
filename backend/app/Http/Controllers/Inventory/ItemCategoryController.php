<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\ItemCategory;
use Illuminate\Http\Request;

class ItemCategoryController extends Controller
{
    public function index()
    {
        return response()->json(ItemCategory::with('type')->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'item_type_id' => 'required|exists:item_types,id',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        $category = ItemCategory::create($data);

        return response()->json($category, 201);
    }

    public function show(ItemCategory $itemCategory)
    {
        return response()->json($itemCategory->load('type'));
    }

    public function update(Request $request, ItemCategory $itemCategory)
    {
        $data = $request->validate([
            'item_type_id' => 'required|exists:item_types,id',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        $itemCategory->update($data);

        return response()->json($itemCategory);
    }

    public function destroy(ItemCategory $itemCategory)
    {
        $itemCategory->delete();

        return response()->json(null, 204);
    }
}
