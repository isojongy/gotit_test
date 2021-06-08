<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lottery extends Model
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
    protected $table = 'lotteries';

    /**
     * user relationship.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_phone', 'phone');
    }
}
