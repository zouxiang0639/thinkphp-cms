
<ul class="nav nav-tabs">
    {$navTabs.nav}
</ul>
<table class="table table-hover table-bordered table-list" id="menus-table">
    <thead>
    <tr>
        <th width="80">排序</th>
        <th width="50">ID</th>
        <th>菜单名称</th>
        <th width="100">操作</th>
    </tr>
    </thead>
    <tbody>
        {$html}
    </tbody>
</table>
<input type="hidden" value="{:url('category/sort')}" class="listOrderUrl">
