<?php

namespace App\Http\Controllers\Admin;

use Illuminate\View\View;
use App\Models\Timeline;

class TimelineController extends BaseController
{
    public function index(): View
    {
        $data = [
            'items' => Timeline::all(),
        ];

        return view('admin.timeline.index', $data);
    }

    public function create(): View
    {
        return view('admin.timeline.create');
    }

    public function edit(string $id): View
    {
        return view('admin.timeline.edit');
    }
}
