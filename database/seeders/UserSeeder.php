<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Options;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Admin',
                'email' => Options::userEmails()->get('admin'),
                'password' => Hash::make(config('sb.seed.admin_password')),
            ],
            // [
            //     'name' => 'Editor',
            //     'email' => Options::userEmails()->get('editor'),
            //     'password' => Hash::make(config('sb.seed.admin_password')),
            // ],
        ];

        foreach ($data as $item) {
            $item += [
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];

            DB::table('users')->insert($item);
        }
    }
}
