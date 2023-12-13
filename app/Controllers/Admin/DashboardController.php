<?php

namespace App\Controllers\Admin;

use App\Controllers\AppController;

class DashboardController extends AppController
{
    public function index()
    {
        return $this->view('dashboard');
    }
}
