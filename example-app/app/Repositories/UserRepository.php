<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends Repository
{

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return User::class;
    }

    public function getByConditions($conditions)
    {
        return User::where($conditions)
                    ->join('lotteries', function ($join) {
                        $join->on("lotteries.user_phone", '=', 'users.phone');
                    })
                    ->get();
    }

    public function getById($id)
    {
        return User::find($id);
    }

}
