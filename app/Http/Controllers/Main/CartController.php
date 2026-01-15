<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;

class CartController extends Controller
{
    public function index()
    {
        return inertia('main/Cart');
    }
}
