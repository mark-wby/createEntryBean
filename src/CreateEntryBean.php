<?php


namespace MarkWang;

/**
 * 自动生成数据表实例类
 * Class CreateEntryBean
 * @package MarkWang
 */
class CreateEntryBean
{
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

    public static function test()
    {
        var_dump("test");
    }
}