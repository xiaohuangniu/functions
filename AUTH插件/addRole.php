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

if(!empty($_POST)){
	# 添加角色
	$name = $_POST['name'];
	$pid  = $_POST['pid'];
	# 参数顺序不能变
	$array = array(
		0 => $name,
		1 => $pid
	);
	# 执行添加
	if ($info->addRole($array)) {
		echo '角色添加成功,2秒后自动跳转……';
		header("Refresh:2;url=listRole.php");
	}
	
}else{
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Auth 权限插件DEMO - www.junphp.com</title>
</head>

<body>
<form action="" method="post">
	角色名称 : <input name="name" type="text" /> <br/>
    权限选择 : <br/>
  
	<?php
    foreach ($info->listAuth() as $k=>$v) {
        echo "<input name='pid[]' type='checkbox' value=".$v['auth_id'].">".$v['auth_name'].'<br/>';
    }
    ?>
    <br/>
    <input type="submit" value="添加">
</form>
</body>
</html>


<?php }?>