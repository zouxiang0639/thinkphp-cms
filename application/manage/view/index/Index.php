
<style>
    .tab-pane {height: 0px}
</style>
<div id="loading"><i class="loadingicon"></i><span>正在加载...</span></div>
<div id="right_tools_wrapper">
    <span id="right_tools_clearcache" title="清除缓存" onclick="javascript:openapp('<?php echo url('publics/clear')?>','index_clearcache','清除缓存');"><i class="fa fa-trash-o right_tool_icon"></i></span>
    <span id="refresh_wrapper" title="REFRESH_CURRENT_PAGE" ><i class="fa fa-refresh right_tool_icon"></i></span> </div>
<!--head-->

<div class="navbar">
    <div class="navbar-inner">

        <div class="container-fluid" >

            <a href="{:url('index/index')}" class="brand">
                <small>后台管理中心 </small>
            </a>
            <div class="pull-left nav_shortcuts" >
                <!-- <a class="btn btn-small btn-warning" href="/" title="网站首页" target="_blank">
                    <i class="fa fa-home"></i>
                </a>
                <a class="btn btn-small btn-success" href="javascript:openapp('<?php /*echo Url('nav/index')*/?>','index_termlist','分类管理');" title="分类管理">
                    <i class="fa fa-th"></i>
                </a>
                <a class="btn btn-small btn-info" href="javascript:openapp('<?php /*echo Url('info/index')*/?>','index_postlist','文章管理');" title="文章管理">
                    <i class="fa fa-pencil"></i>
                </a
                <a class="btn btn-small btn-danger" href="javascript:openapp('<?php /*echo Url('publics/clear')*/?>','index_clearcache','清除缓存');" title="清除缓存">
                    <i class="fa fa-trash-o"></i>
                </a>-->
            </div>
            <ul id="myTabs" class="nav simplewind-nav pull-lift" style="margin-left: 48px;">
                {$menu.menuName}
            </ul>

            <ul class="nav simplewind-nav pull-right">
                <li class="light-blue">
                    <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                        <img class="nav-user-photo" width="30" height="30" src="__PublicAdmin__/images/logo-18.png" alt="admin">
                        <span class="user-info"> 欢迎, admin </span>
                        <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="user-menu pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-closer">
                        <li>
                            <a href="javascript:openapp('/moban2/index.php?g=Admin&m=setting&a=site','index_site','网站信息');">
                                <i class="fa fa-cog"></i> 网站信息
                            </a>
                        </li>
                        <li>
                            <a href="javascript:openapp('/moban2/index.php?g=Admin&m=user&a=userinfo','index_userinfo','修改信息');">
                                <i class="fa fa-user"></i> 修改信息
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo Url('publics/logout')?>" data-method="post">
                                <i class="fa fa-sign-out"></i> 退出</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
<!--head-->

<!--cantent-->
<div class="main-container container-fluid">
    <div class="sidebar" id="sidebar">
        <div class="sidebar-shortcuts" id="sidebar-shortcuts">
            <a class="btn btn-small btn-warning" href="{:url('index/index')}" title="网站首页" >
                <i class="fa fa-home"></i>
            </a>
            <a class="btn btn-small btn-success" href="javascript:openapp('<?php echo Url('nav/index')?>','index_termlist','分类管理');" title="分类管理">
                <i class="fa fa-th"></i>
            </a>
            <a class="btn btn-small btn-info" href="javascript:openapp('<?php echo Url('info/index')?>','index_postlist','文章管理');" title="文章管理">
                <i class="fa fa-pencil"></i>
            </a>
            <a class="btn btn-small btn-danger" href="javascript:openapp('<?php echo Url('publics/clear')?>','index_clearcache','清除缓存');" title="清除缓存">
                <i class="fa fa-trash-o"></i>
            </a>
        </div>
        <!--left-->
        <div class="nav_wraper">
            {$menu.menuHtml}
        </div>

        <!--left-->
    </div>
    <div class="main-content">
        <div class="breadcrumbs" id="breadcrumbs"> <a id="task-pre" class="task-changebt">←</a>
            <div id="task-content">
                <ul class="macro-component-tab" id="task-content-inner">
                    <li class="macro-component-tabitem noclose" app-id="0" app-url="<?php echo url('index/home')?>" app-name="首页"> <span class="macro-tabs-item-text">首页</span> </li>
                </ul>
                <div style="clear:both;"></div>
            </div>
            <a id="task-next" class="task-changebt">→</a> </div>
        <div class="page-content" id="content">
            <iframe src="<?php echo url('index/home')?>" style="width:100%;height: 100%;" frameborder="0" id="appiframe-0"
                    class="appiframe"></iframe>
        </div>
    </div>
</div>
