<?php // app/Http/Controllers/Api/ManufacturerController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Manufacturer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Enums\DataSource;

class ManufacturerController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $query = $request->input('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $manufacturers = Manufacturer::search($query)
            ->where('source', DataSource::ORIGINAL->value) // Only show original manufacturer
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
        // Allow showing user-generated manufacturers by ID (for editing existing vehicles)
        // But in practice, the dropdowns won't surface them
        $manufacturer = Manufacturer::findOrFail($id);

        return response()->json([
            'id' => $manufacturer->id,
            'name' => $manufacturer->name,
        ]);
    }
}
