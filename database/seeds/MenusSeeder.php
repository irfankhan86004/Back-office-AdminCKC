<?php

use Illuminate\Database\Seeder;

use App\Models\Menu;

class MenusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menu = Menu::where('id', 1)->first();
        if (!$menu) {
            $menu = Menu::create([
                'id' => 1,
                'parent_id' => 0,
                'target' => '_self',
            ]);
        }
    }
}
