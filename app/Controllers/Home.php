<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('layouts/header').
		view('layouts/aside').
		view('admin/dashboard').
		//view('admin/login').
		view('layouts/footer');
    }
}
