<?php
return [

    //数据库备份配置
    'data_backup_path'          =>  ROOT_PATH.'database'.DS.'backups'.DS, //数据备份文件列表
    'data_backup_part_size'     => 20971520,
    'data_backup_compress'      => 1,
    'data_backup_compress_level'=> 9,

    //上传文件配置
    'file' => [
        'image' => ['path' => DS.'uploads'.DS.'img'.DS, 'ext' => 'jpg,jpeg,png,gif,bmp4'],
        'file'  => ['path' => DS.'uploads'.DS.'file'.DS, 'ext' => 'txt,dwg,pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar'],
        'video' => ['path' => DS.'uploads'.DS.'video'.DS, 'ext' => 'mp4,avi,wmv,rm,rmvb,mkv'],
        'audio' => ['path' => DS.'uploads'.DS.'audio'.DS, 'ext' => 'mp3,wma,wav']
    ],

    //缩略图
    'thumb_image' => [
        'height'    => 300,
        'width'     => 300,
    ]

];