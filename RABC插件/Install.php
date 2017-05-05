<?php
# 引入配置文件
include 'config.php';
$rbac->Install();                                   // 创建 Rbac表

echo '创建 Rbac表成功<br/><a href="Index.php">点击进入控制台</a>';
