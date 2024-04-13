<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class roles extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // add admin role
        \App\Models\Role::create([
            'name' => 'admin',
            'description' => 'Admin Role'
        ]);
        // add user role
        \App\Models\Role::create([
            'name' => 'editor',
            'description' => 'Editor Role'
        ]);

        //authr
        \App\Models\Role::create([
            'name' => 'author',
            'description' => 'Author Role'
        ]);
        // subscriber
        \App\Models\Role::create([
            'name' => 'subscriber',
            'description' => 'Subscriber Role'
        ]);
        
       


    }
}
