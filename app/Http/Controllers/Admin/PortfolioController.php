<?php

namespace App\Http\Controllers\Admin;

use Illuminate\View\View;
use App\Models\Portfolio;

class PortfolioController extends BaseController
{
    public function index(): View
    {
        $data = [
            'items' => Portfolio::all(),
        ];

        return view('admin.porfolio.index', $data);
    }

    public function create(): View
    {
        return view('admin.porfolio.create');
    }

    public function edit(string $id): View
    {
        return view('admin.porfolio.edit');
    }
}
