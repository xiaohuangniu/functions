<?php
/*
 +----------------------------------------------------------------------
 + Title        : PHP7的一些新特征
 + Author       : 小黄牛
 + Version      : V1.0.0.1
 + Initial-Time : 2017-5-17 10:43:00
 + Last-time    : 2017-5-17 10:43:00 + 小黄牛
 + Desc         : 
 +     对于数据类型声明，PHP7有了很大的改变，存在两种模式：强制 (默认) 和 严格模式。

 +     强制模式下：
 +     在PHP7种，已支持：
                字符串(string), 
                整数 (int), 
                浮点数 (float), 
                布尔值 (bool)
                接口(interfaces)
                类(类名)
                数组(array)
                回调(callable)
 +----------------------------------------------------------------------
*/

header("Content-type: text/html; charset=utf-8");

# 强制申明类型 - 传参强制只支持以下6种，interfaces与callable只在强制回调类型中有生效
function demo(string $A, int $B, float $C, bool $D, array $E, Test $F){

}

# 定义一个测试的class
class Test{}

demo(
    '我爱小黄牛',
    100,
    2.1,
    true,
    ['大哥'],
    new Test()
);