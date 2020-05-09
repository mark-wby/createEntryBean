<?php


namespace MarkWang\AutoCreateEntryBean;

/**
 * 自动生成数据表实例类
 * Class CreateEntryBean
 * @package MarkWang
 */
class CreateEntryBean
{
    /**
     * @var 实例类的命名空间
     */
    protected static $nameSpace;
    /**
     * @var 数据库表结构
     */
    protected static $tableStructs;

    /**
     * @var 生成的实例类存放的文件位置
     */
    protected static $targetPath;

    /**
     * @param 实例类的命名空间 $nameSpace
     */
    public static function setNameSpace($nameSpace)
    {
        self::$nameSpace = $nameSpace;
    }

    /**
     * @param 生成的实例类存放的文件位置 $targetPath
     */
    public static function setTargetPath($targetPath)
    {
        self::$targetPath = $targetPath;
    }

    /**
     * @param 数据库表结构 $tableStructs
     */
    public static function setTableStructs($tableStructs)
    {
        self::$tableStructs = $tableStructs;
    }

    /**
     * 生成数据表实例类
     * @throws \Exception
     */
    public static function createEntryBean()
    {
        if(!self::$tableStructs){
            throw new \Exception("请设置数据表结构");
        }
        if(!self::$targetPath){
            throw new \Exception("请设置生成目录");
        }
        $file = self::parseName(self::$tableStructs[0]["table_name"],2)."Bean";
        $content = "<?php";
        if(self::$nameSpace){
            $content.="
            
namespace ".self::$nameSpace.";";
        }
        $content .="
        
class $file
{";
        //循环生成字段
        foreach (self::$tableStructs as $tableStruct){
            $fieldName = self::parseName($tableStruct["column_name"],1);
            $comment = $tableStruct["column_comment"];
            $content.="
        /**
         * @var \$$fieldName $comment
         */
        protected $".$fieldName.";
        ";
        }
        //循环生成set方法
        foreach (self::$tableStructs as $tableStruct){
            $fieldName = self::parseName($tableStruct["column_name"],2);
            $arg = self::parseName($tableStruct["column_name"],1);
            $type = self::parseType($tableStruct["data_type"]);
            $content.="
        /**
         * @var $type
         */
        public function set".$fieldName."(\$$arg){
            \$this->$arg=\$$arg;
        }
        ";
        }
        //循环生成get方法
        foreach (self::$tableStructs as $tableStruct){
            $fieldName = self::parseName($tableStruct["column_name"],2);
            $arg = self::parseName($tableStruct["column_name"],1);
            $comment = $tableStruct["column_comment"];
            $type = self::parseType($tableStruct["data_type"]);
            $content.="
        /**
         * @var $comment
         * @return $type
         */
        public function get".$fieldName."(){
           return \$this->$arg;
        }
        ";
        }
        $content.="
}";
        $fileName = self::$targetPath."/$file.php";
        file_put_contents($fileName,$content);
    }

    /**
     * 将驼峰变量和下划线风格的变量名互转
     * @param string $name
     * @param int $type 0 驼峰转下划线 1 下划线转小驼峰 2 下划线转大驼峰
     * @return string
     */
    public static function parseName($name, $type=0) {
        if ($type == 1) {
            // 下划线转小驼峰
            return preg_replace_callback('/_([a-zA-Z])/', function($match){
                return strtoupper($match[1]);
            }, $name);
        }elseif ($type == 2){
            // 下划线转大驼峰
            return ucfirst( preg_replace_callback('/_([a-zA-Z])/', function($match){
                return strtoupper($match[1]);
            }, $name));
        }else {
            // 驼峰转下划线
            return strtolower(trim(preg_replace("/[A-Z]/", "_\\0", $name), "_"));
        }
    }

    /**
     * 转换数据库字段类型
     * @param $type
     * @return string
     */
    public static function parseType($type)
    {
        switch ($type){
            case in_array($type,["int","tinyint","bigint","smallint","mediumint"]):
                return "int";
            case in_array($type,["varchar","char","text"]):
                return "string";
            case in_array($type,["decimal","float","double"]):
                return "float";
            default:
                return "";
        }
    }
}