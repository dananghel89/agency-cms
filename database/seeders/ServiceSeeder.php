<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $content = str('
            Lorem ipsum dolor sit amet, consectetur adipisicing elit.
            Minima maxime quam architecto quo inventore harum ex magni, dicta impedit.
        ')->squish();

        $data = [
            [
                'title' => 'E-Commerce',
                'icon' => 'shopping-cart',
            ],
            [
                'title' => 'Responsive Design',
                'icon' => 'laptop',
            ],
            [
                'title' => 'Web Security',
                'icon' => 'lock',
            ],
        ];

        foreach ($data as $item) {
            $item += [
                'content' => $content,
                'created_at' => Carbon::now(),
            ];

            DB::table('services')->insert($item);
        }
    }
}
