<?php // app/Http/Controllers/ModelController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ModelController extends Controller
{
    public function search(Request $request)
    {
        try {
            $query = $request->input('q', '');
            $manufacturerId = $request->input('manufacturer_id');

            if (strlen($query) < 2) {
                return response()->json([]);
            }

            $models = Model::query()
                ->where('name', 'LIKE', "%{$query}%")
                ->when($manufacturerId, function ($q) use ($manufacturerId) {
                    $q->where('manufacturer_id', $manufacturerId);
                })
                ->orderBy('name')
                ->limit(50)
                ->get(['id', 'name', 'manufacturer_id']);

            return response()->json($models);
        } catch (\Exception $e) {
            Log::error('Model search error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $model = Model::findOrFail($id);

            return response()->json([
                'id' => $model->id,
                'name' => $model->name,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Model not found'], 404);
        }
    }
}
