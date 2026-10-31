<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\Service;
use App\Models\Team;
use App\Models\Timeline;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $data = [
            'services' => Service::published()->orderBy('position')->get(),
            'portfolio' => Portfolio::published()->orderBy('position')->get(),
            'timeline' => Timeline::published()->orderBy('position')->get(),
            'team' => Team::published()->orderBy('position')->get(),
        ];

        return view('pages.home', $data);
    }
}
