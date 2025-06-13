<?php

namespace App\Abstracts;

use App\Exceptions\CustomException;

class BaseService
{
    protected $model;

    public function getById($id, $keys = '*', $relations = null)
    {
        $query = $this->model->select($keys);
        if ($relations) {
            $query->with($relations);
        }
        return $query->find($id);
    }

    protected function recordExists($record)
    {
        if (empty($record)) {
            throw new CustomException(__('lang.messages.not_found_exception'));
        }
    }

    public function delete($record)
    {
        $record->delete();
    }
}
