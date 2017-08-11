<?php
namespace app\common\base;

use think\Validate;

abstract class BaseValidate extends Validate
{

    public function __construct(array $rules = [], $message = [], $field = [])
    {
        $this->rule     = $this->setRule();
        $this->message  = $this->setMessage();
        $this->scene    = $this->setScene();

        parent::__construct($rules, $message, $field);
    }

    abstract function setRule();
    abstract function setMessage();
    abstract function setScene();

}