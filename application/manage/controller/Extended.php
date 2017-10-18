<?php
namespace app\manage\controller;

use app\common\bls\extended\ExtendedBls;
use app\common\bls\extended\traits\ExtendedTrait;

class Extended extends BasicController
{

    use ExtendedTrait;

    private $id     = 0;

    public function __construct()
    {
        parent::__construct();
        $this->id       = $this->request->get('id');

        $nav = [
            '数据扩展列表' => ['url' => 'index'],
            '数据扩展增加' => ['url' => 'add'],
            '数据类型修改' => ['url' => ['dataTypeEdit', ['id' => $this->id]], 'style' => "display: none;"],
            '字段类型修改' => ['url' => ['fieldsTypeEdit', ['id' => $this->id]], 'style' => "display: none;"],
        ];
        $this->assign('navTabs',  parent::navTabs($nav));
    }

    public function index()
    {
        $where  = ['parent_id' => 0];
        $groups   = intval($this->request->get('type'));
        if(!empty($groups)){
            $where['type']  = $groups;
        }

        $model = ExtendedBls::getExtendedList($where);
        $this->formatExtended($model->getCollection());
        return $this->fetch('',[
            'list'      => $model,
        ]);
    }

    /**
     * 创建扩展
     */
    public function add()
    {
        //createExtended_post 数据处理
        if($this->request->isPost()){

            $post = $this->request->post();
            $result = $this->validate($post, 'app\common\bls\extended\validate\ExtendedValidate.create');
            if(true !== $result){
                // 验证失败 输出错误信息
                return $this->error($result);
            }

            $post['name'] = empty($post['name']) ? '' : 'ex_'.$post['name'];

            //写入数据 如果是数据类型就创建数据库
            if(ExtendedBls::createExtended($post)){
                return $this->success(lang('Add success'), url('index'));
            }else{
                return $this->error(lang('Add failed'));
            }

        }

        return $this->fetch('');
    }

    /**
     * 更新扩展
     */
    public function update()
    {
        if($this->request->isPost() && !empty($this->id)){
            $post       = $this->request->post();

            $result = $this->validate($post, 'app\common\bls\extended\validate\ExtendedValidate.update');
            if(true !== $result){
                // 验证失败 输出错误信息
                return $this->error($result);
            }

            //更新数据库
            $update = ExtendedBls::getOneExtended(['extended_id'=>$this->id]);
            if(empty($update)){
                return $this->error('参数错误');
            }
            if($update->save($post)){
                return $this->success(lang('Update success'), url('index'));
            }else{
                return $this->error(lang('Update failed'));
            }
        }

        return abort(404, lang('404 not found'));
    }

    /**
     * 删除扩展
     */
    public function delete()
    {
        if($this->request->isPost() && !empty($this->id)){
            $model  = ExtendedBls::getOneExtended(['extended_id'=>$this->id]);
            if(empty($model)){
                return $this->error('参数错误');
            }

            if($model->pageFieldsExtended->count() || $model->pageDataExtended->count()){
                return $this->error('数据扩展已被绑定');
            }

            if(ExtendedBls::deleteExtended($model)){
                return $this->success(lang('delete success'), url('index'));
            }else{
                return $this->error(lang('delete failed'));
            }
        }
        return $this->error('请求错误');

    }

    /**
     * 数据库类型修改
     */
    public function dataTypeEdit()
    {

        if($this->request->isPost()){
            $post               = $this->request->post();
            $post['parent_id']  = $this->id;
            $post['id']         = intval($post['id']);

            if(empty($post['id'])){ //创建数据库字段

                //数据验证
                $result = $this->validate($post, 'app\common\bls\extended\validate\ExtendedValidate.dataTypeCreate');
                if(true !== $result){
                    // 验证失败 输出错误信息
                    return $this->error($result);
                }

                if(ExtendedBls::createDataType($post)){
                    return $this->success(lang('Add success'));
                }else{
                    return $this->error(lang('Add failed'));
                }
            }else{//修改字段

                $result = $this->validate($post, 'app\common\bls\extended\validate\ExtendedValidate.dataTypeUpdate');
                if(true !== $result){
                    // 验证失败 输出错误信息
                    return $this->error($result);
                }

                $model = ExtendedBls::getOneExtended(['extended_id'=>$post['id']]);
                if(empty($model)){
                    return $this->error('参数错误');
                }

                //更新扩展数据库
                if($model->allowField(['title','comment','input_type','input_value','sort'])->save($post)){
                    return $this->success(lang('Update success'));
                }else{
                    return $this->error(lang('Update failed'));
                }
            }
        }

        $model = ExtendedBls::getOneExtended(['extended_id'=>$this->id]);
        $list = $model->extendedChild;
        $this->formatExtendedField($list);
        if(empty($model)){
            return $this->error('参数错误');
        }

        return $this->fetch('',[
            'info'   => $model,
            'showCreateTabel' => ExtendedBls::showCreateTabel($model),
            'list' => $list
        ]);
    }

    /**
     * 删除数据库字段
     */
    public function mysqlFieldsDelete()
    {
        if($this->request->isPost() && !empty($this->id)){

            $model  = ExtendedBls::getOneExtended(['extended_id'=>$this->id]);
            if(empty($model)){
                return abort(404, lang('404 not found'));
            }

            if(ExtendedBls::mysqlFieldsDelete($model)){
                return $this->success(lang('delete success'));
            }else{
                return $this->error(lang('delete failed'));
            }
        }
        return $this->error('请求错误');
    }

    /**
     * 字段类型修改
     */
    public function fieldsTypeEdit()
    {

        if($this->request->isPost()){
            $post               = $this->request->post();
            $post['parent_id']  = $this->id;
            $post['id']         = intval($post['id']);



            if(empty($post['id'])){ // 创建字段

                $result = $this->validate($post, 'app\common\bls\extended\validate\ExtendedValidate.fieldsTypeCreate');
                if(true !== $result){
                    // 验证失败 输出错误信息
                    return $this->error($result);
                }
                unset($post['id']);
                if(ExtendedBls::createExtendedFields($post)){
                    return $this->success(lang('Add success'));
                }else{
                    return $this->error(lang('Add failed'));
                }
            }else{ //修改字段

                //数据验证
                $result = $this->validate($post, 'app\common\bls\extended\validate\ExtendedValidate.fieldsTypeUpdate');
                if(true !== $result){
                    // 验证失败 输出错误信息
                    return $this->error($result);
                }


                $update = ExtendedBls::getOneExtended(['extended_id'=>$post['id']]);
                $update->title = $post['title'];
                $update->sort = $post['sort'];
                $update->comment = $post['comment'];
                $update->input_type = $post['input_type'];
                $update->input_value = $post['input_value'];
                $update->binding_label = $post['binding_label'];

                //更新扩展数据库
                if($update->save()){
                    return $this->success(lang('Update success'));
                }else{
                    return $this->error(lang('Update failed'));
                }
            }
        }

        $model = ExtendedBls::getOneExtended(['extended_id'=>$this->id]);
        $list = $model->extendedChild;
        $this->formatExtendedField($list);

        if(empty($model)){
            return $this->error('参数错误');
        }

        return $this->fetch('',[
            'info'  => $model,
            'list'  => $list
        ]);
    }

    /**
     * 删除字段
     */
    public function fieldsDelete()
    {
        if($this->request->isPost() && !empty($this->id)){
            $model = ExtendedBls::getOneExtended(['extended_id' => $this->id]);

            if(empty($model)) {
                return $this->error('参数错误');
            }

            if($model->delete()) {
                return $this->success(lang('delete success'));
            } else {
                return $this->error(lang('delete failed'));
            }
        }

        return $this->error('请求错误');
    }


}