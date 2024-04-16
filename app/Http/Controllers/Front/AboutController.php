<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;

class AboutController extends Controller
{
    public function index()
    {
        return view('front.pages.about.index');
    }
}
