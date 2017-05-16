<?php
/*
 +----------------------------------------------------------------------
 + Title        : PHP闭包与匿名函数
 + Author       : 小黄牛
 + Version      : V1.0.0.1
 + Initial-Time : 2017-5-16 16:57:00
 + Last-time    : 2017-5-16 16:57:00 + 小黄牛
 + Desc         : 如何向返回的闭包函数实例中，传递外部变量参数
 +----------------------------------------------------------------------
*/

header("Content-type: text/html; charset=utf-8");

# 直接调用将不会输出$txt的内容
function demo(){
    $txt = '我爱小黄牛';

    # 1、function()内的变量，为父类实例外部可传递的变量
    # 2、use()内的变量，为实例父类实例内部可传递的变量
    $func = function($str='') use($txt){
        echo $txt;
        echo '<br/>';
        echo $str;
    };
    # 这里不再直接调用，而且是把实例返回
    return $func; 
}

# 测试一下
$res = demo();          // 函数返回实例
$obj = $res('大狗蛋');  // 再通过res()调用，没有这一步，将不会输出$txt