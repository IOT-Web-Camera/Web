<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SupportController extends Controller
{
    public function faq()
    {
        return view('pages.support.faq');
    }

    public function contact()
    {
        return view('pages.support.contact');
    }
}
