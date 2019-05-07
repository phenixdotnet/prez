<?php


namespace App\Http\Controllers;


use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function show() {

        $results = DB::select("SELECT text FROM texts");
        $text = $results[0]->text;

        return view('welcome', [
            'AppName' => config("app.name"),
            'Text' => $text
        ]);
    }
}