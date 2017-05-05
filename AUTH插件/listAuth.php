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
    <td>权限名称</td>
    <td>父类ID</td>
    <td>控制器</td>
    <td>操作方法</td>
    <td>全路径</td>
    <td>级别</td>
    <td>修改</td>
    <td>删除</td>
  </tr>
  <?php foreach ($info->listAuth() as $k=>$v) {?>
  <!--具体分页方法请参考 Auth类中的listAuth方法-->
  <tr>
    <td><?php echo $v['auth_id']?></td>
    <td><?php echo $v['auth_name']?></td>
    <td><?php echo $v['auth_pid']?></td>
    <td><?php echo $v['auth_c']?></td>
    <td><?php echo $v['auth_a']?></td>
    <td><?php echo $v['auth_path']?></td>
    <td><?php echo $v['auth_level']?></td>
    <td><a href="updAuth.php?id=<?php echo $v['auth_id']?>">修改</a></td>
    <td><a href="deleteAuth.php?id=<?php echo $v['auth_id']?>">删除</a></td>
  </tr>
  <?php }?>
</table>

            
				
			

</body>
</html>
