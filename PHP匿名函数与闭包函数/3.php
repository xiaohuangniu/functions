<?php
/*
 +----------------------------------------------------------------------
 + Title        : PHP闭包与匿名函数
 + Author       : 小黄牛
 + Version      : V1.0.0.1
 + Initial-Time : 2017-5-16 16:37:00
 + Last-time    : 2017-5-16 16:37:00 + 小黄牛
 + Desc         : 闭包函数的域限制，与如何向闭包函数传递变量
 +----------------------------------------------------------------------
*/

header("Content-type: text/html; charset=utf-8");

# 函数内部，定义一个匿名函数，即可称为闭包函数
function demo(){
    $txt  = '我爱小黄牛';
    $func = function(){
        echo $txt;
    };
    $func();
}
# 调用测试下
demo();

# 当你运行上面代码的时候，肯定会报：Notice: Undefined variable: txt in 错误，因为匿名函数本身是有域的存在，你不能跨域调用父函数的变量


# 隐藏上面的代码，我们再来试下
function demo(){
    $txt  = '我爱小黄牛';
    $func = function($txt){
        echo $txt;
    };
    $func();
}
# 调用测试下
demo();

# 运行上面的代码，会报新的错误提示：Warning: Missing argument 1 for {closure}(), called in ，因为当匿名函数成为闭包函数时，将不能直接对其进行function(变量)传递


# 隐藏上面的代码，我们最后再来试下
function demo(){
    $txt  = '我爱小黄牛';
    $func = function() use($txt){
        echo $txt;
    };
    $func();
}
# 调用测试下
demo();

# 成功了，很明显，在闭包函数中，PHP提供了一个use闭包引入变量的方式，解决了函数域与域之间参数传递的问题