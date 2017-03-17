<?php
namespace app\common\base;

use think\Controller;

class BaseController extends Controller
{

    public function __construct()
    {
        ini_set("error_reporting","E_ALL & ~E_NOTICE");
        parent::__construct();
    }
}