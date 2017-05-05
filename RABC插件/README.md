RBAC 权限插件
===============================================

小黄牛
-----------------------------------------------

### 1731223728@qq.com 


## 使用说明

1. 修改config.php 内的数据库信息

2. 运行Install.php 进行数据表安装

3. 运行addNode.php 添加多几个测试的访问权限

4. 运行addRole.php 添加多几个测试的管理角色 - 再点击分配权限

5. 修改rbacCheck.php 内的验证参数 - 运行这个文件查看验证结果

6. 修改listUser.php  内的管理员id 获取对应的权限列表

## 文件说明

WEB部署目录（或者子目录）

├─Rbac

│   └─Rbac.php      Rbac权限插件核心文件 

│

├─Index.php          DEMO控制台

├─config.php         DEMO数据库配置文件

├─Install.php        初始化 RBAC表

│
├─addNone.php        添加节点

├─addRole.php        添加角色

├─deleteNode.php     删除节点

├─DeleteRole.php     删除角色

├─listNode.php       节点列表

├─listRole.php       角色列表

├─listUser.php       管理员拥有的列表展示

├─rbacCheck.php      管理员关联角色 与管理员权限验证

├─updNode.php        修改节点

├─updNR.php          角色分配权限

├─updRole.php        修改角色


### 不满足之处：

1、没有使用到缓存

2、没有过滤参数

3、节点并没有使用到递归(当时写着写着懵逼了，写完才想起来可以递归节点)