<?php
namespace app\appadmin\controller;

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

            foreach((array)$_FILES as $k => $v){
               $data = Tool::get('file')->uploadPicture($this->request, $k);
            }

            if($data['code'] ==1 ){

                //图片写入数据库利于管理
                FileModel::create([
                    'path'      => $data['saveName'],
                    'name'      => $data['info']['name'],
                    'type'      => 'img',
                    'hash'      => $data['hash'],
                ]);

                $json = [
                    'preview_url'   => $data['saveName'],
                    'filepath'      => $data['saveName'],
                    'url'           => $data['saveName'],
                    'referer'       => $data['saveName'],
                    'name'          => $data['info']['name'],
                    'status'        => 1,
                    'message'       => 'success'
                ];
            }else{
                $json = [
                    'status'        => 0,
                    'message'       => $data['message']

                ];
            }
            return json($json);
        }
        $this->view->engine->layout(false);
        $info   = [
            'multi'                     => 0,
            'mime_type'                 => '{"title":"Image files","extensions":"jpg,jpeg,png,gif,bmp4"}',
            'upload_max_filesize_mb'    => 10,
            'app'                       => 'Portal'
        ];
        return $this->fetch('',[
            'info'  =>  $info
        ]);
    }


}
