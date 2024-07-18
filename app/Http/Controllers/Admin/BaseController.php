<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Project\AdminClass;

class BaseController extends Controller
{
    public function __construct()
    {
        $adminClass = new AdminClass;

        $data = [
            'sidebarNav' => $adminClass::getItems(),
        ];

        view()->share($data);
    }
}
