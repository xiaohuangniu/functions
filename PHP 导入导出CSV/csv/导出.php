<?php
//使用示例
$title = ['狗带','狗蛋','小黄牛'];
$array = array(
	array('title'=> 1,'num'=>11,'age'=>'21'),
	array('title'=> 2,'num'=>12,'age'=>'22'),
	array('title'=> 3,'num'=>13,'age'=>'23'),
	array('title'=> 4,'num'=>14,'age'=>'24'),
	array('title'=> 5,'num'=>15,'age'=>'25'),
	array('title'=> 6,'num'=>16,'age'=>'26'),
	array('title'=> 7,'num'=>17,'age'=>'27'),
	array('title'=> 8,'num'=>18,'age'=>'28')
);
# 引入类
include 'Csv.php';
$str = new Csv();
$str->Export($title, $array);