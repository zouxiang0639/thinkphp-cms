<div class="wrap">
    <ul class="nav nav-tabs">
        <li class="active"><a href="">修改密码</a></li>
    </ul>
    <div class="site-signup">
        <div class="row">
            <form class="form-horizontal" action="{:url('admin/editPassword')}" method="post" >
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th class="col-sm-2">用户名</th>
                        <th>
                            {$info.admin_name}
                        </th>
                    </tr>
                    <tr>
                        <th>原始密码</th>
                        <td>
                            <input type="password" class="form-control text" name="old_password"><span class="form-required">*</span>
                        </td>
                    </tr>
                    <tr>
                        <th>新密码</th>
                        <td>
                            <input type="password" class="form-control text" name="password"><span class="form-required">*</span>
                        </td>
                    </tr>
                    <tr>
                        <th>确认密码</th>
                        <td>
                            <input type="password" class="form-control text" name="repassword"><span class="form-required">*</span>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <div class="form-actions col-sm-12">

                    <button type="button" class="btn btn-primary ajax-post " autocomplete="off">
                        保存
                    </button>
                    <input type="reset" class="btn btn-default active" value="重置">
                    <!--<a class="btn btn-default active" href="JavaScript:history.go(-1)">返回</a>-->
                </div>
            </form>
        </div>
    </div>
</div>
</div>
