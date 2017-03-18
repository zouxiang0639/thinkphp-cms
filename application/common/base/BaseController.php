<?php
namespace app\common\base;

use think\Controller;

class BaseController extends Controller
{

    public function __construct()
    {
        // 关闭错误报告
        // error_reporting(0);
        parent::__construct();
    }
}