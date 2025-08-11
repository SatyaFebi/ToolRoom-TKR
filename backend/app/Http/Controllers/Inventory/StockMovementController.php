<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\StockMovement;
use App\Models\Inventory\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StockMovementController extends Controller
{
    public function index()
    {
        return response()->json(StockMovement::with('item')->latest()->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'item_id' => 'required|exists:items,id',
            'movement_type' => 'required|in:in,out',
            'qty' => 'required|integer|min:1',
            'location' => 'nullable|string|max:255',
            'photo' => 'required|image|max:2048',
            'notes' => 'nullable|string',
            'movement_date' => 'required|date',
        ]);

        // Simpan foto
        $data['photo_path'] = $request->file('photo')->store('stock_movements', 'public');

        unset($data['photo']);

        $movement = StockMovement::create($data);

        // Update stok barang
        $item = Item::find($data['item_id']);
        if ($data['movement_type'] === 'in') {
            $item->increment('stock', $data['qty']);
        } else {
            $item->decrement('stock', $data['qty']);
        }

        return response()->json($movement->load('item'), 201);
    }

    public function show(StockMovement $stockMovement)
    {
        return response()->json($stockMovement->load('item'));
    }

    public function destroy(StockMovement $stockMovement)
    {
        if ($stockMovement->photo_path) {
            Storage::disk('public')->delete($stockMovement->photo_path);
        }
        $stockMovement->delete();

        return response()->json(null, 204);
    }
}
