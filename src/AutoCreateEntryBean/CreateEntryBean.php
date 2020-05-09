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
     * @var 数据库表结构
     */
    protected static $tableStructs;

    /**
     * @var 生成的实例类存放的文件位置
     */
    protected static $targetPath;

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
        //循环生成
        foreach (self::$tableStructs as $tableStruct){

        }
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
}