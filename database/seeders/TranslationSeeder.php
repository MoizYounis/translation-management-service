<?php

namespace Database\Seeders;

use App\Models\Translation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Redis;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Redis::executeRaw(['CONFIG', 'SET', 'stop-writes-on-bgsave-error', 'no']);
        Translation::factory()->count(100000)->create();
        Redis::executeRaw(['CONFIG', 'SET', 'stop-writes-on-bgsave-error', 'yes']);
    }
}
