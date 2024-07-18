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
        return view('admin.team.create');
    }

    public function edit(string $id): View
    {
        return view('admin.team.edit');
    }
}
