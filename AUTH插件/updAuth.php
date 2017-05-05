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

$id = $_GET['id']; // 权限id
if(!empty($_POST)){
	# 修改权限
	$name = $_POST['name'];
	$pid  = $_POST['pid'];
	$controller = $_POST['controller'];
	$action = $_POST['action'];
	# 参数顺序不能变
	$array = array(
		0 => $id,
		1 => $name,
		2 => $pid,
		3 => $controller,
		4 => $action
	);
	# 执行修改
	if ($info->updAuth($array)) {
		echo '权限修改成功,2秒后自动跳转……';
		header("Refresh:2;url=listAuth.php");
	}
	
}else{

# 获得修改的资料
$list = $info->selectAuth($id);
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Auth 权限插件DEMO - www.junphp.com</title>
</head>

<body>
<form action="" method="post">
	权限名称 : <input name="name" type="text" value="<?php echo $list['auth_name']?>" /> <br/>
    权限父类 : 
    	<select name="pid">
        	<option value="0">顶级权限</option>
            <?php
			foreach ($info->listAuth() as $k=>$v) {
				if ($v['auth_id'] != $id){ // 不允许选择自己当做父类
					// 选中标签
					if($v['auth_id'] == $list['auth_pid']){$selected = 'selected';}else{$selected = '';}
					echo "<option value=".$v['auth_id']." $selected>".$v['auth_name']."</option>";
				}
			}
			?>
        </select>
    <br/>
    控制器   : <input name="controller" type="text" value="<?php echo $list['auth_a']?>" /><br/>
    操作方法 : <input name="action" type="text"  value="<?php echo $list['auth_c']?>"/><br/>
    <input type="submit" value="修改">
</form>
</body>
</html>


<?php }?>