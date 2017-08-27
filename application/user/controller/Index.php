<?php
namespace app\user\controller;

class Index extends BasicController
{
    public function index()
    {
        $model =  $model = $this->request->getUser()->formatUsers()->offsetGet(0);;
        return $this->fetch('' , [
            'info' => $model
        ]);
    }

}