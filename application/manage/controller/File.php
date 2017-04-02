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

    public function index()
    {
        $groups = lang('file groups');
        $get    = $this->request->get();
        $where  = '';

        if(!empty($get['groups'])){
            $where['groups']  = $get['groups'];
        }
        if(!empty($get['name'])){
            $where['name|path'] =['like',"%{$get['name']}%"];
        }

        $list   = FileModel::where($where)->paginate(18, '' , ['query' => $get]);

        return $this->fetch('',[
            'navTabs'   => parent::navTabs(['文件列表' => ['url' => 'file/index']]),
            'list'      => $list,
            'groups'    => $groups,
            'page'  => $list->render(),
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
        $this->view->engine->layout(false);
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


}
