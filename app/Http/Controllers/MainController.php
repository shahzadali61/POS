<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public function checkRole(){
        if (auth()->check()) {
            if (auth()->user()->role == "user") {

                return redirect()->route('user.dashboard');
            } else {

                return redirect()->route('profile.edit');
            }
        } else {

            return redirect()->route('login');
        }

    }
}
