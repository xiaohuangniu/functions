<?php
$config = array(
	'dbName' => 'cs_rbac', // 数据库名
	'dbUser' => 'root',    // 账号
	'dbPwd'  => '',        // 密码
	'tablePrefix' => ''    // 表前缀
);

# 引入Rbac类
include './Rbac/Rbac.php';
$str = new Rbac();
$str->set('dbName',$config['dbName']);              // 数据库名
$str->set('dbUser',$config['dbUser']);              // 账号
$str->set('dbPwd',$config['dbPwd']);                // 密码
$str->set('tablePrefix',$config['tablePrefix']);    // 表前缀
$rbac = $str->M();                                  // 获得PDO实例
