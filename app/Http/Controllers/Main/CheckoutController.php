<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;

class CheckoutController extends Controller
{
    public function index()
    {
        return inertia('main/Checkout');
    }
}
