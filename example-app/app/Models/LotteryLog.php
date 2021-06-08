<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LotteryLog extends Model
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
    protected $table = 'lottery_logs';

    /**
     * gift relationship.
     */
    public function gift()
    {
        return $this->belongsTo(Gift::class);
    }
}
