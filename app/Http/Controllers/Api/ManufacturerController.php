<?php // app/Http/Controllers/ManufacturerController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Manufacturer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ManufacturerController extends Controller
{
    public function search(Request $request)
    {
        try {
            $query = $request->input('q', '');

            if (strlen($query) < 2) {
                return response()->json([]);
            }

            $manufacturers = Manufacturer::query()
                ->where('name', 'LIKE', "%{$query}%")
                ->orderBy('name')
                ->limit(50)
                ->get(['id', 'name']);

            return response()->json($manufacturers);
        } catch (\Exception $e) {
            Log::error('Manufacturer search error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $manufacturer = Manufacturer::findOrFail($id);

            return response()->json([
                'id' => $manufacturer->id,
                'name' => $manufacturer->name,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Manufacturer not found'], 404);
        }
    }
}
