<?php

namespace App\Http\Controllers\Admin;

use Illuminate\View\View;
use App\Models\Page;
use App\Models\Team;
use App\Models\Options;
use App\Models\Service;
use App\Models\Timeline;
use App\Models\Portfolio;

class DashboardController extends BaseController
{
    public function index(): View
    {
        $data = [
            'featured' => collect([
                'pages' => Page::count(),
                'services' => Service::count(),
                'portfolio' => Portfolio::count(),
                'timeline' => Timeline::count(),
                'team' => Team::count(),
            ]),
        ];

        return view('admin.dashboard.index', $data);
    }

    public function profile(): View
    {
        return view('admin.dashboard.profile');
    }

    public function options(): View
    {
        $data = [
            'socialNetworks' => Options::socialNetworks(),
        ];

        return view('admin.dashboard.options', $data);
    }
}
