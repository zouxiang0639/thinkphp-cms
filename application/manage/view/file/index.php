

<script>
    $(function(){
        $("#navcid_select").change(function() {
            $("#mainform").submit();
        });
    })
</script>

<ul class="nav nav-tabs">
    {$navTabs.nav}
</ul>
<form class="well form-search" id="mainform" action="{:url('file/index')}" method="get">
    <div class="btn-group width2">
        <?=\app\common\tool\Tool::get('form')->select(
            'groups',
             array_merge([0 => '全部模型'], $groups),
            input('groups'),
            ['class' => 'form-control', 'id' => 'navcid_select' ]
        )?>
    </div>
    <div class="btn-group width2">
        <input class="form-control width2" placeholder="文件路由或者名称" type="text" name="name" value="{:input('name')}">
    </div>
    <div class="btn-group width2">
        <input class="btn btn-primary"  type="submit" value="查询">
    </div>
</form>

<div class="bs-example" data-example-id="simple-thumbnails">

<div class="row">
    {foreach name='$list' id='v'}
    <?php $name = 'asfas'.$key?>
    <div class="col-sm-3 col-md-2">
        <div class="thumbnail">
            <img id='{$name}-preview' title="{$v.path} {$v.name}" alt="{$v.name}"  style="height: 120px; width: 100%;cursor: hand; display: block;" src="{$v.path}">
            <div class="caption">
                <a  class="btn btn-primary" href="javascript:upload_one_file('{$groups[$v['groups']]}替换' ,'#{$name}' ,'{$v.groups}' ,1 ,'' ,{$v.file_id});">
                    {$groups[$v['groups']]}替换
                </a>
            </div>
        </div>
    </div>
    {/foreach}
</div>

</div>
<div class="text-center">
    {$page}
</div>