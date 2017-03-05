<ul class="nav nav-tabs">
    {$navTabs.nav}
</ul>


<form class="form-horizontal" action="{:url('configure/update',['id'=>input('id')])}" method="post">
    {include file="configure/_form" /}
</form>
