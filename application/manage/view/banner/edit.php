<ul class="nav nav-tabs">
    {$navTabs.nav}
</ul>

<form class="form-horizontal" action="{:url('banner/update',['id'=>$info['banner_id']])}" method="post">
    {include file="banner/_form" /}
</form>