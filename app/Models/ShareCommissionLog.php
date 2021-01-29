<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShareCommissionLog extends Model
{
    use HasFactory;

    protected $table = 'share_commission_logs';

    public function user()
    {
        return $this->belongsTo(WxUser::class, 'user_id', 'id');
    }



}
