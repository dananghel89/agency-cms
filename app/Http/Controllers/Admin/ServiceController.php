<?php

namespace App\Http\Controllers\Admin;

use App\Models\Service;
use Illuminate\View\View;

class ServiceController extends BaseController
{
    public function index(): View
    {
        $data = [
            'items' => Service::all(),
        ];

        return view('admin.services.index', $data);
    }

    public function create(): View
    {
        return $this->notImplemented(__('Add New Service'));
    }

    public function edit(string $id): View
    {
        Service::findOrFail($id);

        return $this->notImplemented(__('Edit Service'));
    }

    private function notImplemented(string $title): View
    {
        return view('admin.common.not-implemented', [
            'title' => $title,
            'slug' => 'services',
            'back' => route('services.index'),
        ]);
    }
}
