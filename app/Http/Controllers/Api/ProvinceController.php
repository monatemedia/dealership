<?php // app/Http/Controllers/Api/ProvinceController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Province;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $query = $request->input('q', '');

        if (strlen($query) < 1) {
            return response()->json([]);
        }

        $provinces = Province::search($query)
            ->take(20)
            ->get()
            ->map(fn($province) => [
                'id' => $province->id,
                'name' => $province->name,
            ]);

        return response()->json($provinces);
    }

    public function show(int $id): JsonResponse
    {
        $province = Province::findOrFail($id);

        return response()->json([
            'id' => $province->id,
            'name' => $province->name,
        ]);
    }
}
