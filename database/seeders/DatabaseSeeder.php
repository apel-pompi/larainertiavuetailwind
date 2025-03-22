<?php

namespace Database\Seeders;

use App\Models\Listing;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::where('email', 'admin@admin.com')->first();
        if (is_null($user)) {
            $user = new User();
            $user->name = "Md. Ashrafur Rahman";
            $user->username = "superadmin";
            $user->email = "admin@admin.com";
            $user->password = Hash::make('Admin@123');
            $user->save();
        }
        $this->call(PermissionGroupModelSeeder::class);
        $this->call(RolePermissionSeeder::class);
        Listing::factory(20)->create();
    }
}
