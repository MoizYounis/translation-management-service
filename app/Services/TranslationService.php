<?php

namespace App\Services;

use App\Models\Translation;
use App\Abstracts\BaseService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use App\Contracts\TranslationContract;

class TranslationService extends BaseService implements TranslationContract
{
    public function __construct()
    {
        $this->model = new Translation();
    }

    public function index($perPage = 10, $data = [])
    {
        $filters = [
            'locale' => $data['locale'] ?? null,
            'key' => $data['key'] ?? null,
            'value' =>  $data['value'] ?? null,
            'tags' => $data['tags'] ?? null
        ];

        return $this->model
            ->select(
                'id',
                'locale',
                'key',
                'value',
                'tags',
                'cdn_ready'
            )
            ->filter($filters)
            ->paginate($perPage);
    }

    public function store($data)
    {
        return $this->prepareData($this->model, $data, true);
    }

    private function prepareData($model, $data, $newRecord = false)
    {
        if (isset($data['locale']) && $data['locale']) {
            $model->locale = $data['locale'];
        }

        if (isset($data['key']) && $data['key']) {
            $model->key = $data['key'];
        }

        if (isset($data['value']) && $data['value']) {
            $model->value = $data['value'];
        }

        if (isset($data['tags']) && $data['tags']) {
            $model->tags = $data['tags'];
        }

        $model->cdn_ready = isset($data['cdn_ready']) && $data['cdn_ready'] ? true : false;

        $model->save();

        return $model;
    }

    public function show($id)
    {
        $keys = [
            'id',
            'locale',
            'key',
            'value',
            'tags',
            'cdn_ready'
        ];
        $translation = $this->getById($id, $keys);
        $this->recordExists($translation);
        return $translation;
    }

    public function update($id, $data)
    {
        $translation = $this->getById($id);
        $this->recordExists($translation);
        return $this->prepareData($translation, $data);
    }

    public function destroy($id)
    {
        $translation = $this->getById($id);
        $this->recordExists($translation);
        $this->delete($translation);

        return true;
    }

    public function export()
    {
        $locales = Redis::smembers("translations_locales");
        $result = [];

        if (empty($locales)) {
            Redis::pipeline(function ($pipe) use (&$result) {
                $this->model->select('locale', 'key', 'value')
                    ->orderBy('locale')
                    ->chunk(1000, function ($translations) use ($pipe, &$result) {
                        foreach ($translations as $t) {
                            $pipe->hset("translations_export_{$t->locale}", $t->key, $t->value);
                            $pipe->sadd("translations_locales", $t->locale);
                            $result[$t->locale][$t->key] = $t->value;
                        }
                    });
            });

            return $result;
        }

        $responses = Redis::pipeline(function ($pipe) use ($locales) {
            foreach ($locales as $locale) {
                $pipe->hgetall("translations_export_{$locale}");
            }
        });

        foreach ($responses as $index => $translations) {
            $result[$locales[$index]] = $translations;
        }

        return $result;
    }
}
