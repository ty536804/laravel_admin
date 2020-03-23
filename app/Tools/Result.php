<?php
namespace App\Tools;

class Result
{
    public $code;
    public $msg;
    public $data;
    function __construct()
    {
        $this->code = Constant::ERROR;
        $this->msg = "处理失败";
        $this->data = [];
    }
}