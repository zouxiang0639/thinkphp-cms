<?php
use app\common\tool\Tool;
?>
<div class="bs-example">
    <table class="table table-bordered">
        <tr>
            <th>标题</th>
            <th>
                <input class="form-control text" type="text" name="title" value="{$info.title ?? ''}">
                <span class="form-required">*</span>
            </th>
        </tr>
        <tr>
            <th width="150">图片</th>
            <td>
               <?=Tool::get('form')->oneImage('picture', object_get($info,'picture'))?>
            </td>
        </tr>
        <tr>
            <th>链接</th>
            <th>
                <input class="form-control text" type="text" name="links" value="{$info.links ?? ''}">
            </th>
        </tr>
        <tr>
            <th>描述</th>
            <th>
                <textarea class="form-control text"  name="comment" >{$info.comment ?? ''}</textarea>
            </th>
        </tr>
        <tr>
            <th>详细</th>
            <th>
                <?=Tool::get('form')->editor('content', object_get($info,'content'))?>
            </th>
        </tr>
    </table>
</div>

<div class="form-actions col-sm-12">
    <button type="button" class="btn btn-primary ajax-post " autocomplete="off">
        保存
    </button>
    <a class="btn btn-default active" href="JavaScript:history.go(-1)">返回</a>
</div>


