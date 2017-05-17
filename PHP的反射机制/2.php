<?php
/*
 +----------------------------------------------------------------------
 + Title        : PHP类的反射
 + Author       : 小黄牛
 + Version      : V1.0.0.1
 + Initial-Time : 2017-5-17 10:19:00
 + Last-time    : 2017-5-17 10:19:00 + 小黄牛
 + Desc         : 如何获得多个类对应的反射信息
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

class demo2{
    public    $res_1;
    private   $res_2;
    protected $res_3;

    /**
     * 测试一下
     */
    private function test(){

    }
}


# get_declared_classes() 获得当前所有类的名称
foreach(get_declared_classes() as $class){  
    $myclass = new ReflectionClass($class);
    # isUserDefined 检测这个类是否用户自定义的
    if($myclass->isUserDefined()){  
        echo '<pre>';
        Reflection::export($myclass);  
        echo '<pre/>';
    }  
}  