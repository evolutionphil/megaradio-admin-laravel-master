<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lang = json_decode(file_get_contents(__DIR__.'/phrases.json'));

        foreach ($lang as $key => $phrases) {
            Language::firstOrCreate([
                'name' => 'Language '.$key,
                'key' => $key,
            ], [
                'phrases' => $phrases,
                'is_rtl' => false,
                'is_published' => false,
            ]);
        }
    }
}
