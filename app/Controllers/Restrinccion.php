<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Restrinccion extends BaseController
{
    public function __construct()
    {
        parent::__construct();

        if (!session()->get('login')) {
            redirect()->to(base_url())->send();
            exit;
        }
    }

    public function index()
    {
        return
            view('layouts/header') .
            view('layouts/aside') .
            view('home/mensaje') .
            view('layouts/footer');
    }
}