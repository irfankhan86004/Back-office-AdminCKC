<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesSeeder::class);
        $this->call(LanguagesSeeder::class);
        $this->call(AdminsSeeder::class);
        $this->call(SettingsSeeder::class);
        $this->call(MenusSeeder::class);
        $this->call(CountriesSeeder::class);
    }
}
