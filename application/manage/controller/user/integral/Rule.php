<?php
namespace app\manage\controller\user\integral;

use app\common\bls\banner\BannerBls;
use app\common\bls\integral\IntegralRuleBls;
use app\common\bls\integral\traits\IntegralRuleTrait;
use app\common\consts\common\CommonSwitchConst;
use app\common\library\integral\IntegralRule;
use app\manage\controller\BasicController;

class Rule extends BasicController
{
    use IntegralRuleTrait;

    private $id     = 0;

    public function __construct()
    {
        parent::__construct();
        $this->id       = !empty($this->request->param('id')) ? intval($this->request->param('id')) : $this->id;
        $nav = [
            '规则列表' => ['url' => 'index'],
            '规则增加' => ['url' => 'add'],
            '规则修改' => ['url' => ['edit', ['id' => $this->id]], 'style' => "display: none;"],
        ];
        $this->assign('navTabs',  parent::navTabs($nav));
    }

    public function index()
    {
        (new IntegralRule())->checkIntegralRule(3, 1);
        $where  = [];
        if(!empty($this->id)){
            $where['integral_rule_id']  = $this->id;
        }

        $model = IntegralRuleBls::getIntegralRuleList($where);
        $this->formatIntegralRule($model->getCollection());

        return $this->fetch('',[
            'list'      => $model,
        ]);
    }

    /**
     * 幻灯片添加
     */
    public function add()
    {
        //Add_post 数据处理
        if($this->request->isPost()){
            $post   = $this->request->post();

            $result = $this->validate($post, 'app\common\bls\integral\validate\IntegralRuleValidate.create');
            if(true !== $result){
                // 验证失败 输出错误信息
                return $this->error($result);
            }

            //写入数据库
            if(IntegralRuleBls::createIntegralRule($post)){
                return $this->success('添加成功', url('index'));
            }else{
                return $this->error('添加失败');
            }

        }

        return $this->fetch('rule');
    }

    /**
     * 幻灯片修改
     */
    public function edit()
    {

        $model = IntegralRuleBls::getOneIntegralRule(['integral_rule_id' => $this->id]);
        if(empty($model)){
            return $this->error('参数错误');
        }
        return $this->fetch('rule',[
            'info'   => $model
        ]);
    }

    /**
     * 幻灯片更新
     */
    public function update()
    {

        if($this->request->isPost() && !empty($this->id)){
            $post   = $this->request->post();

            //数据验证
            $result = $this->validate($post, 'app\common\bls\banner\validate\BannerValidate.update');
            if(true !== $result){
                // 验证失败 输出错误信息
                return $this->error($result);
            }

            //更新数据库
            $model = IntegralRuleBls::getOneIntegralRule(['integral_rule_id' => $this->id]);
            if(empty($model)){
                return $this->error('参数错误');
            }

            if(IntegralRuleBls::IntegralRuleUpdate($model, $post)){
                return $this->success('更新成功', url('index'));
            }else{
                return $this->error('更新失败');
            }
        }

        return $this->error('参数错误');
    }

    /**
     * 幻灯片删除
     */
    public function delete()
    {
        if($this->request->isPost() && !empty($this->id)){
            $model = IntegralRuleBls::getOneIntegralRule(['integral_rule_id' => $this->id]);
            if(empty($model)){
                return $this->error('参数错误');
            }

            if($model->status == CommonSwitchConst::ON) {
                return $this->error('请关闭后在删除');
            }

            if($model->delete()){
                return $this->success('删除成功', url('index'));
            }else{
                return $this->error('删除失败');
            }
        }
        return $this->error('参数错误');
    }

}