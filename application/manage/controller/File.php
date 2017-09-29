<?php
namespace app\manage\controller;

use app\common\bls\file\FileBls;
use app\common\consts\file\FileTypeConst;
use app\common\tool\Tool;
use think\Request;
use app\common\bls\file\traits\FileTrait;

class File extends BasicController
{

    use FileTrait;

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $get    = $this->request->get();
        $where  = '';

        if(!empty($get['type'])){
            $where['type']  = $get['type'];
        }
        if(!empty($get['name'])){
            $where['name|path'] =['like',"%{$get['name']}%"];
        }
        $model = FileBls::getFileList($where);
        $this->formatFile($model->getCollection());
        return $this->fetch('',[
            'navTabs'   => parent::navTabs(['文件列表' => ['url' => 'index']]),
            'list'      => $model,
        ]);
    }

    public function upload($data = 'json')
    {

        $param            = $this->request->param();

        if($this->request->isPost()){
            $file = [];
            foreach((array)$_FILES as $k => $v){
                $file = Tool::get('file')->fileUpload($this->request, $k, $param['type'], array_get($param, 'id', '0'));
            }

            if(empty($file)){
                $file = [
                    'code'      => 0,
                    'msg'       => '请选择上传文件',
                ];
            }

            //图片上传的途径
            switch($data){
                case 'echo': //编辑器
                    echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction({$_REQUEST["CKEditorFuncNum"]},'".$file['path']."','');</script>";die;
                case 'json': //单张图片
                    return json($file);
                    break;
                default:
                    return abort(404, lang('404 not found'));
                    break;
            }
        }

    }

    public function plupload()
    {

        $param              = $this->request->param();
        $extensions         = Tool::get('file')->fileType($param['filetype']);
        $info   = [
            'multi'                     => $param['multi'],
            'extensions'                => $extensions['ext'],
            'mime_type'                 => "{'title':'{$extensions['title']}','extensions':'{$extensions['ext']}'}",
            'upload_max_filesize_mb'    => $extensions['upload_max_filesize'],
            'app'                       => $param['app']
        ];
        return $this->fetch('',[
            'info'  =>  $info
        ]);
    }

    public function uploadFile()
    {
        if(empty($_GET['type'])) {
            $_GET['type'] = 1;
        }
        $param              = $this->request->param();
        $type = FileTypeConst::getEn($param['type']);

        $extensions         = Tool::get('file')->fileType($type);

        $info   = [
            'multi'                     => 20,
            'extensions'                => $extensions['ext'],
            'mime_type'                 => "{'title':'{$extensions['title']}','extensions':'{$extensions['ext']}'}",
            'upload_max_filesize_mb'    => $extensions['upload_max_filesize'],
            'app'                       => 'Portal'
        ];
        return $this->fetch('',[
            'info'  =>  $info,
            'type' => $type
        ]);
    }


}
