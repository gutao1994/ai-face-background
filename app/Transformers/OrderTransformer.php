<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Order;

class OrderTransformer extends TransformerAbstract
{

    public function transform(Order $order)
    {
        $attr = $order->attributesToArray();

        if (!empty($attr['img']))
            $attr['img'] = $this->getUrl($attr['img']);

        return $attr;
    }

    protected function getUrl($path)
    {
        return 'https://' . config('filesystems.disks.oss.cdnDomain') . '/' . ltrim($path, '/');
    }



}


