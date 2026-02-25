<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ProdukController;//nyoba
use App\Models\ModelProduk;//nyoba
use Illuminate\Http\Request;

class NavbarController extends Controller
{
    public function home()
    {
        return redirect()->route('test');
    }

    public function produk()
    {
        return redirect()->route('viewproduk');
    }

    public function about()
    {
        return view('kerangka.about');
    }

    public function kontak()
    {
        return view('kerangka.kontak');
    }

}
