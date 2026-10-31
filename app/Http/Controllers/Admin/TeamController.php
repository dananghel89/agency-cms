<?php

namespace App\Http\Controllers\Admin;

use Illuminate\View\View;
use App\Models\Team;

class TeamController extends BaseController
{
    public function index(): View
    {
        $data = [
            'items' => Team::all(),
        ];

        return view('admin.team.index', $data);
    }

    public function create(): View
    {
        return $this->notImplemented(__('Add New Team Member'));
    }

    public function edit(string $id): View
    {
        Team::findOrFail($id);

        return $this->notImplemented(__('Edit Team Member'));
    }

    private function notImplemented(string $title): View
    {
        return view('admin.common.not-implemented', [
            'title' => $title,
            'slug' => 'team',
            'back' => route('team.index'),
        ]);
    }
}
