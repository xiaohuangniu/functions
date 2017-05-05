AUTH 权限插件
===============================================

小黄牛
-----------------------------------------------

### 1731223728@qq.com 


## 使用说明

1. 修改config.php 内的数据库信息

2. 运行authInstall.php 进行数据表安装

3. 运行addAuth.php 添加多几个测试的访问权限

4. 运行addRole.php 添加多几个测试的管理角色

5. 修改authCheck.php 内的$array验证信息 - 运行这个文件查看验证结果

6. 修改getList.php 内的角色id 获取角色对应的权限列表

## 文件说明

WEB部署目录（或者子目录）

├─Auth

│   └─Auth.php      Auth权限插件核心文件 

│

├─Index.php          DEMO控制台

├─config.php         DEMO数据库配置文件

├─authInstall.php    初始化权限与角色表

├─addAuth.php        添加权限DEMO

├─listAuth.php       权限列表DEMO

├─deleteAuth.php     删除权限DEMO

├─updAuth.php        修改权限DEMO

│

├─addRole.php        添加角色DEMO

├─listRole.php       角色列表DEMO

├─deleteRole.php     删除角色DEMO

├─updRole.php        修改角色DEMO

│

├─authCheck.php      权限验证DEMO

├─getList.php        获取角色对应的权限菜单
