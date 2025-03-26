<?php

namespace Database\Seeders;

use App\Models\PermissionGroupModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionGroupModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = [
            ['name' => 'Branch'],
            ['name' => 'Users'],
            ['name' => 'Role'],
            ['name' => 'Permission'],
            ['name' => 'Profile'],
            ['name' => 'Listing']
        ];
        
        for ($i=0; $i < count($names); $i++) { 
            $name = $names[$i]['name'];
            $nameExist = PermissionGroupModel::where('name', $name)->first();
            if (is_null($nameExist)) {
                PermissionGroupModel::create(
                    [
                        'name' => $name,
                    ]
                );
            }
        }
        
    }
}
