<?php

namespace App\Repositories;

use App\Models\Lottery;

class LotteryRepository extends Repository
{

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Lottery::class;
    }

    public function getByConditions($conditions)
    {
        return Lottery::where($conditions)->get();
    }

    public function getById($id)
    {
        return Lottery::find($id);
    }

}
