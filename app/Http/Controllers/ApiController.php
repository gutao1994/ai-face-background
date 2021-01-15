<?php

namespace App\Http\Controllers;

use Dingo\Api\Routing\Helpers;

class ApiController extends Controller
{
    use Helpers {
        __get as _helper_get;
    }

    public function __get($key)
    {
        try {
            return parent::__get($key);
        } catch (\Exception $exception) {
            return $this->_helper_get($key);
        }
    }



}
