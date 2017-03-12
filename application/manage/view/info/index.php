<ul class="nav nav-tabs">
    {$navTabs.nav}
</ul>
<form  method="post">
    <table class="table table-hover table-bordered table-list" id="menus-table">
        <thead>
        <tr>
            <th width="50">排序</th>
            <th width="50">ID</th>
            <th width="120">缩略图</th>
            <th>标题</th>
            <th width="180">分类</th>
            <th width="180">操作</th>
        </tr>
        </thead>
        <tbody>
        {foreach name='$list' item='v' }
            <tr>
                <th width="50"><input name='sort[{$v.info_id}]' type='text' size='3' value='{$v.sort}' data='{$v.info_id}' class='listOrder'></th>
                <th width="50">{$v.info_id}</th>
                <th><img height="30" src="{$v.picture}"></th>
                <th>{$v.title}</th>
                <th>{$v.category_title}</th>
                <th>
                    <a href="{:url('info/edit', ['id' => $v['info_id'], 'cid' => $cid])}">编辑</a> |
                    <a class="a-post" post-msg="你确定删除吗?" post-url="{:url('info/delete', ['id'=>$v['info_id']])}">删除</a>
                </th>
            </tr>
        {/foreach}
        </tbody>
    </table>
</form>

<input type="hidden" value="{:url('info/sort')}" class="listOrderUrl">