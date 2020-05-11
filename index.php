<?php

/**
 * 获取到需要执行的任务
 */
function getWorks():\Swoole\Coroutine\Channel{
    $chan = new \Swoole\Coroutine\Channel(1);
    go(function ()use ($chan){
        defer(function ()use ($chan){
//            Co::sleep(2);
           $chan->close();
        });
       for ($i=1;$i<=10;$i++){
           $chan->push($i);
//           Co::sleep(1);
//           var_dump("塞入数据:".$i);
       }
       $chan->push(88);
    });
    return $chan;
}

/**
 * 处理作业
 * @param \Swoole\Channel $channel
 * @return \Swoole\Channel
 */
function handleWork(\Swoole\Coroutine\Channel $channel):\Swoole\Coroutine\Channel{
    $chan = new \Swoole\Coroutine\Channel(1);
    go(function ()use ($chan,$channel){
        defer(function ()use ($chan){
           $chan->close();
        });
        //循环channel通道里面的数据
        while (($tmp=$channel->pop())!==false){
//            var_dump("获取数据:".$tmp);
            $chan->push($tmp*10);
            Co::sleep(2);
        }
    });
    return $chan;
}

/**
 * 定义管道处理数据
 */
function pipleCmd(callable $getwork,callable ...$handWorks):\Swoole\Coroutine\Channel{
    //首先获取数据
    $works = $getwork();
    //多路复用处理数据
    //创建channel通道保存最后的结果
    $chan = new \Swoole\Coroutine\Channel();
    $wg=new \Swoole\Coroutine\WaitGroup();
    foreach ($handWorks as $handWork){
        $wg->add();
        $getResult = $handWork($works);
        go(function ()use ($getResult,$chan,$wg){
            defer(function ()use ($wg){
                $wg->done();
            });
            //循环channel通道里面的数据
            while (($tmp=$getResult->pop())!==false){
                $chan->push($tmp);
            }
        });
    }
    go(function ()use ($wg,$chan){
    //等所有处理任务都执行完
    //使用协程来处理否则会挂起
        defer(function ()use ($chan){
            $chan->close();
        });
        $wg->wait();
    });
    return $chan;
}

Co\run(function(){
    $startTime = time();
    $methods = ["handleWork","handleWork","handleWork","handleWork","handleWork","handleWork","handleWork","handleWork","handleWork"];
    $chan = pipleCmd("getWorks",...$methods);
    //循环channel通道里面的数据
    while (($tmp=$chan->pop())!==false){
        var_dump($tmp);
    }
    $useTime = time()-$startTime;
    var_dump("用时:$useTime"."秒");


//    $chan = new \Swoole\Coroutine\Channel();
//    go(function ()use ($chan){
//        defer(function ()use ($chan){
//            $chan->close();
//            var_dump("通道关闭");
//        });
//        for ($i=0;$i<10;$i++){
//            $chan->push($i);
//            var_dump("生产数据:".$i);
//        }
//
//    });
//    while (($tmp=$chan->pop())!==false){
//        var_dump("获取到数据:".$tmp);
//        Co::sleep(1);
//    }
//    var_dump("执行完成");

});