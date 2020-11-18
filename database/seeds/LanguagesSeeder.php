<?php

use Illuminate\Database\Seeder;
use App\Models\Language;

class LanguagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $languages = [
            [1, 'Français', 'Français', 'fr'],
        ];

        foreach ($languages as $l) {
            $language_array = [
                'id' => $l[0],
                'name' => $l[1],
                'lang_name' => $l[2],
                'short' => $l[3],
            ];
            $language = Language::find($language_array['id']);
            if (!$language) {
                Language::create($language_array);
            } else {
                $language->update($language_array);
            }
        }
    }
}
