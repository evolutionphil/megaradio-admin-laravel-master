<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        $users = [
//            [
//                'name' => 'Admin',
//                'email' => 'admin@gmail.com',
//                'role' => User::ROLES['ADMIN'],
//                'password' => bcrypt('password'),
//            ],
//            [
//                'name' => 'Test User',
//                'email' => 'test@gmail.com',
//                'role' => User::ROLES['USER'],
//                'password' => bcrypt('password'),
//            ],
//        ];
//
//        foreach ($users as $user) {
//            User::firstOrCreate($user);
//        }

        User::factory()->count(100)->create();
    }
}
