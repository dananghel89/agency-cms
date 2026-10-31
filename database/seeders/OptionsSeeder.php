<?php

namespace Database\Seeders;

use App\Models\Options;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'site_title',
                'value' => config('app.name'),
            ],
            [
                'name' => 'admin_email',
                'value' => config('project.options.admin_email'),
            ],
            [
                'name' => 'social_links',
                'value' => Options::socialNetworks()->pluck('url', 'name')->toJson(),
            ],
        ];

        foreach ($data as $item) {
            $item += [
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];

            DB::table('options')->insert($item);
        }
    }
}
