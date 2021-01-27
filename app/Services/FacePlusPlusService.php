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



}












