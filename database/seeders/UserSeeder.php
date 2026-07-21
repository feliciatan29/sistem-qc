<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::truncate(); // Clear existing users

        \App\Models\User::create([
            'name' => 'Admin Produksi',
            'username' => 'produksi',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'admin_produksi',
            'email' => 'produksi@simkajar.com'
        ]);

        \App\Models\User::create([
            'name' => 'Admin QC',
            'username' => 'qc',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'admin_qc',
            'email' => 'qc@simkajar.com'
        ]);
    }
}
