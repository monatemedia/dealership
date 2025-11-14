<?php // app/Http/Controllers/Api/ModelController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ModelController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $query = $request->input('q', '');
        $manufacturerId = $request->input('manufacturer_id');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $searchQuery = Model::search($query);

        // Filter by manufacturer if provided
        if ($manufacturerId) {
            $searchQuery->where('manufacturer_id', $manufacturerId);
        }

        $models = $searchQuery
            ->take(20)
            ->get()
            ->map(fn($model) => [
                'id' => $model->id,
                'name' => $model->name,
                'manufacturer_id' => $model->manufacturer_id,
            ]);

        return response()->json($models);
    }

    public function show(int $id): JsonResponse
    {
        $model = Model::findOrFail($id);

        return response()->json([
            'id' => $model->id,
            'name' => $model->name,
            'manufacturer_id' => $model->manufacturer_id,
        ]);
    }
}
