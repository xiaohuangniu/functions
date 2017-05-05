<style>
ul{list-style:none; padding:0px; margin:0px;}
ul ul{margin-left:30px;}
ul li ul{margin-left:60px}
</style>
<?php

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

$res = $info->getList(1);

echo '<ul>';
foreach ($res as $k=>$v) {
	# 1级菜单
	if ($v['auth_level'] == 0){echo '<li>'.$v['auth_name'].'</li>';}
	# 2级菜单
	if ($v['auth_level'] == 1){echo '<ul><li>'.$v['auth_name'].'</li></ul>';}
	# 3级菜单
	if ($v['auth_level'] == 2){echo '<ul><ul><li>'.$v['auth_name'].'</li></ul></ul>';}
}
echo '</ul>';
?>
