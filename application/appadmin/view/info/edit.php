<ul class="nav nav-tabs">
    {$navTabs.nav}
</ul>

<form class="form-horizontal" action="{:url('info/update', ['id'=>input('id')])}" method="post">
    {include file="info/_form" /}
</form>
