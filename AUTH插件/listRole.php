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
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Auth 权限插件DEMO - www.junphp.com</title>
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>主键</td>
    <td>角色名称</td>
    <td>权限IDS</td>
    <td>控制器/操作方法</td>
    <td>修改</td>
    <td>删除</td>
  </tr>
  <?php foreach ($info->listRole() as $k=>$v) {?>
  <!--具体分页方法请参考 Auth类中的listRole方法-->
  <tr>
    <td><?php echo $v['role_id']?></td>
    <td><?php echo $v['role_name']?></td>
    <td><?php echo $v['role_auth_ids']?></td>
    <td><?php echo $v['role_auth_ac']?></td>
    <td><a href="updRole.php?id=<?php echo $v['role_id']?>">修改</a></td>
    <td><a href="deleteRole.php?id=<?php echo $v['role_id']?>">删除</a></td>
  </tr>
  <?php }?>
</table>

            
				
			

</body>
</html>
