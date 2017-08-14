<?php
namespace app\manage\controller;

use app\common\bls\configure\ConfigureBls;
use app\common\consts\common\CommonFormInputConst;
use app\common\consts\configure\ConfigureTypeConst;
use app\common\model\ConfigureModel;
use app\common\tool\Tool;
use think\Config;

class Configure extends BasicController
{
    private $id;
    private $url        = 'configure/index';

    public function __construct()
    {


        parent::__construct();
        $this->id       = !empty($this->request->param('id')) ? intval($this->request->param('id')) : $this->id;

        $nav = [
            '配置属性列表' => ['url' => 'index'],
            '配置属性增加' => ['url' => 'add'],
            '配置属性修改' => ['url' => ['edit', ['id' => $this->id]], 'style' => "display: none;"],
        ];
        $this->assign('navTabs',  parent::navTabs($nav));
    }

    /**
     * 配置变量列表
     */
    public function index()
    {
        $where  = [];
        $type   = intval($this->request->get('type'));

        if(!empty($type)){
            $where['type']  = $type;
        }

        $model = ConfigureBls::getConfigureList($where);

        return $this->fetch('', [
            'list'  => $model,
        ]);
    }

    /**
     * 配置变量添加
     */
    public function add()
    {
        //add_post 数据处理
        if($this->request->isPost()){
            $post = $this->request->post();

            //数据验证
            $result = $this->validate($post, 'app\common\bls\configure\validate\ConfigureValidate.create');
            if(true !== $result){
                // 验证失败 输出错误信息
                return $this->error($result);
            }

            //写入数据
            if(ConfigureBls::createConfigure($post)){
                return $this->success(lang('Add success'), 'index');
            }else{
                return $this->error(lang('Add error'));
            }
        }

        return $this->fetch('configure', [
        ]);
    }

    /**
     * 配置变量修改
     */
    public function edit()
    {
        $model = ConfigureBls::getOneConfigure(['configure_id' => $this->id]);

        if(empty($model)){
            return $this->error('参数错误');
        }

        return $this->fetch('configure', [
            'info'  => $model
        ]);
    }

    /**
     * 配置变量更新数据
     */
    public function update()
    {
        //edit_post 数据处理
        if($this->request->isPost()){
            $post   = $this->request->post();

            //数据验证
            $result = $this->validate($post, 'app\common\bls\configure\validate\ConfigureValidate.update');
            if(true !== $result){
                // 验证失败 输出错误信息
                return $this->error($result);
            }

            //查询数据
            $model  = ConfigureBls::getOneConfigure(['configure_id' => $this->id]);
            if(empty($model)){
                return $this->error('参数错误');
            }

            //修改数据库
            if($model->save($post)){
                return $this->success(lang('Edit success'),$this->url);
            }else{
                return $this->error(lang('Edit failed'));
            }
        }
        return $this->error('请求错误');
    }

    /**
     * 配置变量删除
     */
    public function delete()
    {
        $delete = ConfigureBls::getOneConfigure(['configure_id' => $this->id]);

        if(!$this->request->isAjax() || empty($delete)){
            return $this->error('请求错误');
        }

        //数据删除
        if($delete->delete()){
            return $this->success(lang('Delete success'),url($this->url));
        }else{
            return $this->error(lang('Delete failed'));
        }
    }

    /**
     * 基本配置
     */
    public function basicSettings()
    {

        $model       = ConfigureBls::getConfigureSelect(['type' => ConfigureTypeConst::BASIC]);
        $fileName   = 'basic';
        return $this->fetch('show',[
            'html'  => ConfigureBls::htmlBuilder($model,$fileName),
            'info'  => [
                'class_name'    => ConfigureTypeConst::BASIC_DESC,
                'file_name'     => $fileName
            ]
        ]);
    }

    /**
     * 邮箱配置
     */
    public function emailSettings()
    {

        $list       = ConfigureBls::getConfigureSelect(['type' => ConfigureTypeConst::EMAIL]);
        $fileName   = 'email';

        return $this->fetch('show',[
            'html'  => ConfigureBls::htmlBuilder($list,$fileName),
            'info'  => [
                'class_name'    => ConfigureTypeConst::EMAIL_DESC,
                'file_name'     => $fileName
            ]
        ]);
    }

    /**
     * 生成配置文件
     * push   /config/extra
     */
    public function configBuilder()
    {
        //生成配置文件数据处理
        if($this->request->isPost()){
            $post   = $this->request->post();
            $pash   = CONF_PATH.'extra'.DS.$post['file_name'].EXT;
            $txt    = '

        //'.$post['class_name'].'

        ';
            $list   = ConfigureBls::getConfigureSelect(['type' => array_search($post['class_name'], ConfigureTypeConst::desc())]);
            if(empty($list)){
                return $this->error('配置属性组数据找不到');
            }


            $tool   = Tool::get('file');

            //重组数组阵列
            foreach ($list as $v) {
                $configureName = isset($post[$v['configure_name']])?$post[$v['configure_name']]:'';
                if(is_array($configureName)){
                    $data   = $tool->recombinantArray($configureName,$v['configure_value']);
                }else{
                    $data   = "'{$configureName}'";
                }
                $txt.="'".$v['configure_name']."'  =>  $data, //".$v['title'].'
        ';
            }

            //写入到配置config/extr/目录下
            $content = '<?php
return ['.$txt.'];
?>';
            $thisContent = $tool->writeFile($pash,$content);
            if ($thisContent){
                return $this->success(lang('edit success'));
            }else{
                return $this->error('更改失败,请检查网站目录'.$pash.'权限');
            }
        }
        return $this->success('请求错误');

    }
}