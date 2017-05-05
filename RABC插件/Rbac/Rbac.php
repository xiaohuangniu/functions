<?php
/*
 +--------------------------------------------------------------------------
 + Title        : Rbac 权限菜单
 + Author       : www.junphp.com - 极资源PHPer - 小黄牛
 + Version      : V1.0.0.1
 + Initial-Time : 2016-12-22 11:06
 + Last-time    : 2016-12-23 09:19 + 小黄牛
 + Desc         : 使用该类需要传入数据库连接信息 - 并支持PDO扩展 - PHP版本在5.3以上
 +--------------------------------------------------------------------------
*/
header("Content-type:text/html;charset=utf-8");
class Rbac{
	protected $dbMs   = 'mysql';          //数据库类型
	protected $dbHost = 'localhost';      //数据库主机名
    protected $dbName;                    //数据库名称
	protected $dbCharset = 'utf8';        //数据库编码
	protected $dbPort    = '3306';        //数据库端口
	protected $dbUser;        			  //数据库连接用户名
	protected $dbPwd;         			  //对应的密码
    protected $tablePrefix = '';   		  //数据表前缀
	protected $dbSql;         			  //记录需要执行的SQL语句
	private   $instance;                  //数据库PDO连接实例
	public    $info        = array();     //后期优化递归存储
	
	# 设置初始化参数
	public function set($key, $value){
		$this->$key = $value;
	}
	
	/*
	 * @Title  : 初始化PDO实例
	 * @Author : 小黄牛
	 * 
	*/
	public function M(){
		$this->dbPdo();//初始化PDO类
		return $this;
	}
	
	# 数据库连接
	private function dbPdo(){
		$dbn = $this->dbMs.':host='.$this->dbHost.';port='.$this->dbPort.';dbname='.$this->dbName.';charset='.$this->dbCharset;
		$dbh = new PDO($dbn,$this->dbUser,$this->dbPwd);
		$this->instance = $dbh;
	}
	
	/**
	 * 节点排序 - 有时间的朋友可以把下面的两个遍历 改成循环递归
	 * @param array $arr  节点数组
	 * @param bool $type  是否开启分级符
	 * @param bool $type2 是否为角色权限分配
	 * @author 小黄牛
	 * @return array
	 */
	private function category($arr = array(),$type = false,$type2 = false){
		$info = array();
		
		if(count($arr) > 0){
			if(!$type2){
				foreach($arr as $k => $v){
					if($v['pid'] == 0 ){ // 一级分组
						$info[] = $v;
						foreach($arr as $vv){
							if($vv['pid'] == $v['id']){ // 二维分组
								if($type){$vv['title']='├ '.$vv['title'];}
								$info[] = $vv;
								foreach($arr as $vvv){ 
									if($vvv['pid'] == $vv['id']){ // 三维数组
										if($type){$vvv['title']='│    ├ '.$vvv['title'];}
										$info[] = $vvv;
										foreach($arr as $vvvv){ 
											if($vvvv['pid'] == $vvv['id']){ // 四维数组
												if($type){$vvvv['title']='│    │    ├ '.$vvvv['title'];}
												$info[] = $vvvv;
											}
										}
									}
								}
							}
						}
					}
				}
				
			}else{
				foreach($arr as $k => $v){
					if($v['pid'] == 0 ){ // 一级分组
						$info[$k] = $v;
						foreach($arr as $kk=>$vv){
							if($vv['pid'] == $v['id']){ // 二维分组
								$info[$k]['arr'][$kk] = $vv;
								foreach($arr as $kkk=>$vvv){
									if($vvv['pid'] == $vv['id']){ // 三维分组
										$info[$k]['arr'][$kk]['arr'][$kkk] = $vvv;
										foreach($arr as $kkkk=>$vvvv){
											if($vvvv['pid'] == $vvv['id']){ // 四维分组
												$info[$k]['arr'][$kk]['arr'][$kkk]['arr'][$kkkk] = $vvvv;
											}
										}
									}
								}
							}
						}
					}
				}
				
				
			}
		}
		
		return $info;
	}
	
	/*
	 * @Title  : 查询单条节点记录 
	 * @Author : 小黄牛
	 * @param  : $id 主键ID
	*/
	public function selectNode($id){
		$pdo = $this->instance;
		$stmt = $pdo->prepare("select * from ".$this->tablePrefix."node where id=?");
		$stmt->bindParam(1, $num);
		$num = $id;
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_ASSOC); //列名索引方式
		return $stmt->fetch();
	}
	
	/*
	 * @Title  : 查询全部节点 
	 * @Type   : 是否开启分级符
	 * @Type2  : 是否角色权限分配
	 * @Author : 小黄牛
	*/
	public function listNode($Type=true,$Type2=false){
		$pdo = $this->instance;
		# 查询全部节点菜单
		$where = ($Type2==true) ? ' where status = 1 ' : '';
		$stmt = $pdo->query("select * from ".$this->tablePrefix."node $where order by sort desc");
		$stmt->setFetchMode(PDO::FETCH_ASSOC); //列名索引方式
		$res = $stmt->fetchAll();
		return $this->category($res,$Type,$Type2);
	}
	
	/*
	 * @Title  : 添加节点
	 * @Author : 小黄牛
	 * @param  : $array 添加参数 一维数组 
	 * @return : true | false
	*/
	public function addNode($array){
		$pdo = $this->instance;
		$stmt = $pdo->prepare("INSERT INTO ".$this->tablePrefix."node (name,title,status,remark,sort,pid,level) VALUES (?,?,?,?,?,?,?)");
		$stmt->bindParam(1, $num1);
		$stmt->bindParam(2, $num2);
		$stmt->bindParam(3, $num3);
		$stmt->bindParam(4, $num4);
		$stmt->bindParam(5, $num5);
		$stmt->bindParam(6, $num6);
		$stmt->bindParam(7, $num7);
		$num1 = $array['name'];
		$num2 = $array['title'];
		$num3 = (empty($array['status'])) ? 1 : $array['status'];
		$num4 = $array['remark'];
		$num5 = (empty($array['sort'])) ? 0 : $array['sort'];
		$num6 = $array['pid'];
		$num7 = $array['level'];
		return $stmt->execute();
	}
	
	/*
	 * @Title  : 删除节点 
	 * @Author : 小黄牛
	 * @param  : $id 主键ID
	*/
	public function deleteNode($id){
		$pdo = $this->instance;
		# 删除单条记录
		$stmt = $pdo->prepare("delete from ".$this->tablePrefix."node where id=?");
		$stmt->bindParam(1, $num);
		$num = $id;
		return $stmt->execute();
	}
	
	/*
	 * @Title  : 修改节点
	 * @Author : 小黄牛
	 * @param  : $array 修改参数 一维数组
	 * @return : true | false
	*/
	public function updNode($array){
		$pdo = $this->instance;
		$stmt = $pdo->prepare("update ".$this->tablePrefix."node set name=?,title=?,status=?,remark=?,sort=?,pid=?,level=? where id=?");
		$stmt->bindParam(1, $num1);
		$stmt->bindParam(2, $num2);
		$stmt->bindParam(3, $num3);
		$stmt->bindParam(4, $num4);
		$stmt->bindParam(5, $num5);
		$stmt->bindParam(6, $num6);
		$stmt->bindParam(7, $num7);
		$stmt->bindParam(8, $num8);
		$num1 = $array['name'];
		$num2 = $array['title'];
		$num3 = (empty($array['status'])) ? 1 : $array['status'];
		$num4 = $array['remark'];
		$num5 = (empty($array['sort'])) ? 0 : $array['sort'];
		$num6 = $array['pid'];
		$num7 = $array['level'];
		$num8 = $array['id'];
		return $stmt->execute();
	}
	
	/*
	 * @Title  : 查询单条角色记录 
	 * @Author : 小黄牛
	 * @param  : $id 主键ID
	*/
	public function selectRole($id){
		$pdo = $this->instance;
		$stmt = $pdo->prepare("select * from ".$this->tablePrefix."role where id=?");
		$stmt->bindParam(1, $num);
		$num = $id;
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_ASSOC); //列名索引方式
		return $stmt->fetch();
	}
	
	/*
	 * @Title  : 查询全部角色
	 * @Type   : 是否开启分级符
	 * @Author : 小黄牛
	*/
	public function listRole($Type=true){
		$pdo = $this->instance;
		$stmt = $pdo->query("select * from ".$this->tablePrefix."role");
		$stmt->setFetchMode(PDO::FETCH_ASSOC); //列名索引方式
		$res = $stmt->fetchAll();
		return $this->category($res,$Type);
	}
	
	/*
	 * @Title  : 添加角色
	 * @Author : 小黄牛
	 * @param  : $array 添加参数 一维数组 
	 * @return : true | false
	*/
	public function addRole($array){
		$pdo = $this->instance;
		$stmt = $pdo->prepare("INSERT INTO ".$this->tablePrefix."role (title,status,remark,pid) VALUES (?,?,?,?)");
		$stmt->bindParam(1, $num1);
		$stmt->bindParam(2, $num2);
		$stmt->bindParam(3, $num3);
		$stmt->bindParam(4, $num4);
		$num1 = $array['title'];
		$num2 = (empty($array['status'])) ? 1 : $array['status'];
		$num3 = $array['remark'];
		$num4 = $array['pid'];
		return $stmt->execute();
	}
	
	/*
	 * @Title  : 获得角色所有的权限
	 * @Author : 小黄牛
	 * @param  : $id 角色id
	 * @return : array
	*/
	public function selectNR(){
		$node = $this->listNode(false,true);   // 拿到所有的权限
		# 第一次分配权限
		return $node;
	}
	
	/*
	 * @Title  : 是否选中的权限
	 * @Author : 小黄牛
	 * @param  : $id 角色id
	 * @return : array
	*/
	public function choiceNR($id,$nodeid){
		$pdo  = $this->instance;
		$stmt = $pdo->prepare("select node_id from ".$this->tablePrefix."access where role_id = ?");
		$stmt->bindParam(1, $num);
		$num  = $id;
		$stmt->setFetchMode(PDO::FETCH_ASSOC); // 列名索引方式
		$stmt->execute();
		$res  = $stmt->fetchAll();
		foreach ($res as $v){
			if ($nodeid == $v['node_id']) {return true;}
		}
		return false;
	}
	
	/*
	 * @Title  : 修改角色拥有的权限
	 * @Author : 小黄牛
	 * @param  : $array 修改参数 二维数组
	 * @return : true | false
	*/
	public function updNR($array){
		$pdo = $this->instance;
		# 先把原来的权限删了
		$del = $pdo->prepare("delete from ".$this->tablePrefix."access where role_id=?");
		$del->bindParam(1, $num);
		$num = $array['id'];
		$del->execute();
		foreach ($array['data'] as $v) {
			$stmt = $pdo->query("select pid,level from ".$this->tablePrefix."node where id=$v");
			$stmt->setFetchMode(PDO::FETCH_ASSOC); //列名索引方式
			$res = $stmt->fetch();
			$pdo->exec("INSERT INTO ".$this->tablePrefix."access (role_id,node_id,level,pid) VALUES ('".$array['id']."',$v,'".$res['level']."','".$res['pid']."')");
		}
	}
	
	/*
	 * @Title  : 分配角色权限
	 * @Author : 小黄牛
	 * @param  : $array 修改参数 一维数组
	 * @return : true | false
	*/
	public function updRole($array){
		$pdo = $this->instance;
		$stmt = $pdo->prepare("update ".$this->tablePrefix."role set title=?,status=?,remark=?,pid=? where id=?");
		$stmt->bindParam(1, $num1);
		$stmt->bindParam(2, $num2);
		$stmt->bindParam(3, $num3);
		$stmt->bindParam(4, $num4);
		$stmt->bindParam(5, $num5);
		$num1 = $array['title'];
		$num2 = (empty($array['status'])) ? 1 : $array['status'];
		$num3 = $array['remark'];
		$num4 = $array['pid'];
		$num5 = $array['id'];
		return $stmt->execute();
	}
	
	/*
	 * @Title  : 删除角色 
	 * @Author : 小黄牛
	 * @param  : $id 主键ID
	*/
	public function deleteRole($id){
		$pdo = $this->instance;
		# 删除单条记录
		$stmt = $pdo->prepare("delete from ".$this->tablePrefix."role where id=?");
		$stmt->bindParam(1, $num);
		$num = $id;
		return $stmt->execute();
	}
	
	/*
	 * @Title  : 添加管理员关联 角色
	 * @Author : 小黄牛
	 * @param  : $id   管理员ID
	 * @param  : $pid  角色组ID 
	 * @return : true | false
	*/
	public function addUser($id, $pid){
		$pdo = $this->instance;
		$stmt = $pdo->prepare("INSERT INTO ".$this->tablePrefix."role_user (user_id,role_id) VALUES (?,?)");
		$stmt->bindParam(1, $num1);
		$stmt->bindParam(2, $num2);
		$num1 = $id;
		$num2 = $pid;
		return $stmt->execute();
	}
	
	# 根据管理员ID 获得对应的权限列表
	private function rbacUser($id){
		$pdo = $this->instance;
		$stmt = $pdo->prepare("select role_id from ".$this->tablePrefix."role_user where user_id=?");
		$stmt->bindParam(1, $num);
		$num = $id;
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_ASSOC); //列名索引方式
		$res = $stmt->fetch();
		# 没找到管理员
		if (!$res) {return false;}
		
		$stmt = $pdo->query("select pid from ".$this->tablePrefix."role where id=".$res['role_id']);
		$stmt->setFetchMode(PDO::FETCH_ASSOC); //列名索引方式
		$res2 = $stmt->fetch();
		
		# 先获得所拥有的的权限
		# 超级管理员
		if ($res2['pid'] == 0){
			$stmt = $pdo->query('select id,name,pid,title from '.$this->tablePrefix.'node');
			$stmt->setFetchMode(PDO::FETCH_ASSOC); //列名索引方式
			$route = $stmt->fetchAll();
		}else{
			$stmt = $pdo->query('select node_id from '.$this->tablePrefix.'access where role_id='.$res['role_id'].' order by pid asc,level asc');
			$stmt->setFetchMode(PDO::FETCH_ASSOC); //列名索引方式
			$list = $stmt->fetchAll();
			foreach ($list as $v) {
				$stmt = $pdo->query('select id,name,pid,title from '.$this->tablePrefix.'node where id='.$v['node_id']);
				$stmt->setFetchMode(PDO::FETCH_ASSOC); //列名索引方式
				$route[] = $stmt->fetch();
			}
		}
		return $route;
	}
	
	
	/*
	 * @Title  : 登录权限验证
	 * @Author : 小黄牛
	 * @param  : $id 参数
	 * @return : true | false
	*/
	public function rbacCheck($id, $model, $controller, $action){
		$route = $this->rbacUser($id);
		/* 验证角色拥有的权限 */
		# 分组判断
		foreach ($route as $v) {
			if ($v['name'] == $model){
				# 控制器判断
				foreach ($route as $vv) {
					if ($vv['name'] == $controller && $vv['pid'] == $v['id']){
						# 操作方法判断
						foreach ($route as $vvv) {
							if ($vvv['name'] == $action && $vvv['pid'] == $vv['id']){
								return true;
							break;
							}
						}
					break;
					}
				}
			break;
			}
		}
		
		return false;
	}
	
	/*
	 * @Title  : 查询管理员拥有的全部权限节点 
	 * @Author : 小黄牛
	*/
	public function listUser($id){
		$res = $this->rbacUser($id);
		# 查询全部节点菜单
		return $this->category($res,false,true);
	}
	
	# 安装 Rbac所需表
	public function Install(){
		$pdo = $this->instance;
		
		# 节点表
		$sql = "CREATE TABLE IF NOT EXISTS `".$this->tablePrefix."node` (
			  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
			  `name` varchar(20) NOT NULL COMMENT '路由',
			  `title` varchar(50) DEFAULT NULL COMMENT '显示名',
			  `status` tinyint(1) DEFAULT '1' COMMENT '0禁用/1开启',
			  `remark` varchar(255) DEFAULT NULL COMMENT '描述',
			  `sort` smallint(6) unsigned DEFAULT '0' COMMENT '排序',
			  `pid` smallint(6) unsigned NOT NULL COMMENT '父ID',
			  `level` tinyint(1) unsigned NOT NULL COMMENT '级别;1/分组/2控制器/3操作方法',
			  PRIMARY KEY (`id`),
			  KEY `level` (`level`),
			  KEY `pid` (`pid`),
			  KEY `status` (`status`),
			  KEY `name` (`name`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='权限节点表' AUTO_INCREMENT=81 ;";
		$pdo->exec($sql);
		
		# 角色表
		$sql = "CREATE TABLE IF NOT EXISTS `".$this->tablePrefix."role` (
			  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
			  `title` varchar(20) NOT NULL COMMENT '角色名',
			  `pid` smallint(6) DEFAULT NULL COMMENT '父ID',
			  `status` tinyint(1) unsigned DEFAULT '1' COMMENT '0禁用/1开启',
			  `remark` varchar(255) DEFAULT NULL COMMENT '描述',
			  PRIMARY KEY (`id`),
			  KEY `pid` (`pid`),
			  KEY `status` (`status`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='权限角色表' AUTO_INCREMENT=6 ;";
		$pdo->exec($sql);
		#初始化一条超级管理员角色
		$pdo->exec("INSERT INTO ".$this->tablePrefix."role (title,pid,remark) VALUES ('超级管理员',0,'系统内置超级管理员组，不受权限分配账号限制')");
		
		# 用户 & 角色 关联表
		$sql = "CREATE TABLE IF NOT EXISTS `".$this->tablePrefix."role_user` (
			  `role_id` mediumint(9) unsigned DEFAULT NULL COMMENT '角色组ID',
			  `user_id` char(32) DEFAULT NULL COMMENT '管理员ID',
			  KEY `group_id` (`role_id`),
			  KEY `user_id` (`user_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户角色表';";
		$pdo->exec($sql);
		
		# 节点 & 角色 关联表
		$sql = "CREATE TABLE IF NOT EXISTS `".$this->tablePrefix."access` (
			  `role_id` smallint(6) unsigned NOT NULL COMMENT '角色ID',
			  `node_id` smallint(6) unsigned NOT NULL COMMENT '节点ID',
			  `level` tinyint(1) NOT NULL COMMENT '节点表中的等级项',
			  `pid` smallint(6) DEFAULT NULL COMMENT '节点表中的父ID项',
			  KEY `groupId` (`role_id`),
			  KEY `nodeId` (`node_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='权限分配表';";
		$pdo->exec($sql);
	}
}