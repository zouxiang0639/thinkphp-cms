// 所有加了dialog类名的a链接，自动弹出它的href
if ($('a.js-dialog').length) {
    Wind.use('artDialog', 'iframeTools', function() {
        $('.js-dialog').on('click', function(e) {
            e.preventDefault();
            var $this = $(this);
            art.dialog.open($(this).prop('href'), {
                close : function() {
                    $this.focus(); // 关闭时让触发弹窗的元素获取焦点
                    return true;
                },
                title : $this.prop('title')
            });
        }).attr('role', 'button');

    });
}

/**
 * 单个图片上传
 * @param dialog_title 上传对话框标题
 * @param input_selector 图片容器
 * @param extra_params 额外参数，object
 * @param app  应用名,CMF的应用名
 */
function upload_one_image(dialog_title, input_selector, extra_params, app) {
    open_upload_dialog(dialog_title, function (dialog, files) {
        $(input_selector).val(files['filepath']);
        $(input_selector + '-preview').attr('src', files['preview_url']);
        $(input_selector + '-name').val(files['name']);
    }, extra_params, 0, 'image', app);
}

/**
 * 打开文件上传对话框
 * @param dialog_title 对话框标题
 * @param callback 回调方法，参数有（当前dialog对象，选择的文件数组，你设置的extra_params）
 * @param extra_params 额外参数，object
 * @param multi 是否可以多选
 * @param filetype 文件类型，image,video,audio,file
 * @param app  应用名，CMF的应用名
 */
function open_upload_dialog(dialog_title,callback,extra_params,multi,filetype,app){
    multi = multi?1:0;
    filetype = filetype?filetype:'image';
    app = app?app:GV.APP;
    var params = '&multi='+multi+'&filetype='+filetype+'&app='+app ;
    Wind.use("artDialog","iframeTools",function(){
        art.dialog.open(GV.ROOT+'file/uploadPicture?g=asset&m=asset&a=plupload'  + params, {
            title: dialog_title,
            id: new Date().getTime(),
            width: '650px',
            height: '420px',
            lock: true,
            fixed: true,
            background:"#CCCCCC",
            opacity:0,
            ok: function() {
                if (typeof callback =='function') {
                    var iframewindow = this.iframe.contentWindow;
                    var files=iframewindow.get_selected_files();
                    if(files){
                        callback.apply(this, [this, files,extra_params]);
                    }else{
                        return false;
                    }

                }
            },
            cancel: true
        });
    });
}