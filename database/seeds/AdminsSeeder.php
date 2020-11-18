<?php

use Illuminate\Database\Seeder;

use App\Models\Admin;
use App\Models\Role;

class AdminsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Admin::where('email', 'admin@ckc-net.com')->first();
        if ($admin === null) {
            $admin = Admin::create([
                'id' => 1,
                'email' => 'admin@ckc-net.com',
                'last_name' => 'CKC',
                'first_name' => 'Admin',
                'password' => 'password',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            $admin->roles()->sync([1]);
        }
    }
}
