<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Options;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

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
                'value' => Options::userEmails()->get('admin'),
            ],
            [
                'name' => 'social_links',
                'value' => Options::socialNetworks()->pluck('url', 'name')->toJson(),
            ],
        ];

        foreach ($data as $item) {
            unset( $item['array'] );

            $item += [
                'created_at' => Carbon::now(),
            ];

            DB::table('options')->insert($item);
        }
    }
}
