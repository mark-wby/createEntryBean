通用的php工具类

一、自动生成数据表实例类(目的是为了防止数组作为参数,导致程序不健壮)

example:

1.使用现有框架运行sql
    
    SELECT column_name,data_type,column_comment,table_name FROM information_schema.`COLUMNS` where TABLE_SCHEMA = '数据库' and TABLE_NAME = '数据表';    

2.将获取到的结果作为参数
    
\MarkWang\AutoCreateEntryBean\CreateEntryBean::setTableStructs($tableStructs);//设置生成的数据表结构
\MarkWang\AutoCreateEntryBean\CreateEntryBean::setTargetPath($targetPath);//设置生成的文件的目录
\MarkWang\AutoCreateEntryBean\CreateEntryBean::setNameSpace($nameSpace);//设置生成的命名空间
\MarkWang\AutoCreateEntryBean\CreateEntryBean::createEntryBean();//生成文件


