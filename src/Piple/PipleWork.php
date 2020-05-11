<?php


namespace MarkWang\Piple;

use Swoole\Coroutine\Channel;

/**
 * 管道模式作业(基于swoole4.0+)
 * Class PipleWork
 * @package MarkWang\Piple
 */
class PipleWork
{
    /**
     * 获取作业任务
     * @param callable $func
     * @return Channel
     */
    protected static function getWorks(callable $func):Channel
    {
        $chan = new Channel(1);
        go(function ()use ($func,$chan){//协程执行获取作业任务
            defer(function ()use ($chan){//处理完成关闭通道
//               \Co::sleep(2);//延时关闭通道防止最后一个元素没有取出(也可以最后添加一个无用数据)
               $chan->close();
            });
            $works = $func();
            foreach ($works as $work){
                $chan->push($work);
            }
            $chan->push("workoff");//最后添加一个无用数据作为容错处理
        });
        return $chan;
    }

    /**
     * 处理作业任务
     * @param Channel $channel
     * @return Channel
     */
    protected static function handelWork(Channel $channel,callable $handFunc):Channel
    {
        $returnChan = new Channel(1);
        go(function ()use ($channel,$returnChan,$handFunc){//协程执行处理作业任务
            defer(function ()use ($returnChan){//最后关闭通道
                $returnChan->close();//处理完成关闭通道
            });
            while (($arg = $channel->pop())!==false){
                if($arg!="workoff"){
                    $res = $handFunc($arg);//处理作业任务
                    $returnChan->push($res);//将处理结果塞入通道
                }
            }
        });
        return $returnChan;
    }

    /**
     * 使用管道模式来处理作业任务
     * @param callable $getWork 获取作业任务函数
     * @param callable $handleWork 处理作业任务函数
     * @param int $handleNum 处理作业任务函数数量
     * @return Channel
     */
    public static function pipleCmd(callable $getWork, callable $handleWork,int $handleNum=1):Channel
    {
        $returnChan = new Channel(1);
        //获取作业任务
//        $workChan = self::getWorks($getWork);
        $workChan = $getWork();
        //获取同步锁(因为要所有处理函数的任务执行完成,数据才是完整的)
        $wg=new \Swoole\Coroutine\WaitGroup();
        //根据处理任务函数的数量来处理任务
        for ($i=1;$i<=$handleNum;$i++){
            $wg->add();
            $handleChan = self::handelWork($workChan,$handleWork);
            go(function ()use ($handleChan,$returnChan,$wg){
                defer(function ()use ($returnChan,$wg){//执行完成关闭通道
                    $wg->done();
                });
                //获取处理结果塞入返回通道
                while (($handRes=$handleChan->pop())!==false){
                    $returnChan->push($handRes);
                }
                $returnChan->push("handleoff");//容错,防止最后一个接收不到
            });
        }
        //等到所有的处理函数完成(需要使用协程否则会阻塞)
        go(function ()use ($wg,$returnChan){
            defer(function ()use ($returnChan){
              $returnChan->close();
            });
            $wg->wait();
        });
        return $returnChan;
    }

}