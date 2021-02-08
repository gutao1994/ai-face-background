<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Order;
use App\Common\HandyClass;

/**
 * @property \App\Services\FileService $fileService
 */
class OrderTransformer extends TransformerAbstract
{

    use HandyClass;

    public function transform(Order $order)
    {
        $attr = $order->attributesToArray();

        if (!empty($attr['img']))
            $attr['img'] = $this->fileService->genOssUrl($attr['img']);

        return $attr;
    }



}


