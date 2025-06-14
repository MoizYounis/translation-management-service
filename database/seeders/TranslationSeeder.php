<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class TranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Redis::executeRaw(['CONFIG', 'SET', 'stop-writes-on-bgsave-error', 'no']);
        $batch = [];
        $count = 100000;

        for ($i = 0; $i < $count; $i++) {
            $batch[] = [
                'locale' => fake()->randomElement(['en', 'fr', 'es', 'de']),
                'key' => Str::slug(fake()->unique()->words(3, true), '_'),
                'value' => fake()->sentence,
                'tags' => json_encode([fake()->randomElement(['web', 'mobile', 'desktop'])]),
                'cdn_ready' => fake()->boolean(20),
            ];

            if ($i % 1000 === 0) {
                DB::table('translations')->insert($batch);
                $batch = [];
                echo "Inserted $i records\n";
            }
        }

        if (!empty($batch)) {
            DB::table('translations')->insert($batch);
        }
        Redis::executeRaw(['CONFIG', 'SET', 'stop-writes-on-bgsave-error', 'yes']);
    }
}
