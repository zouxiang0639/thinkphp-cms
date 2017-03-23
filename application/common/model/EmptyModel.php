<?php
namespace app\common\model;

class EmptyModel extends BasicModel
{
    public $name = '';

    public function __construct($data)
    {
        parent::__construct($data);
    }

    public static function names($name)
    {
        $model = new EmptyModel();
        $model->name    = $name;
        return $model;
    }

}