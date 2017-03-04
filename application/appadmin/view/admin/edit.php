
<div class="wrap">
    <ul class="nav nav-tabs">
        {$navTabs.nav}
    </ul>
    <div class="site-signup">
        <div class="row">
            <form class="form-horizontal" action="{:url('admin/editPost',['id'=>$info['admin_id']])}" method="post" >
                {include file="admin/_form" /}
            </form>
        </div>
    </div>
</div>

