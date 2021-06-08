<?php

namespace App\Repositories;

use App\Models\Gift;
use App\Models\LotteryLog;
use App\Models\Lottery;
use App\Exceptions\UpdateFailException;

class GiftRepository extends Repository
{

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Gift::class;
    }

    public function getByConditions($conditions)
    {
        return Gift::where($conditions)->get();
    }

    public function getById($id)
    {
        return Gift::find($id);
    }

    public function getLotteryByCode($code, $phone)
    {
        return Lottery::where('codes', 'like', "%{$code}%")
                        ->where('user_phone', $phone)
                        ->first();
    }

    public function getLotteryLog($conditions)
    {
        return LotteryLog::where($conditions)->first();
    }

    public function createLotteryLog($data)
    {
        return LotteryLog::create($data);
    }

    public function getGiftListByUser($phone)
    {
        $lottery = Lottery::select('codes')
                        ->where('user_phone', $phone)
                        ->first();

        if(!empty($lottery)){
            $codes = explode(',', $lottery->codes);
            if(!empty($codes) && count($codes)){
                return LotteryLog::whereIn('lottery_code', $codes)
                            ->join('gifts', function ($join) {
                                $join->on("gifts.id", '=', 'lottery_logs.gift_id');
                            })
                            ->get();
            }
        }

        return [];
    }


    public function updateLotteryFirstByConditions(array $attributes, array $where)
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
            \Log::error('updateLotteryFirstByConditions err');
            throw new UpdateFailException($e->getMessage(), 0, $e);
        }
    }

    public function getLotteryByUser($phone)
    {
        return Lottery::where('user_phone', $phone)
                        ->first();
    }

}
