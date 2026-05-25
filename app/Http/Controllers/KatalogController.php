<?php

namespace App\Http\Controllers;

use App\Models\Product;

class KatalogController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('katalog.index', compact('products'));
    }
}
