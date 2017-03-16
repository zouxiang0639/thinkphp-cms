<?php
use \app\common\tool\Tool;
?>
<div class="bs-example">
    <table class="table table-bordered">
        <tbody>
        <tr>
            <th>名称</th>
            <th>
                <input class="form-control text" type="text" name="title" value="{$info.title ?? ''}">
                <span class="form-required">*</span>
            </th>
        </tr>
        <tr>
            <th width="150">所属分组</th>
            <td>
                <?=Tool::get('form')->select('type', lang('configure groups'), object_get($info,'groups'), ['class'=>"form-control text"] )?>
            </td>
        </tr>
        <tr>
            <th>配置名称</th>
            <th>
                <input class="form-control text" type="text" name="configure_name" value="{$info.configure_name ?? ''}">
                <span class="form-required">*</span><span class="span-text">只能是英文命名</span>
            </th>
        </tr>

        <tr>
            <th>渲染类型</th>
            <td>
                <?=Tool::get('form')->select('form_type', lang('form type'), object_get($info,'form_type'), ['class'=>"form-control
                text"] )?>
            </td>
        </tr>
        <tr>
            <th>备注</th>
            <td>
                <input class="form-control text" type="text" name="comment" value="{$info.comment ?? ''}">
            </td>
        </tr>
        <tr>
            <th>内容</th>
            <td>
                <textarea name="configure_value" class="form-control" rows="3" >{$info.configure_value ?? ''}</textarea>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<div class="form-actions col-sm-12">
  <!--<button class="btn btn-primary js-ajax-submit" type="submit">提交</button>-->
    <button type="button" class="btn btn-primary ajax-post " autocomplete="off">
        保存
    </button>
    <a class="btn btn-default active" href="JavaScript:history.go(-1)">返回</a>

</div>


