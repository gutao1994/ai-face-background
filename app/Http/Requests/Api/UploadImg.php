<?php

namespace App\Http\Requests\Api;

use Dingo\Api\Auth\Auth;
use App\Models\Order;
use App\Logics\OrderLogic;

class UploadImg extends FormRequest
{

    public $requestOrder;

    public function rules(OrderLogic $orderLogic, Auth $auth)
    {
        return [
            'no' => ['required', function ($attribute, $value, $fail) use ($orderLogic, $auth) {
                $this->requestOrder = $orderLogic->checkStepOrder($value, $auth->user(), 10);

                if ($this->requestOrder === false)
                    $fail('订单错误');
            }],
            'img' => ['required'],
        ];
    }

    public function messages()
    {
        return [

        ];
    }



}




