<?php

namespace Database\Seeders;

use App\Models\Options;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $socialLinks = Options::socialNetworks()->pluck('url', 'name')->toJson();

        $data = [
            [
                'name' => 'Parveen Anand',
                'profession' => 'Lead Designer',
            ],
            [
                'name' => 'Diana Petersen',
                'profession' => 'Lead Marketer',
            ],
            [
                'name' => 'Larry Parker',
                'profession' => 'Lead Developer',
            ],
        ];

        foreach ($data as $item) {
            $item += [
                'social_links' => $socialLinks,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];

            DB::table('team')->insert($item);
        }
    }
}
