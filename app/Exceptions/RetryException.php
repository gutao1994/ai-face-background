<?php

namespace App\Exceptions;

class RetryException extends ActionException
{

    public $actionName = 'retry';

    public $actionMessage = 'retry again';

}


