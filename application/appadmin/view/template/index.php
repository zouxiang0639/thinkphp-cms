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
<form class="well form-search" id="mainform" action="{:url('template/index')}" method="get">
    <div class="btn-group width2">
        <?=\app\common\tool\Tool::get('form')->select(
            'groups',
            array_merge([0 => '全部模型'], $groups),
            input('groups'),
            ['class' => 'form-control', 'id' => 'navcid_select' ]
        )?>
    </div>
</form>
<form  method="post">

    <table class="table table-hover table-bordered table-list" id="menus-table">
        <thead>
        <tr>
            <th width="50">ID</th>
            <th>分类名称</th>
            <th>模型URL</th>
            <th>模版文件</th>
            <th>模版文件</th>
            <th width="180">操作</th>
        </tr>
        </thead>
        <tbody>
        {foreach name='$list' item='v' }
            <tr>
                <th width="50">{$v.template_id}</th>
                <th>{$v.title}</th>
                <th>{$v.group}</th>
                <th>{$v.type}</th>
                <th>{$v.template_file}</th>
                <th>
                    <a href="{:url('template/edit', ['id'=>$v['template_id']])}">编辑</a> |
                    <a class="a-post" post-msg="你确定删除吗?" post-url="{:url('template/delete', ['id'=>$v['template_id']])}">删除</a>
                </th>
            </tr>
        {/foreach}
        </tbody>
    </table>

</form>
