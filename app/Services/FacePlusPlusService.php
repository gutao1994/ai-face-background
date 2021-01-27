<?php

namespace App\Services;

use App\Common\HandyClass;
use GuzzleHttp\Client;

class FacePlusPlusService
{

    use HandyClass;

    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api-cn.faceplusplus.com',
            'timeout' => 30,
            'http_errors' => false, //禁用HTTP协议抛出的异常(如 4xx 和 5xx 响应)
        ]);
    }

    /**
     * 面部特征
     */
    public function facialfeatures($params)
    {
        return $this->getResponse($params, '/facepp/v1/facialfeatures');
    }

    /**
     * 皮肤分析
     */
    public function skinanalyze($params)
    {
        return $this->getResponse($params, '/facepp/v1/skinanalyze');
    }

    /**
     * detect
     */
    public function detect($params)
    {
        return $this->getResponse($params, '/facepp/v3/detect');
    }

    /**
     * 构建请求数据
     */
    protected function buildSendData($params)
    {
        $result = [];

        array_push($result, [
            'name' => 'api_key',
            'contents' => config('faceplusplus.key'),
        ], [
            'name' => 'api_secret',
            'contents' => config('faceplusplus.secret'),
        ]);

        foreach ($params as $key => $val) {
            if ($key == 'image_file')
                $val = fopen($val, 'r');

            array_push($result, [
                'name' => $key,
                'contents' => $val,
            ]);
        }

        return $result;
    }

    /**
     * 获取请求响应
     */
    protected function getResponse($params, $uri)
    {
        return $this->client->post($uri, [
            'multipart' => $this->buildSendData($params)
        ]);
    }

    /**
     * 解析错误信息
     */
    public function parseErrorMessage($message)
    {
        if (preg_match('/IMAGE_ERROR_UNSUPPORTED_FORMAT/', $message)) {
            return '照片的文件格式不符合要求';
        } elseif ($message == 'NO_FACE_FOUND') {
            return '没有检测到人脸';
        } elseif ($message == 'INVALID_IMAGE_FACE') {
            return '照片人脸角度不符合要求 或 人脸不完整 或 出现多个人脸';
        } elseif (preg_match('/INVALID_IMAGE_SIZE/', $message)) {
            return '照片像素尺寸太大或太小';
        } elseif ($message == 'INVALID_IMAGE_URL') {
            return '照片URL错误或者无效';
        } elseif ($message == 'INVALID_IMAGE_T') {
            return '人脸照片无法检测出右眼瞳孔半径';
        } elseif (preg_match('/IMAGE_FILE_TOO_LARGE/', $message)) {
            return '照片文件太大';
        } elseif ($message == 'IMAGE_DOWNLOAD_TIMEOUT') {
            return '下载图片超时';
        } elseif ($message == 'AUTHENTICATION_ERROR') {
            return '服务器出错';
        } elseif (preg_match('/AUTHORIZATION_ERROR/', $message)) {
            return '服务器出错';
        } elseif ($message == 'CONCURRENCY_LIMIT_EXCEEDED') {
            return '并发数超过限制';
        } elseif (preg_match('/MISSING_ARGUMENTS/', $message)) {
            return '服务器出错';
        } elseif (preg_match('/BAD_ARGUMENTS/', $message)) {
            return '服务器出错';
        } elseif ($message == 'COEXISTENCE_ARGUMENTS') {
            return '服务器出错';
        } elseif ($message == 'API_NOT_FOUND') {
            return '服务器出错';
        } elseif ($message == 'INTERNAL_ERROR') {
            return '服务器出错';
        }

        return $message;
    }



}












