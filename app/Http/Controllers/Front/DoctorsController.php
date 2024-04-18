<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;

class DoctorsController extends Controller
{
    public function index()
    {
        return view('front.pages.doctors.index');
    }
    public function view()
    {
        return view('front.pages.doctors.view');
    }
}
