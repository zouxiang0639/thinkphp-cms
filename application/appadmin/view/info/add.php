<ul class="nav nav-tabs">
    {$navTabs.nav}
</ul>

<form class="form-horizontal" action="{:url('info/create', ['cid' => input('cid')])}" method="post">
    {include file="info/_form" /}
</form>
