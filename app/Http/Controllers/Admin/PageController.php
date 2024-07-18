<?php

namespace App\Http\Controllers\Admin;

use Illuminate\View\View;
use App\Models\Page;

class PageController extends BaseController
{
    public function index(): View
    {
        $data = [
            'items' => Page::all(),
        ];

        return view('admin.pages.index', $data);
    }

    public function create(): View
    {
        return view('admin.pages.create');
    }

    public function edit(string $id): View
    {
        return view('admin.pages.edit');
    }
}
