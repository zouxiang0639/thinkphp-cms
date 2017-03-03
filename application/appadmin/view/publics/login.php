<style>
    body {
        background-color: #c3cdda;
        background: url(__PublicAdmin__/images/login_1.jpg) no-repeat center center fixed;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
    }
    .m-login { width:340px;background-color:#fafafa; position:absolute; left:50%; top:50%; margin-left:-170px; margin-top:-120px; box-shadow: 0 0 10px #333;border-radius:5px; padding-bottom:15px; color:#666;}
    .m-login .login-head { background-color:#f0f0f0; height:50px;line-height:50px; padding-left:10px; font-size:16px; border-bottom:1px solid #ebebeb; border-radius:5px 5px 0px 0px; text-align:center; color:#909090; text-shadow:#fff 1px 1px 0px;}
    .form-input{width: 300px;margin: auto;padding-top: 10px; }
</style>

<div class="m-login">
    <div class="login-head"> 后台管理</div>
    <form action="{:url('publics/login')}" method="post">
        <div  class="form-input">
            <div class="form-group ">
                <input type="text" class="form-control" name="admin_name" placeholder="用户名" value="">
            </div>
            <div class="form-group ">
                <input type="password" class="form-control" name="admin_password" placeholder="密码">
            </div>
            <div class="form-group ">
                <input  type="text" class="form-control" name="verify" placeholder="验证码" >
            </div>
            <div class="form-group ">
                <img  width="300" height="39" src="{:captcha_src()}" alt="captcha" onclick="this.src='{:captcha_src()}?time='+Math.random();" />
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">登录</button>
            </div>
        </div>
    </form>

</div>
