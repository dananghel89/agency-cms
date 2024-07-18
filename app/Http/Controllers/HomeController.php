<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Service;
use App\Models\Timeline;
use App\Models\Portfolio;
use App\Models\Page;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $data = [
            'services' => Service::all()->sortBy('position'),
            'portfolio' => Portfolio::all()->sortBy('position'),
            'timeline' => Timeline::all()->sortBy('position'),
            'team' => Team::all()->sortBy('position'),
        ];

        return view('pages.home', $data);
    }
}
