<?php
# 引入配置文件
include 'config.php';
$rbac->deleteRole($_GET['id']);
echo '角色删除成功,2秒后自动跳转……';
header("Refresh:2;url=listRole.php");
?>