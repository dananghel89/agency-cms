<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PortfolioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $title = 'Project Name';
        $subtitle = 'Lorem ipsum dolor sit amet consectetur.';

        $content = str('
            Use this area to describe your project.
            Lorem ipsum dolor sit amet, consectetur adipisicing elit.
            Est blanditiis dolorem culpa incidunt minus dignissimos
            deserunt repellat aperiam quasi sunt officia expedita
            beatae cupiditate, maiores repudiandae, nostrum, reiciendis facere nemo!
        ')->squish();

        $data = [
            [
                'client' => 'Threads',
                'category' => 'Illustration',
            ],
            [
                'client' => 'Explore',
                'category' => 'Graphic Design',
            ],
            [
                'client' => 'Finish',
                'category' => 'Identity',
            ],
            [
                'client' => 'Lines',
                'category' => 'Branding',
            ],
            [
                'client' => 'Southwest',
                'category' => 'Website Design',
            ],
            [
                'client' => 'Window',
                'category' => 'Photography',
            ],
        ];

        foreach ($data as $item) {
            $item += [
                'title' => $title,
                'subtitle' => $subtitle,
                'content' => $content,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];

            DB::table('portfolio')->insert($item);
        }
    }
}
