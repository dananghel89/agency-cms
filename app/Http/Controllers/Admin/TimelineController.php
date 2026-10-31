<?php

namespace App\Http\Controllers\Admin;

use App\Models\Timeline;
use Illuminate\View\View;

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
        return $this->notImplemented(__('Add New Timeline Item'));
    }

    public function edit(string $id): View
    {
        Timeline::findOrFail($id);

        return $this->notImplemented(__('Edit Timeline Item'));
    }

    private function notImplemented(string $title): View
    {
        return view('admin.common.not-implemented', [
            'title' => $title,
            'slug' => 'timeline',
            'back' => route('timeline.index'),
        ]);
    }
}
