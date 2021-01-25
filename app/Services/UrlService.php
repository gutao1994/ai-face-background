<?php

declare(strict_types=1);

namespace App\Services;

class UrlService
{

    /**
     * 附加query string到url后面
     */
    public function appendQueryToUrl($url, $key, $value)
    {
        $urlArr = parse_url($url);

        $resUrl = $urlArr['scheme'] . '://' . $urlArr['host'];

        if (isset($urlArr['port']))
            $resUrl .= (':' . $urlArr['port']);

        if (isset($urlArr['path']))
            $resUrl .= $urlArr['path'];

        if (isset($urlArr['query'])) {
            parse_str($urlArr['query'], $queryArr);
            $queryArr[$key] = $value;
            $resUrl .= '?' . http_build_query($queryArr);
        } else {
            $resUrl .= "?{$key}={$value}";
        }

        return $resUrl;
    }



}




