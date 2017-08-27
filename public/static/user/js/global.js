function buttonDisabledTrue(_this)
{
    _this.attr('disabled', true);
    _this.addClass('layui-btn-disabled');
}

function buttonDisabledFalse(_this)
{
    _this.attr('disabled', false);
    _this.removeClass('layui-btn-disabled');
}

$(function(){
    //手机设备的简单适配
    var treeMobile = $('.site-tree-mobile')
        ,shadeMobile = $('.site-mobile-shade');

    treeMobile.on('click', function(){
        $('body').addClass('site-mobile');
    });

    shadeMobile.on('click', function(){
        $('body').removeClass('site-mobile');
    });
})