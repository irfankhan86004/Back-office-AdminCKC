<?php

use Illuminate\Database\Seeder;

use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = Setting::find(1);
        if ($settings === null) {
            $settings = Setting::create([
                'id' => 1,
                'city' => null,
                'zipcode' => null,
                'address' => null,
                'phone' => null,
                'email' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
