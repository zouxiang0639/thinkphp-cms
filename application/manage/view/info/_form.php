<?php
use app\common\tool\Tool;
?>

<div class="bs-example">
    <!--右侧-->
    <div class="col-sm-8">
        <table class="table table-bordered">
            <tr>
                <th>标题</th>
                <th>
                    <input class="form-control text" type="text" name="title" value="{$info.title ?? ''}">
                    <span class="form-required">*</span>
                </th>
            </tr>
            <tr>
                <th width="150">分类</th>
                <td>
                    <input type="hidden" name="category_id" value="{$info.category_id ?? ''}" />
                    <select class="form-control text" name="category_id"  {$cid ? 'disabled="disabled"' : ''}>
                        {$enum.category}
                    </select>
                </td>
            </tr>
            <tr>
                <th width="150">可见性</th>
                <td>
                    <?=Tool::get('form')->select('display', $enum['display'], object_get($info, 'display'), ['class' => 'form-control text'])?>
                </td>
            </tr>
            <tr>
                <th>外链</th>
                <th>
                    <input type="text" class="form-control text" name="links" value="{$info.links ?? ''}">
                </th>
            </tr>
            <tr>
                <th>访问量</th>
                <th>
                    <input type="text" class="form-control text" name="visiting" value="{$info.visiting ?? '0'}">
                </th>
            </tr>
            <tr>
                <th>推荐</th>
                <th>
                    <?=Tool::get('form')->checkboxs('recommendation', $enum['recommendation'], object_get($info, 'recommendation'))?>
                </th>
            </tr>
            <tr>
                <th>简介</th>
                <th>
                    <textarea name="comment" class="form-control "  rows="5">{$info.comment ?? ''}</textarea>
                </th>
            </tr>
            <tr>
                <th>相册图集</th>
                <th>
                    <?=Tool::get('form')->multiImage('photos', object_get($info, 'photos'))?>
                </th>
            </tr>
        </table>
    </div>

    <!--左侧-->
    <div class="col-sm-3">
        <table class="table table-bordered">
            <tr>
                <th>缩略图</th>
            </tr>
            <tr>
                <th>
                    <?=Tool::get('form')->oneImage('picture', object_get($info, 'picture'))?>
                </th>
            </tr>
            <tr>
                <th>创建时间</th>
            </tr>
            <tr>
                <th>
                    <?=Tool::get('form')->date('create_time', object_get($info, 'create_time'))?>
                </th>
            </tr>
            <tr>
                <th>网页描述</th>
            </tr>
            <tr>
                <th>
                    <textarea name="keywords" class="form-control text" clos="30" rows="2" >{$info.keywords ?? ''}</textarea>
                </th>
            </tr>
            <tr>
                <th>网页关键字</th>
            </tr>
            <tr>
                <th>
                    <textarea name="description" class="form-control text" clos="30" rows="2">{$info.description ?? ''}</textarea>
                </th>
            </tr>
        </table>
    </div>
    <!--底部-->
    <div class="col-sm-12">
        <table class="table table-bordered" >
            <tr>
                <th width="150">文章内容</th>
                <th>
                    <?=Tool::get('form')->editor('content', object_get($info, 'content'))?>
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
