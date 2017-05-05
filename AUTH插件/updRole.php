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

$id = $_GET['id']; // 角色ID
if(!empty($_POST)){
	# 添加角色
	$name = $_POST['name'];
	$pid  = $_POST['pid'];
	# 参数顺序不能变
	$array = array(
		0 => $id,
		1 => $name,
		2 => $pid
	);
	# 执行添加
	if ($info->updRole($array)) {
		echo '角色修改成功,2秒后自动跳转……';
		header("Refresh:2;url=listRole.php");
	}
	
}else{

# 获得修改的资料
$list = $info->selectRole($id);
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Auth 权限插件DEMO - www.junphp.com</title>
</head>

<body>
<form action="" method="post">
	角色名称 : <input name="name" type="text" value="<?php echo $list['role_name']?>"/> <br/>
    权限选择 : <br/>
  
	<?php
	$array =  explode(',',$list['role_auth_ids']);//获得角色对应的权限id
		
    foreach ($info->listAuth() as $k=>$v) {
		if (in_array($v['auth_id'],$array)){$checked = 'checked';}else{$checked = '';}
        echo "<input name='pid[]' type='checkbox' value=".$v['auth_id']." $checked>".$v['auth_name'].'<br/>';
    }
    ?>
    <br/>
    <input type="submit" value="添加">
</form>
</body>
</html>


<?php }?>