<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class RegTuto extends BaseController
{
    public function index()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url());
        }

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('home/tutorial') .
            view('layouts/footer');
    }
}