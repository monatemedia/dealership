<?php // app/Http/Controllers/Api/ManufacturerController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Manufacturer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ManufacturerController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $query = $request->input('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $manufacturers = Manufacturer::search($query)
            ->take(20)
            ->get()
            ->map(fn($manufacturer) => [
                'id' => $manufacturer->id,
                'name' => $manufacturer->name,
            ]);

        return response()->json($manufacturers);
    }

    public function show(int $id): JsonResponse
    {
        $manufacturer = Manufacturer::findOrFail($id);

        return response()->json([
            'id' => $manufacturer->id,
            'name' => $manufacturer->name,
        ]);
    }
}
