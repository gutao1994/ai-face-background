<?php
declare(strict_types=1);

namespace App\Services\Tools;

class SystemTransForm
{

    public static function ten2SixtyTwo($int)
    {
        $table = [
            '0','1','2','3','4','5','6','7','8','9',
            'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
            'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
        ];
        $num = 62;
        $arr = [];
        $loop = true;

        while($loop){
            $arr[] = $table[$int % $num];
            $int = floor($int / $num);
            if($int == 0) $loop = false;
        }

        return implode('', array_reverse($arr));
    }


}
