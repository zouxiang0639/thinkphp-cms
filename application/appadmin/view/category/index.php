<link href="__PublicDefault__/font-awesome/4.4.0/css/font-awesome.min.css"  rel="stylesheet" type="text/css">
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
<form class="well form-search" id="mainform" action="{:url('category/index')}" method="get">
    <div class="btn-group width2">
        <?=\app\common\tool\Tool::get('form')->select(
            'terminal',
            $terminal,
            input('terminal'),
            ['class' => 'form-control', 'id' => 'navcid_select' ]
        )?>
    </div>
</form>
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
