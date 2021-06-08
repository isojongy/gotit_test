<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gift extends Model
{

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gifts';

    /**
     * ambStatus relationship.
     */
    public function lotteryLog()
    {
        return $this->hasMany(lotteryLog::class);
    }
}
