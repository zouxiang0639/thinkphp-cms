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
 * @param filetype 文件类型
 * @param id 文件ID
 * @param app  应用名,CMF的应用名
 */
function upload_one_file(dialog_title, input_selector, filetype, extra_params, app, id) {

    filetype = filetype?filetype:'image';
    open_upload_dialog(dialog_title, function (dialog, files) {
        $(input_selector).val(files[0].filepath);
        $(input_selector + '-preview').attr('src', files[0].preview_url);
        $(input_selector + '-name').val(files[0].name);
    }, filetype, extra_params, 0, app, id);
}

/**
 * 打开文件上传对话框
 * @param dialog_title 对话框标题
 * @param callback 回调方法，参数有（当前dialog对象，选择的文件数组，你设置的extra_params）
 * @param filetype 文件类型，image,video,audio,file
 * @param extra_params 额外参数，object
 * @param multi 是否可以多选
 * @param app  应用名，CMF的应用名
 */
function open_upload_dialog(dialog_title,callback,filetype,extra_params,multi,app,id){
    multi = multi?1:0;

    filetype = filetype?filetype:'image';
    app = app?app:GV.APP;

    var params = '?multi='+multi+'&filetype='+filetype+'&app='+app+'&type=inputImgae&id='+id;
    Wind.use("artDialog","iframeTools",function(){
        art.dialog.open(GV.ROOT+'file/plupload'  + params, {
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

/**
 * 多图上传
 * @param dialog_title 上传对话框标题
 * @param container_selector 图片容器
 * @param item_tpl_wrapper_id 单个图片html模板容器id
 * @param extra_params 额外参数，object
 * @param app  应用名,CMF 的应用名
 */
function upload_multi_image(dialog_title, container_selector, item_tpl_wrapper_id, extra_params, app) {
    open_upload_dialog(dialog_title, function (dialog, files) {
        var tpl = $('#' + item_tpl_wrapper_id).html();
        var html = '';
        $.each(files, function (i, item) {
            var itemtpl = tpl;
            itemtpl = itemtpl.replace(/\{id\}/g, item.id);
            itemtpl = itemtpl.replace(/\{filepath\}/g, item.filepath);
            itemtpl = itemtpl.replace(/\{name\}/g, item.name);
            html += itemtpl;
        });
        $(container_selector).append(html);

    }, extra_params, 1, 'image', app);
}

/**
 * 查看图片对话框
 * @param img 图片地址
 */
function image_preview_dialog(img) {
    Wind.use("artDialog", function () {
        art.dialog({
            title: '图片查看',
            fixed: true,
            width: "420px",
            height: '420px',
            id: "image_preview_" + img,
            lock: true,
            background: "#CCCCCC",
            opacity: 0,
            content: '<img src="' + img + '" />'
        });
    });
}