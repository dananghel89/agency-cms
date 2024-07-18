<?php

namespace App\Http\Controllers\Admin;

use Illuminate\View\View;
use App\Models\Service;

class ServiceController extends BaseController
{
    public function index(): View
    {
        $data = [
            'items' => Service::all(),
        ];

        return view('admin.services.index', $data);
    }

    public function create(): View
    {
        return view('admin.services.create');
    }

    public function edit(string $id): View
    {
        return view('admin.services.edit');
    }
}
