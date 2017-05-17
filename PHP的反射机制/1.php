<?php
/*
 +----------------------------------------------------------------------
 + Title        : PHP类的反射
 + Author       : 小黄牛
 + Version      : V1.0.0.1
 + Initial-Time : 2017-5-17 09:58:00
 + Last-time    : 2017-5-17 09:58:00 + 小黄牛
 + Desc         : 如何简单获得一个类对应的反射信息
 +----------------------------------------------------------------------
*/

header("Content-type: text/html; charset=utf-8");

class demo{
    public    $str_1;
    private   $str_2;
    protected $str_3;

    /**
     * 我是描述
     */
    public function test(){

    }
}

# 系统内置的class，用于产生一个镜像
$class = new ReflectionClass("demo");  

echo '<pre>';
# 系统内置的class，用于导出镜像能反射的信息
Reflection::export($class);  
echo '<pre/>';

/**
 * 通常会打印出以下6种类型的信息：
 1）常量 Contants
 2）属性 Property Names
 3）方法 Method Names静态
 4）属性 Static Properties
 5）命名空间 Namespace
 6）Person类是否为final或者abstract
 */