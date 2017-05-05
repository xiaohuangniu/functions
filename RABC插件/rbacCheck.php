<?php
# 引入配置文件
include 'config.php';

/*
	添加管理员时,使用这个方法进行角色关联
	管理员ID -  角色ID
	$rbac->addUser(2,7);
*/

# 权限验证 管理员ID 当前: 分组 控制器 操作方法
$res = $rbac->rbacCheck(2,'Admin','Index','Index');
# 执行权限过滤
if ($res) {
	die( '权限核验通过' );
}
die( '权限核验不通过' );
?>