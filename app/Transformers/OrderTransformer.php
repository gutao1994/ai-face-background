<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Order;

class OrderTransformer extends TransformerAbstract
{

    public function transform(Order $order)
    {
        return $order->attributesToArray();
    }



}


