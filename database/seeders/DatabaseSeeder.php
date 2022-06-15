<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::query()->create([
            'first_name' => 'admin',
            'last_name' => 'admin',
            'email' => 'admin@gmail.com',
            'email_verified_at' => date('Y-m-d h:i:s'),
            'password' => Hash::make('12345678'),
        ]);

        $this->call([
            MemberSeeder::class
        ]);
    }
}
