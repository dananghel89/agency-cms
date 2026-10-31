<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\View\View;

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
        return $this->notImplemented(__('Add New User'));
    }

    public function edit(string $id): View
    {
        User::findOrFail($id);

        return $this->notImplemented(__('Edit User'));
    }

    private function notImplemented(string $title): View
    {
        return view('admin.common.not-implemented', [
            'title' => $title,
            'slug' => 'users',
            'back' => route('users.index'),
        ]);
    }
}
