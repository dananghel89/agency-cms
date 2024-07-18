<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TimelineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $content = str('
            Lorem ipsum dolor sit amet, consectetur adipisicing elit.
            Sunt ut voluptatum eius sapiente, totam reiciendis temporibus
            qui quibusdam, recusandae sit vero unde, sed, incidunt
            et ea quo dolore laudantium consectetur!
        ')->squish();

        $data = [
            [
                'title' => '2009-2011',
                'subtitle' => 'Our Humble Beginnings',
            ],
            [
                'title' => 'March 2011',
                'subtitle' => 'An Agency is Born',
            ],
            [
                'title' => 'December 2015',
                'subtitle' => 'Transition to Full Service',
            ],
            [
                'title' => 'July 2020',
                'subtitle' => 'Phase Two Expansion',
            ],
        ];

        foreach ($data as $item) {
            $item += [
                'content' => $content,
                'created_at' => Carbon::now(),
            ];

            DB::table('timeline')->insert($item);
        }
    }
}
