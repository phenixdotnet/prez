<?php


namespace App\Http\Controllers;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function show() {


        $results = DB::select("SELECT text FROM texts");
        $text = $results[0]->text;

        Log::info("Loaded text for home page", ["text" => $text]);

        return view('welcome', [
            'AppName' => config("app.name"),
            'Text' => $text
        ]);
    }
}