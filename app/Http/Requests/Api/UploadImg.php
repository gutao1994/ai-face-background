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
            'img' => [
                'bail',
                'required',
                'max:2048',
                'mimetypes:image/bmp,image/jpeg,image/pjpeg,image/png,image/x-png,image/webp',
                'dimensions:min_width=200,min_height=200,max_width=4096,max_height=4096'
            ],
        ];
    }

    public function messages()
    {
        return [
            'no.required' => '不合法的订单号',
            'img.required' => '请上传照片',
            'img.uploaded' => '照片上传失败',
            'img.max' => '照片不能超过2M',
            'img.mimetypes' => '不合法的图片类型',
            'img.dimensions' => '照片最小200*200像素，最大4096*4096像素',
        ];
    }



}




