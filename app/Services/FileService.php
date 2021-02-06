<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use App\Common\HandyClass;
use Illuminate\Support\Facades\Storage;

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

    /**
     * 将oss文件 临时保存至 本地
     */
    public function getOssFile($ossPath)
    {
        $localName = md5($ossPath);
        Storage::disk('local')->put($localName, Storage::disk('oss')->get($ossPath));
        return Storage::disk('local')->path($localName);
    }



}







