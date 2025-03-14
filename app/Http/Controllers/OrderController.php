<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function orderCreate(){

        return Inertia::render('admin/order/Create');
    }
}
