<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(string $slug): View
    {
        $page = Page::published()->where('slug', $slug)->firstOrFail();

        return view('pages.default', ['page' => $page]);
    }
}
