<ul class="nav nav-tabs">
    {$navTabs.nav}
</ul>

<form class="form-horizontal" action="{:url('file/uploadPicture', ['type'=>'file'])}" method="post" enctype="multipart/form-data">
   <input type="file" name="file">
    <input type="submit">
</form>
<form class="form-horizontal" action="{:url('banner/add')}" method="post" >
    {include file="banner/_form" /}
</form>
