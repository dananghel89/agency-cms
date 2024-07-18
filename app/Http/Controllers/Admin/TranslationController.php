<?php

namespace App\Http\Controllers\Admin;

use App\Models\Translation;
use Illuminate\View\View;

class TranslationController extends BaseController
{
    public function index(): View
    {
        $data = [
            'items' => Translation::all(),
        ];

        return view('admin.strings.index', $data);
    }

    public function create(): View
    {
        return view('admin.strings.create');
    }

    public function edit(string $id): View
    {
        $data = [
            'item' => Translation::find($id),
        ];

        if (empty($data['item'])) abort(404);

        return view('admin.strings.edit', $data);
    }
}
