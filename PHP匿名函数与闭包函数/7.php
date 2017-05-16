<?php
/*
 +----------------------------------------------------------------------
 + Title        : PHP闭包与匿名函数
 + Author       : 小黄牛
 + Version      : V1.0.0.1
 + Initial-Time : 2017-5-16 17:14:00
 + Last-time    : 2017-5-16 17:14:00 + 小黄牛
 + Desc         : 如何把闭包函数当做参数传递
 +----------------------------------------------------------------------
*/

header("Content-type: text/html; charset=utf-8");

/**
 * 我将调用闭包函数的实例
 * @param object : $obj 闭包函数的实例
 */
function demo($obj){
  $obj('我爱小黄牛');
}

# 传一个闭包过去
demo(function($txt){
    echo $txt;
});