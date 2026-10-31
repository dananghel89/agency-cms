<?php

namespace App\Http\Controllers\Admin;

use App\Models\Portfolio;
use Illuminate\View\View;

class PortfolioController extends BaseController
{
    public function index(): View
    {
        $data = [
            'items' => Portfolio::all(),
        ];

        return view('admin.portfolio.index', $data);
    }

    public function create(): View
    {
        return $this->notImplemented(__('Add New Portfolio Item'));
    }

    public function edit(string $id): View
    {
        Portfolio::findOrFail($id);

        return $this->notImplemented(__('Edit Portfolio Item'));
    }

    private function notImplemented(string $title): View
    {
        return view('admin.common.not-implemented', [
            'title' => $title,
            'slug' => 'portfolio',
            'back' => route('portfolio.index'),
        ]);
    }
}
