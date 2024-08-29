<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employeeObj = new User();
        $employeeObj->name = 'Pracownik Pracowniczy';
        $employeeObj->email = 'prac@prac.com';
        $employeeObj->password = Hash::make('123');
        $employeeObj->type = 0;
        $employeeObj->save();

        $adminObj = new User();
        $adminObj->name = 'Admin Adminowniczy';
        $adminObj->email = 'admin@admin.com';
        $adminObj->password = Hash::make('123');
        $adminObj->type = 1;
        $adminObj->save();

    }
}
