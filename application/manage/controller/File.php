<?php
namespace app\manage\controller;

use app\common\model\FileModel;
use app\common\tool\Tool;
use think\Request;

class File extends BasicController
{

    public function __construct()
    {
        parent::__construct();
    }


    public function uploadPicture()
    {
        if($this->request->isPost()){
            $data = [];
            foreach((array)$_FILES as $k => $v){
               $data = Tool::get('file')->fileUpload($this->request, $k, 'image');
            }

            if(empty($data)){
                $data = [
                    'code'      => 0,
                    'msg'       => '请选择上传文件',
                ];
            }
            //图片上传的途径
            switch($this->request->param('type')){
                case 'editor': //编辑器
                    echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction({$_REQUEST["CKEditorFuncNum"]},'".$data['path']."','');</script>";die;
                case 'inputImgae': //单张图片
                    return json($data);
                    break;
                default:
                    return abort(404, lang('404 not found'));
                    break;
            }
        }
        $this->view->engine->layout(false);
        $get            = $this->request->get();

        $extensions     = Tool::get('file')->fileType('image');
        $info   = [
            'multi'                     => $get['multi'],
            'mime_type'                 => "{'title':'Image files','extensions':'{$extensions['ext']}'}",
            'upload_max_filesize_mb'    => 10,
            'app'                       => $get['app']
        ];
        return $this->fetch('',[
            'info'  =>  $info
        ]);
    }


}
