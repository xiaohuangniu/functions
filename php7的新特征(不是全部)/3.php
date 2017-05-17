<?php
/*
 +----------------------------------------------------------------------
 + Title        : PHP7的一些新特征
 + Author       : 小黄牛
 + Version      : V1.0.0.1
 + Initial-Time : 2017-5-17 14:12:00
 + Last-time    : 2017-5-17 14:12:00 + 小黄牛
 + Desc         : 
 +     其实在PHP5.3之后，已经支持function声明返回的数据类型
 +----------------------------------------------------------------------
*/

# 开启严格模式
declare(strict_types = 1);

# 返回数字类型
function demo() : int {
    return 10;
}
demo();


# 定义一个测试的class
class Eco{}
# 返回Eco的实例
function test() : Eco {
    return new Eco();
}
test();