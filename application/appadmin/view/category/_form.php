<?php
use app\common\tool\Tool;
?>
<div class="bs-example">
    <!--右侧-->
    <div class="col-sm-9">
        <table class="table table-bordered">
            <tr>
                <th>标题</th>
                <th>
                    <input class="form-control text" type="text" name="title" value="{$info.title ?? ''}">
                    <span class="form-required">*</span>
                </th>
            </tr>
            <tr>
                <th width="150">前端</th>
                <td>
                    <?=Tool::get('form')->select('terminal', $enum['terminal'], '', ['class' => 'form-control text'])?>
                    <span class="form-required">*</span>
                </td>
            </tr>
            <tr>
                <th width="150">上级分类</th>
                <td>
                    <select class="form-control text" name="parent_id">
                        <option value="0">无</option>
                        {$enum.parent}
                    </select>
                    <span class="form-required">*</span>
                </td>
            </tr>
            <tr>
                <th width="150">模版模型</th>
                <td>
                    <?=Tool::get('form')->select('template_group', $enum['template_group'], object_get($info, 'template_group'), ['class' => 'form-control
                    text', 'id' =>
                        'group'])?>
                </td>
            </tr>
            <tr>
                <th width="150">默认模版</th>
                <td>
                    <?=Tool::get('form')->select('template_default', $enum['template_default'], object_get($info, 'template_default'), ['class' => 'form-control text'])?>
                </td>
            </tr>
            <tr class="template_info">
                <th width="150">详情模板</th>
                <td>
                    <?=Tool::get('form')->select('template_info', $enum['template_info'], '', ['class' => 'form-control text'])?>
                </td>
            </tr>
            <tr class="template_info">
                <th>列表行数</th>
                <th>
                    <input type="text" class="form-control text" name="list_row" value="{$info.list_row ?? '10'}">
                </th>
            </tr>
            <tr>
                <th>简介</th>
                <th>
                    <textarea name="comment" class="form-control "  rows="5">{$info.comment ?? ''}</textarea>
                </th>
            </tr>
        </table>
    </div>
<!--    左侧-->
    <div class="col-sm-3">
        <table class="table table-bordered">
            <tr>
                <th>可见性</th>
            </tr>
            <tr>
                <th>
                    <?=Tool::get('form')->select('display', $enum['display'], '', ['class' => 'form-control text'])?>
                </th>
            </tr>
            <tr>
                <th>排序</th>
            </tr>
            <tr>
                <th> <input type="text" class="form-control text" name="sort" value="{$info.sort ?? '0'}"></th>
            </tr>
            <tr>
                <th>关键字</th>

            </tr>
            <tr>
                <th>
                    <textarea name="keywords" class="form-control text" clos="30" rows="2" >{$info.keywords ?? ''}</textarea>
                </th>
            </tr>
            <tr>
                <th>描述</th>
            </tr>
            <tr>
                <th>
                    <textarea name="description" class="form-control text" clos="30" rows="2">{$info.description ?? ''}</textarea>
                </th>
            </tr>
        </table>
    </div>
    <div class="col-sm-12"> <!--底部-->
        <table class="table table-bordered" >
            <tr>
                <th width="150">文章内容</th>
                <th>
                    <textarea name="content" class="form-control "  rows="5">{$info.content ?? ''}</textarea>
                </th>
            </tr>
        </table>
    </div>
</div>
<div class="form-actions col-sm-12">
    <button class="btn btn-primary js-ajax-submit" type="submit">提交</button>
    <button type="button" class="btn btn-primary ajax-post " autocomplete="off">
        保存
    </button>
    <a class="btn btn-default active" href="JavaScript:history.go(-1)">返回</a>
</div>

<script>
    $(function(){
        $("#group").change(function() {
            var tr,num;
            num = $(this).val();
            tr  = $('.template_info');
            if(num == 2){
                tr.show();
            }else {
                tr.hide();
            }
        });
        $("#group").trigger("change");
    })
</script>

