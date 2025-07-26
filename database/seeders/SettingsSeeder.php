<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            'radio-browser::is_sync_enabled' => false,
            'radio-browser::status' => 'idle', // ['idle', 'running', 'failed']
            'radio-browser::last_sync_start' => null,
            'radio-browser::last_sync_stop' => null,

            'social-links::facebook' => 'https://facebook.com',
            'social-links::twitter' => 'https://twitter.com',
            'social-links::instagram' => 'https://instagram.com',
            'site-settings::enable_uncategorized_genre' => false,
        ];

        foreach ($settings as $key => $value) {
            Setting::firstOrCreate([
                'name' => $key,
            ], [
                'value' => $value,
            ]);
        }
    }
}
