<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use App\Common\HandyClass;

/**
 * @property \App\Services\OrderService $orderService
 */
class FileService
{

    use HandyClass;

    /**
     * 生成要上传的图片的完全路径
     */
    public function genFileName(UploadedFile $file)
    {
        return $this->orderService->genOrderNum() . '.' . $file->clientExtension();
    }



}







