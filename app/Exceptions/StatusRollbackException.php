<?php

namespace App\Exceptions;

class StatusRollbackException extends ActionException
{

    public $actionName = 'status_rollback';

    public $actionMessage;

    public function __construct($actionMessage)
    {
        $this->actionMessage = $actionMessage;

        parent::__construct();
    }



}



