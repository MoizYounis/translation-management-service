<?php

namespace App\Observers;

use App\Models\Translation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class TranslationObserver
{
    /**
     * Handle the Translation "created" event.
     */
    public function created(Translation $t): void
    {
        Redis::hset("translations_export_{$t->locale}", $t->key, $t->value);
        Redis::sadd("translations_locales", $t->locale);
    }

    /**
     * Handle the Translation "updated" event.
     */
    public function updated(Translation $t): void
    {
        Redis::hset("translations_export_{$t->locale}", $t->key, $t->value);
    }

    /**
     * Handle the Translation "deleted" event.
     */
    public function deleted(Translation $t): void
    {
        Redis::hdel("translations_export_{$t->locale}", $t->key);
    }
}
