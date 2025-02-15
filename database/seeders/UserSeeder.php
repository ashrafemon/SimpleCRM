<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::query()->truncate();

        $users = [
            ['name' => 'Admin', 'email' => 'admin@crm.com', 'role' => 'ADMIN', 'password' => '123456'],
            ['name' => 'Counselor 1', 'email' => 'counselor1@crm.com', 'password' => '123456'],
            ['name' => 'Counselor 2', 'email' => 'counselor2@crm.com', 'password' => '123456'],
            ['name' => 'Counselor 3', 'email' => 'counselor3@crm.com', 'password' => '123456'],
            ['name' => 'Counselor 4', 'email' => 'counselor4@crm.com', 'password' => '123456'],
        ];

        foreach ($users as $user) {
            User::query()->create($user);
        }
    }
}
