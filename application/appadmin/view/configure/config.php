<ul class="nav nav-tabs">
    <li class="active"><a href="">{$info.class_name}</a></li>
</ul>
<form class="form-horizontal" action="{:url('configure/configBuilder')}" method="post">
    <input type="hidden" name="class_name" value="{$info.class_name}">
    <input type="hidden" name="file_name" value="{$info.file_name}">
    <div class="bs-example">
        <table class="table table-bordered">
            <tbody>
                {$html}
            </tbody>
        </table>
    </div>
    <div class="form-actions col-sm-12">
        <button class="btn btn-primary js-ajax-submit" type="submit">提交</button>
        <button type="button" class="btn btn-primary ajax-post " autocomplete="off">
            保存
        </button>
        <a class="btn btn-default active" href="JavaScript:history.go(-1)">返回</a>

    </div>
</form>
