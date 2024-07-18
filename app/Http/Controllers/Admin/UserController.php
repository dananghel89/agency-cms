<?php

namespace App\Http\Controllers\Admin;

use Illuminate\View\View;
use App\Models\User;

class UserController extends BaseController
{
    public function index(): View
    {
        $data = [
            'items' => User::all(),
        ];

        return view('admin.users.index', $data);
    }

    public function create(): View
    {
        return view('admin.users.create');
    }

    public function edit(string $id): View
    {
        return view('admin.users.edit');
    }
}
