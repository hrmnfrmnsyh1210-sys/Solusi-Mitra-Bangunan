<?php

namespace App\Controllers;

class LandingController extends BaseController
{
    public function index()
    {
        if (session()->get('logged_in')) {
            return redirect()->to(base_url('dashboard'));
        }

        return view('landing');
    }
}
