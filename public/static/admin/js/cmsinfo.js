!function(a) {

    //ajax获取页面指定内容
    $('.post_url').click(function(){
        var name = $(this).attr('title');
        if(!name){
            var name = $(this).text();
        }
        var url = $(this).attr('post-url');
        $('.modal-title').html(name);
        $.ajax(
            {
                url : url,
                type : 'post',
                dataType : 'json',
                success : function (json)
                {
                    var html = $(json).find("#ajaxContent").html();
                    $("#rechargeLog").html(html);
                },
                error:function(xhr){          //上传失败
                    $('#alert').html(alertDanger(xhr.responseText));

                },
                beforeSend:function(){
                    $("#rechargeLog").html('<img src="/statics/admin/css/loading.gif">');
                }
            });
    });

    //a标签post提交
    $('.a-post').click(function(){

        var msg =$(this).attr('post-msg');
           if(msg){
               if (!confirm(msg)){
                   return false;
               }
           }
        var url =$(this).attr('post-url');
        $.ajax(
            {
                url : url,
                type : 'post',
                dataType : 'json',
                success : function (json)
                {
                    if(json.code == 1){

                        $('#alert').html(alertSuccess(json.msg));
                        setTimeout(function() {
                            window.location.href=json.url;
                        },1000);
                    }else if(json.code == 0){
                        $('#alert').html(alertDanger(json.msg));
                    }
                    setTimeout(function() {
                        $('.close').click();
                    },3e3);
                },
                error:function(xhr){          //上传失败
                    $('#alert').html(alertDanger(xhr.responseText));

                }
            });

    });

    //form表达提交
    $(".ajax-post").click(function(){

        var data,ajaxCallUrl,postUrl;

        //解决ckeditor编辑器 ajax上传问他
        for(instance in CKEDITOR.instances){
            CKEDITOR.instances[instance].updateElement();
        }

        d = $(this).parents('.form-horizontal');
        postUrl = $(this).attr('post-url');

        //按钮上的url优先
        ajaxCallUrl = postUrl ? postUrl : d.attr('action');

        $.ajax({
            url : ajaxCallUrl,
            type : 'post',
            dataType : 'json',
            data : d.serialize(),
            success: function(json) {
                if(json.code == 1){

                    $('#alert').html(alertSuccess(json.msg));
                    if (confirm('是否离开此页')){
                        window.location.href=json.url;
                    }

                }else if(json.code == 0){
                    $('#alert').html(alertDanger(json.msg));

                }
                setTimeout(function() {
                    $('.close').click();
                },3e3);
            },
            error:function(xhr){          //上传失败

                $('#alert').html(alertDanger(xhr.responseText));
            }
        });
    });



    //按钮禁止
    a(".ajax-post").on("click",
        function() {
            var b = a(this);
            b.button("loading"),
                setTimeout(function() {
                    b.button("reset");
                },3e3)
        });


    $(".listOrder").focus(function ()
        {
            $('#alert').html(alertDanger('输入一个数字来更改排序'));
            $(this).css("background-color", "#E93333");
        }
    );
    $(".listOrder").blur(function(){

        var url,id,order;

        $(this).css("background-color", "#F1F1F1");
        url     = $('.listOrderUrl').val();
        id      = $(this).attr('data');
        order   = $(this).val();

        $.ajax(
            {
                url : url,
                type : 'post',
                dataType : 'json',
                data : 'id=' + id + '&order=' + order,
                success : function (json)
                {
                    $('#alert').html(alertSuccess('保存成功'));
                    setTimeout(function() {
                        $('.close').click();
                    },3e3);
                },
                error:function(xhr){          //上传失败

                    $('#alert').html(alertDanger(xhr.responseText));
                }
            });
    });

} (jQuery);

function toggles(obj,act,id){
    var url = $('.toggleUrl').val();
    val = ($(obj).attr('src').match(/yes.png/i)) ? 0 : 1;
    $.ajax(
        {
            url         : url,
            type        : 'post',
            dataType    : 'json',
            data        : 'val='+val+'&act='+act+'&id='+id,
            success : function (json)
            {
                if(json.code == 1){
                    var imgsrc = (json.data == 1 ) ? '/statics/admin/images/yes.png' : '/statics/admin/images/no.png';

                    $(obj).attr('src',imgsrc);

                }else if(json.code == 0){
                    $('#alert').html(alertDanger(json.msg));
                }
            },
            error:function(xhr){
                $('#alert').html(alertDanger(xhr.responseText));

            }
        });
}

//复选框全选
function check_all(obj, cName)
{
    var checkboxs = document.getElementsByName(cName);
    for (var i = 0; i < checkboxs.length; i++)
    {
        checkboxs[i].checked = obj.checked;
    }
}


function alertSuccess(data){
    return '<div class="alert alert-success" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>'+data+'</div>';
}


function alertDanger(data){
    return '<div class="alert alert-danger" role="alert" style="overflow-y: auto;max-height: 600px;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>'+data+'</div>';
}

