<?php
/*
 +----------------------------------------------------------------------
 + Title        : PHP闭包与匿名函数
 + Author       : 小黄牛
 + Version      : V1.0.0.1
 + Initial-Time : 2017-5-16 17:23:00
 + Last-time    : 2017-5-16 17:23:00 + 小黄牛
 + Desc         : 使用闭包函数递归无限级分类
 +----------------------------------------------------------------------
*/

header("Content-type: text/html; charset=utf-8");


/**
 * 我将调用闭包函数的实例
 * @param array : $array 需要递归的二维数组 [id，pid,name]
 */
function demo($array){
  # 用于存储递归后的队列
  $data = [];

  # 递归函数
  $func = function (&$array, &$data, &$pid=0) use(&$func){
      foreach ($array as $k=>$v) {
        if ($v['pid'] == $pid) {
            $data[] = $v;
            # 递归自身
            $func($array, $data, $v['id']);
        }
    }
  };

  # 开始递归
  $func($array, $data);
  return $data;
}

# 测试下
$array = array(
    0 => array('id' => 1, 'pid' => 0, 'name' => '安徽省'),
    1 => array('id' => 2, 'pid' => 5, 'name' => '浙江省'),
    2 => array('id' => 3, 'pid' => 2, 'name' => '合肥市'),
    3 => array('id' => 4, 'pid' => 2, 'name' => '长丰县'),
    4 => array('id' => 5, 'pid' => 1, 'name' => '安庆市'),
);

echo '<pre>';
var_dump(demo($array));
echo '</pre>';