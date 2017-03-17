<ul class="nav nav-tabs">
    {$navTabs.nav}
</ul>

<form class="form-horizontal" action="{:url('fragment/update',['id'=>$info['fragment_id']])}" method="post">
    {include file="fragment/_form" /}
</form>