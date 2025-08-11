<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        return response()->json(Item::with('category.type')->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'item_category_id' => 'required|exists:item_categories,id',
            'code' => 'required|string|max:50|unique:items,code',
            'name' => 'required|string|max:100',
            'brand' => 'nullable|string|max:100',
            'unit' => 'required|string|max:20',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
        ]);

        $item = Item::create($data);

        return response()->json($item, 201);
    }

    public function show(Item $item)
    {
        return response()->json($item->load('category.type'));
    }

    public function update(Request $request, Item $item)
    {
        $data = $request->validate([
            'item_category_id' => 'required|exists:item_categories,id',
            'code' => 'required|string|max:50|unique:items,code,' . $item->id,
            'name' => 'required|string|max:100',
            'brand' => 'nullable|string|max:100',
            'unit' => 'required|string|max:20',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
        ]);

        $item->update($data);

        return response()->json($item);
    }

    public function destroy(Item $item)
    {
        $item->delete();

        return response()->json(null, 204);
    }
}
