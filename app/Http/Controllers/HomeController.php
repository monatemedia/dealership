<?php

namespace App\Http\Controllers;

use App\Models\Manufacturer;
use App\Models\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;

class HomeController extends Controller
{
    public function index()
    {
        Manufacturer::factory()
            ->count(5)
            ->has(Model::factory()->count(3))
            ->create();

        // Return the blade view
        return view('home.index');
    }
}
