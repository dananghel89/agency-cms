<?php

namespace App\Http\Controllers\Admin;

use App\Models\Page;
use Illuminate\View\View;

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
        return $this->notImplemented(__('Add New Page'));
    }

    public function edit(string $id): View
    {
        Page::findOrFail($id);

        return $this->notImplemented(__('Edit Page'));
    }

    private function notImplemented(string $title): View
    {
        return view('admin.common.not-implemented', [
            'title' => $title,
            'slug' => 'pages',
            'back' => route('pages.index'),
        ]);
    }
}
