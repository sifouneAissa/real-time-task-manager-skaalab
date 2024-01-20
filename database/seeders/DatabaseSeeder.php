<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        $this->call(RolePermissionSeeder::class);

        $user = User::query()->create([
            'name' => 'admin',
            'email' => 'admin@demo.com',
            'password' => Hash::make('password'),
        ]);

        $user->assignRole('admin');



        \App\Models\User::factory(10)->create([
            'password' => Hash::make('password'),
        ]);
    }
}
