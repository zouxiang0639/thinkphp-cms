<?php
namespace app\manage\controller;

use app\common\model\ExtendedModel;
use think\Db;

class Extended extends BasicController
{

    private $id     = 0;
    private $url    = 'extended/index';

    public function __construct()
    {
        $this->groups   = lang('extended groups');

        parent::__construct();
        $this->id       = !empty($this->request->param('id')) ? intval($this->request->param('id')) : $this->id;
        $nav = [
            '数据扩展列表' => ['url' => $this->url],
            '数据扩展增加' => ['url' => 'extended/add'],
            '数据类型修改' => ['url' => ['extended/dataTypeEdit', ['id' => $this->id]], 'style' => "display: none;"],
            '字段类型修改' => ['url' => ['extended/fieldsTypeEdit', ['id' => $this->id]], 'style' => "display: none;"],
        ];
        $this->assign('navTabs',  parent::navTabs($nav));
    }

    public function index()
    {
        $where  = ['parent_id' => 0];
        $groups   = intval($this->request->get('groups'));
        if(!empty($groups)){
            $where['group']  = $groups;
        }

        $list = ExtendedModel::where($where)->paginate();
        return $this->fetch('',[
            'list'      => $list,
            'page'      => $list->render(),
            'groups'    => $this->groups
        ]);
    }

    /**
     * 创建扩展
     */
    public function add()
    {
        //createExtended_post 数据处理
        if($this->request->isPost()){
            $post   = $this->request->post();

            //数据验证
            $validate =[
                ['title|标题', 'require'],
                ['name|数据库名', 'requireIf:group,2|unique:extended,name|alphaDash']
            ];
            $result = $this->validate($post, $validate);
            if($result !== true){
                return $this->error($result);
            }

            $post['name'] = empty($post['name']) ? '' : 'ex_'.$post['name'];

            //写入数据 如果是数据类型就创建数据库
            if(ExtendedModel::create($post)->createTabel()){
                return $this->success(lang('Add success'), url($this->url));
            }else{
                return $this->error(lang('Add failed'));
            }

        }

        return $this->fetch('',[
            'info'  => [
                'groups'    => $this->groups,
            ]
        ]);
    }

    /**
     * 更新扩展
     */
    public function update()
    {
        if($this->request->isPost() && !empty($this->id)){
            $post       = $this->request->post();

            //数据验证
            $validate   = [
                ['title|标题', 'require']
            ];
            $result = $this->validate($post, $validate);
            if($result !== true){
                return $this->error($result);
            }

            //更新数据库
            $update = ExtendedModel::get($this->id);
            if(empty($update)){
                return abort(404, lang('404 not found'));
            }
            if($update->allowField(['title','comment'])->save($post)){
                return $this->success(lang('Update success'), url($this->url));
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
            $delete  = ExtendedModel::get($this->id);
            if(empty($delete)){
                return abort(404, lang('404 not found'));
            }
            if(ExtendedModel::get($this->id)->deleteChild()->deleteTabel()->delete()){
                return $this->success(lang('delete success'), url($this->url));
            }else{
                return $this->error(lang('delete failed'));
            }
        }
        return abort(404, lang('404 not found'));

    }

    /**
     * 数据库类型修改
     */
    public function dataTypeEdit()
    {

        if($this->request->isPost()){
            $post               = $this->request->post();
            $post['group']      = 2;
            $post['parent_id']  = $this->id;
            $post['id']         = intval($post['id']);


            if(empty($post['id'])){
                /**
                 * 创建数据库字段
                 */

                //数据验证
                $validate =[
                    ['title|标题', 'require'],
                    ['name|字段名称', "require|alphaDash|unique:extended,parent_id={$this->id}&name={$post['name']}"],
                    ['mysql_fields_length|字段值长度', 'regex:^\d+(\,\d+)?$'.$this->checkFieldsType($post['mysql_fields_type'])]
                ];
                $result = $this->validate($post, $validate);
                if($result !== true){
                    return $this->error($result);
                }

                //创建字段
                unset($post['id']);
                if(ExtendedModel::create($post)->createFields()){
                    return $this->success(lang('Add success'));
                }else{
                    return $this->error(lang('Add failed'));
                }
            }else{
                /**
                 * 修改字段
                 */

                //数据验证
                $validate =[
                    ['title|标题', 'require'],
                    ['id|字段ID', 'require']
                ];
                $result = $this->validate($post, $validate);
                if($result !== true){
                    return $this->error($result);
                }

                $update = ExtendedModel::get($post['id']);

                //更新扩展数据库
                if($update->allowField(['title','comment','input_type','input_value','sort'])->save($post)){
                    return $this->success(lang('Update success'));
                }else{
                    return $this->error(lang('Update failed'));
                }
            }
        }

        $info = ExtendedModel::get($this->id);

        if(empty($info)){
            return abort(404, lang('404 not found'));
        }
        $list = ExtendedModel::where(['parent_id' => $this->id])
            ->order(["sort" => "desc", 'extended_id' => 'asc'])
            ->select();
        $info['group'] = $this->groups[$info['group']];

        return $this->fetch('',[
            'info'   => $info,
            'list'   => $list
        ]);
    }

    /**
     * 数据库类型修改
     */
    public function fieldsTypeEdit()
    {

        if($this->request->isPost()){
            $post               = $this->request->post();
            $post['group']      = 1;
            $post['parent_id']  = $this->id;
            $post['id']         = intval($post['id']);

            if(empty($post['id'])){
                /**
                 * 创建字段
                 */

                //数据验证
                $validate =[
                    ['title|标题', 'require'],
                    ['name|字段名称', "require|alphaDash|unique:extended,parent_id={$this->id}&name={$post['name']}"]
                ];
                $result = $this->validate($post, $validate);
                if($result !== true){
                    return $this->error($result);
                }

                unset($post['id']);
                if(ExtendedModel::create($post)->createFields()){
                    return $this->success(lang('Add success'));
                }else{
                    return $this->error(lang('Add failed'));
                }
            }else{
                /**
                 * 修改字段
                 */

                //数据验证
                $validate =[
                    ['title|标题', 'require']
                ];
                $result = $this->validate($post, $validate);
                if($result !== true){
                    return $this->error($result);
                }


                $update = ExtendedModel::get($post['id']);
                //更新扩展数据库
                if($update->save($post)){
                    return $this->success(lang('Update success'));
                }else{
                    return $this->error(lang('Update failed'));
                }
            }
        }

        $info = ExtendedModel::get($this->id);

        if(empty($info)){
            return abort(404, lang('404 not found'));
        }
        $list = ExtendedModel::where(['parent_id' => $this->id])
            ->order(["sort" => "desc", 'extended_id' => 'asc'])
            ->select();
        $info['group'] = $this->groups[$info['group']];

        return $this->fetch('',[
            'info'   => $info,
            'list'   => $list
        ]);
    }

    /**
     * 删除数据库字段
     */
    public function mysqlFieldsDelete()
    {
        if($this->request->isPost() && !empty($this->id)){
            $delete  = ExtendedModel::get($this->id);
            if(empty($delete)){
                return abort(404, lang('404 not found'));
            }

            if($delete->fieldsDelete()->delete()){
                return $this->success(lang('delete success'));
            }else{
                return $this->error(lang('delete failed'));
            }
        }
        return abort(404, lang('404 not found'));
    }


    /**
     * 数据库不同的类型做不同的长度判断
     *
     * @param  string    $type
     * @return string
     */
    private function checkFieldsType($type)
    {
        $validate   = '';
        $typeArray   = lang('mysql fields type');

        //根据不同的类型做长度判断
        switch($typeArray[$type]){
            case 'tinyint':         //存储大小为 1 字节。
                $validate   = '|require|between:1,4';
                break;
            case 'smallint':        //存储大小为 2 个字节。
                $validate   = '|require|between:1,6';
                break;
            case 'int':             //存储大小为 4 个字节。
                $validate   = '|require|between:1,11';
                break;
            case 'bigint':          //存储大小为 8 个字节。
                $validate   = '|require|between:1,20';
                break;
            case 'varchar':         //存储大小为 1 个字节, 大于 255 为2个字节
                $validate   = '|require|between:1,65535';
                break;
            case 'char':            //存储大小为 1 个字节, 建议存储固定长度的字符串
                $validate   = '|require|between:1,255';
                break;
            case 'float':
                $validate   = '|require';
                break;
            case 'decimal':
                $validate   = '|require';
                break;
            default:
        }

        if($validate){
            return $validate;
        }

        return '';
    }

}