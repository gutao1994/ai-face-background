<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    public function user()
    {
        return $this->belongsTo(WxUser::class, 'user_id', 'id');
    }

    public function shareUser()
    {
        return $this->belongsTo(WxUser::class, 'share_user_id', 'id');
    }



}
