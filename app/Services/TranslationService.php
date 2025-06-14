<?php

namespace App\Services;

use App\Models\Translation;
use App\Abstracts\BaseService;
use Illuminate\Support\Facades\Redis;
use App\Contracts\TranslationContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TranslationService extends BaseService implements TranslationContract
{
    public function __construct()
    {
        $this->model = new Translation();
    }

    public function index($perPage = 10, $data = []): LengthAwarePaginator
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

    public function store($data): Translation
    {
        return $this->prepareData($this->model, $data, true);
    }

    private function prepareData($model, $data, $newRecord = false): Translation
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

    public function show($id): Translation
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

    public function update($id, $data): Translation
    {
        $translation = $this->getById($id);
        $this->recordExists($translation);
        return $this->prepareData($translation, $data);
    }

    public function destroy($id): bool
    {
        $translation = $this->getById($id);
        $this->recordExists($translation);
        $this->delete($translation);

        return true;
    }

    public function export(): array
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

        foreach ($locales as $locale) {
            $result[$locale] = Redis::hgetall("translations_export_{$locale}");
        }

        return $result;
    }
}
