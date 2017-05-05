<?php
/* 使用方法: 管理员表 增加一个角色ID字段 用于管理员关联对应的角色 超级管理员角色ID为0*/

# 引入Auth类
include './Auth/Auth.php';
# 引入配置文件
$config = include 'config.php';

$str = new Auth();
$str->set('dbName',$config['dbName']);              // 数据库名
$str->set('dbUser',$config['dbUser']);              // 账号
$str->set('dbPwd',$config['dbPwd']);                // 密码
$str->set('tablePrefix',$config['tablePrefix']);    // 表前缀
$info = $str->M();                                  // 获得PDO实例

$array = array(
	0 => 1, // 管理员对应的角色ID - 0为超级管理员
	1 => 'Index', // 当前操作的控制器
	2 => 'Head', // 当前的才操作方法
	3 => array( // 默认不过滤的访问权限
	     	'Index/Left',
			'Index/Index',
			'Index/Footer'
		 )
);

# 执行权限过滤
if ($info->authCheck($array)) {
	die( '权限核验通过' );
}
die( '权限核验不通过' );
?>