<?php // app/Http/Controllers/ProvinceController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Province;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q', '');

        $provinces = Province::query()
            ->where('name', 'LIKE', "%{$query}%")
            ->orderBy('name')
            ->limit(20)
            ->get(['id', 'name']);

        return response()->json($provinces);
    }

    public function show($id)
    {
        $province = Province::findOrFail($id);

        return response()->json([
            'id' => $province->id,
            'name' => $province->name,
        ]);
    }
}
