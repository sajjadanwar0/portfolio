<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeederTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'name' => 'admin',
                'label' => 'admin'
            ],
            [
                'name' => 'employer',
                'label' => 'Employer'
            ],
            [
                'name' => 'employee',
                'label' => 'Employee'
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }

    }
}
