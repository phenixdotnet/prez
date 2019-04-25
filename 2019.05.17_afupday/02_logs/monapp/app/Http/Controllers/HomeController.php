<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function show()
    {
        Log::info("call HomeController");

        return view('welcome');
    }
}

