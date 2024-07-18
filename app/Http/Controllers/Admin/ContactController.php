<?php

namespace App\Http\Controllers\Admin;

use App\Models\Contact;
use Illuminate\View\View;

class ContactController extends BaseController
{
    public function index(): View
    {
        $data = [
            'items' => Contact::all(),
        ];

        return view('admin.contacts.index', $data);
    }

    public function show(string $id): View
    {
        $data = [
            'item' => Contact::find($id),
        ];

        if (empty($data['item'])) abort(404);

        return view('admin.contacts.show', $data);
    }
}
