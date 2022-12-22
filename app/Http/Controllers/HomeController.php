<?php

namespace App\Http\Controllers;

use App\Helpers\HttpClient;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $resCategory = HttpClient::fetch(
            "GET",
            'http://127.0.0.1:8000/api/category'
        );
        $category = $resCategory['data'];
        return view('home', [
            'category' => $category
        ]);
    }
}
