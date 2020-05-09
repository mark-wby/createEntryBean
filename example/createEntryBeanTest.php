<?php

use MarkWang\AutoCreateEntryBean\CreateEntryBean;

require __DIR__."/../vendor/autoload.php";

//设置文件生成路径
CreateEntryBean::setTargetPath(__DIR__."/");

//设置文件命名空间
CreateEntryBean::setNameSpace("arr\\example");

//设置需要生成的数据表结构
CreateEntryBean::setTableStructs(
    [
        [
            "table_name"=>"books",
            "column_name"=>"id",
            "data_type"=>"int",
            "column_comment"=>"主键"
        ],
        [
            "table_name"=>"books",
            "column_name"=>"book_name",
            "data_type"=>"varchar",
            "column_comment"=>"图书名称"
        ]
    ]
);

//生成文件
CreateEntryBean::createEntryBean();

