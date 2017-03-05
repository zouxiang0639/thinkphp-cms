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
<form class="well form-search" id="mainform" action="{:Url('configure/index')}" method="get">
    <div class="btn-group width2">
        <?=\app\common\tool\Tool::get('form')->select(
            'type',
            array_merge([0 => '全部配置'], $class),
            input('type'),
            ['class' => 'form-control', 'id' => 'navcid_select' ]
        )?>
    </div>
</form>
<table class="table table-hover table-bordered" id="menus-table">
    <thead>
    <tr>
        <th width="50">ID</th>
        <th>标题</th>
        <th>配置名</th>
        <th>分组</th>
        <th>备注</th>
        <th width="100">操作</th>
    </tr>
    </thead>
    <tbody>
    {foreach name='$list' item='v'}
        <tr>
            <th width="50">{$v.configure_id}</th>
            <th>{$v.title}</th>
            <th>{$v.configure_name}</th>
            <th>{$v.type}</th>
            <th>{$v.comment}</th>
            <th>
                <a href="{:url('configure/edit',['id'=>$v['configure_id']])}">编辑</a> |
                <a class="a-post" post-msg="你确定删除吗?" post-url="{:url('configure/delete',['id' => $v['configure_id']])}">删除</a>
            </th>
        </tr>
    {/foreach}
    </tbody>
</table>
<div class="text-center">
    {$page}
</div>


