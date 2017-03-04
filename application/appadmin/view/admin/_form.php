
<link rel="stylesheet" href="__PublicDefault__/multiselect/css/multi-select.css">
<script src="__PublicDefault__/multiselect/js/jquery.multi-select.js"></script>
<script src="__PublicDefault__/multiselect/js/joinable.js"></script>

<table class="table table-bordered">
    <tbody>
    <tr>
        <th class="col-sm-2">用户名</th>
        <th>
            {if condition="!isset($info['admin_name'])"}
                <input type="text" class="form-control text" value="" name="admin_name" >
                <span class="form-required">*</span>
            {else /}
                {$info.admin_name}
            {/if}
        </th>
    </tr>

    {if condition="empty($info['admin_password'])"}
        <tr>
            <th>密码</th>
            <td>
                <input type="password" class="form-control text" name="admin_password"><span class="form-required">*</span>
            </td>
        </tr>
    {/if}
    <tr>
        <th>管理员姓名</th>
        <td>
            <input type="text" value="{$info.name ?? ''}" class="form-control text" name="name">
        </td>
    </tr>
    <tr>
        <th>管理员编号</th>
        <td>
            <input type="text" value="{$info.code ?? ''}" class="form-control text" name="code">
        </td>
    </tr>
    <tr>
        <th>管理员性别</th>
        <td>
            <?=\app\common\tool\Tool::get('form')->select(
                'sex',
                ['请选择', '男' => '男', '女' => '女'],
                object_get($info,'sex'),
                ['class'=>'form-control text']
            )?>
        </td>
    </tr>
    <tr>
        <th>管理员邮箱</th>
        <td>
            <input type="text" value="{$info.admin_email ?? ''}" class="form-control text" name="admin_email">
        </td>
    </tr>
    <tr>
        <th>管理员介绍</th>
        <td>
            <textarea name="comment" class="form-control" rows="3" >{$info.comment ?? ''}</textarea>
        </td>
    </tr>
    <tr>
        <th>角色</th>
        <td>
            <script type="text/javascript">
                jQuery(document).ready(function($)
                {
                    $("#multi-select").multiSelect({
                        afterInit: function()
                        {
                            // Add alternative scrollbar to list
                            this.$selectableContainer.add(this.$selectionContainer).find('.ms-list').perfectScrollbar();
                        },
                        afterSelect: function()
                        {
                            // Update scrollbar size
                            this.$selectableContainer.add(this.$selectionContainer).find('.ms-list').perfectScrollbar('update');
                        }
                    });
                });
            </script>
            <select class="form-control" multiple="multiple" id="multi-select" name="role[]">
                {$info.role_html ?? ''}
            </select>
        </td>
    </tr>
    </tbody>
</table>

<div class="form-actions col-sm-12">
    <button type="submit" class="btn btn-primary active" >保存</button>
    <button type="button" class="btn btn-primary ajax-post " autocomplete="off">
        保存
    </button>
    <a class="btn btn-default active" href="JavaScript:history.go(-1)">返回</a>
</div>



