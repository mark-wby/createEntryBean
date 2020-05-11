<?php

use MarkWang\Piple\PipleWork;
use Swoole\Coroutine\Channel;

require __DIR__."/../vendor/autoload.php";

//任务类
class ceshi{
    function getwork():Channel{
        $chan = new Channel(1);
        go(function ()use ($chan){//协程执行获取作业任务
            defer(function ()use ($chan){//处理完成关闭通道
                $chan->close();
            });
            $args = [1,2,3,4,5,6,7,8,9,10];
            foreach ($args as $arg){
                $chan->push($arg);
            }
            $chan->push("workoff");//最后添加一个无用数据作为容错处理
        });
        return $chan;
    }

    function hanleWork($param){
        Co::sleep(2);
        return $param*10;
    }
}



//基于swoole实现,版本在4.4+
Co\run(function(){
    $startTime = time();
    $chan = PipleWork::pipleCmd([ceshi::class,"getwork"],[ceshi::class,"hanleWork"],10);
    while (($res=$chan->pop())!==false){
        if($res!="handleoff"){
            var_dump($res);
        }
    }
    $useTime = time()-$startTime;
    var_dump("用时:{$useTime}秒");
});