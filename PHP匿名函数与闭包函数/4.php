<?php
/*
 +----------------------------------------------------------------------
 + Title        : PHP闭包与匿名函数
 + Author       : 小黄牛
 + Version      : V1.0.0.1
 + Initial-Time : 2017-5-16 16:46:00
 + Last-time    : 2017-5-16 16:46:00 + 小黄牛
 + Desc         : 如何返回闭包函数实例
 +----------------------------------------------------------------------
*/

header("Content-type: text/html; charset=utf-8");

# 直接调用将不会输出$txt的内容
function demo(){
    $txt = '我爱小黄牛';
    $func = function() use($txt){
        echo $txt;
    };
    # 这里不再直接调用，而且是把实例返回
    return $func; 
}

# 测试一下
$res = demo();  // 函数返回实例
$obj = $res();  // 再通过res()调用，没有这一步，将不会输出$txt