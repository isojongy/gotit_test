<?php

namespace App\Repositories;

use App\Exceptions\DeleteFailException;
use App\Exceptions\UpdateFailException;
use Prettus\Repository\Eloquent\BaseRepository;

abstract class Repository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(app());
    }

    /**
     * Trigger static method calls to the model.
     *
     * @param $method
     * @param $arguments
     *
     * @return mixed
     */
    public static function __callStatic($method, $arguments)
    {
        $instance = new static();

        return $instance->$method(...$arguments);
    }

    /**
     * findByCond.
     *
     * @author Viet Ngo <hoangviet-ngo@am-bition.vn>
     * @param select
     * @param where
     * @return object
     */
    public function findByCond($select = '*', $where = [], $whereRaw = [])
    {
        $model = $this->makeModel();

        return $model->select($select)
                    ->where($where)
                    ->where(function ($query) use ($whereRaw) {
                        if (! empty($whereRaw) && count($whereRaw)) {
                            foreach ($whereRaw as $raw) {
                                $query->where($raw[0], $raw[1], $raw[2]);
                            }
                        }
                    })
                    ->get();
    }

    /**
     * deleteByConditions.
     *
     * @author Viet Ngo <hoangviet-ngo@am-bition.vn>
     * @param where
     * @return object
     */
    public function deleteByConditions($where = [])
    {
        try {
            $model = $this->makeModel();
            $records = $model->select('id')
                        ->where($where)
                        ->get();

            if (! empty($records) && count($records)) {
                foreach ($records as $record) {
                    $modelFind = $model->find($record->id);
                    $modelFind->delete();
                }
            }

            return true;
        } catch (\Illuminate\Database\QueryException $e) {
            \Log::error('deleteByConditions err');
            throw new DeleteFailException($e->getMessage(), 0, $e);
        }
    }

    /**
     * Update a entity in repository by conditions.
     *
     * @author Viet Ngo <hoangviet-ngo@am-bition.vn>
     * @param array $attributes
     * @param       $where
     *
     * @return int
     */
    public function updateFirstByConditions(array $attributes, array $where)
    {
        try {
            $model = $this->makeModel();
            $model = $model->where($where)->first();
            if (empty($model)) {
                return false;
            }

            foreach ($attributes as $key => $value) {
                $model->{$key} = $value;
            }

            return $model->save();
        } catch (\Illuminate\Database\QueryException $e) {
            \Log::error('updateFirstByConditions err');
            throw new UpdateFailException($e->getMessage(), 0, $e);
        }
    }

    /**
     * Delete a entity in repository by conditions.
     *
     * @author Viet Ngo <hoangviet-ngo@am-bition.vn>
     * @return int
     */
    public function deleteFirstByConditions(array $where)
    {
        try {
            $model = $this->makeModel();
            $model = $model->where($where)->first();
            if (empty($model)) {
                return false;
            }

            return $model->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            \Log::error('deleteFirstByConditions err');
            throw new DeleteFailException($e->getMessage(), 0, $e);
        }
    }

    /**
     * getLatestRecord.
     *
     * @author Viet Ngo <hoangviet-ngo@am-bition.vn>
     * @return object
     */
    public function getLatestRecord($isCheckIsActive = true)
    {
        $model = $this->makeModel();
        if ($isCheckIsActive) {
            return $model->orderBy('id', 'desc')->first();
        } else {
            return $model->withoutGlobalScope('active')->orderBy('id', 'desc')->first();
        }
    }

    /**
     * findOneByCond.
     *
     * @author Viet Ngo <hoangviet-ngo@am-bition.vn>
     * @param select
     * @param where
     * @return object
     */
    public function findOneByCond($select = '*', $where = [])
    {
        $model = $this->makeModel();

        return $model->select($select)
                    ->where($where)
                    ->first();
    }
}
