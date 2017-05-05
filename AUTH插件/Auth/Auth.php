<?php
/*
 +--------------------------------------------------------------------------
 + Title        : AUTH 权限菜单
 + Author       : www.junphp.com - 极资源PHPer - 小黄牛
 + Version      : V1.0.0.1
 + Initial-Time : 2016-12-13 15:16
 + Last-time    : 2016-12-13 15:16 + 小黄牛
 + Desc         : 使用该类需要传入数据库连接信息 - 并支持PDO扩展 - PHP版本在5.3以上
 +--------------------------------------------------------------------------
*/
header("Content-type:text/html;charset=utf-8");
class Auth{
	protected $dbMs   = 'mysql';          //数据库类型
	protected $dbHost = 'localhost';      //数据库主机名
    protected $dbName;                    //数据库名称
	protected $dbCharset = 'utf8';        //数据库编码
	protected $dbPort    = '3306';        //数据库端口
	protected $dbUser;        			  //数据库连接用户名
	protected $dbPwd;         			  //对应的密码
    protected $tablePrefix = '';   		  //数据表前缀
	protected $dbSql;         			  //记录需要执行的SQL语句
	protected $LengTh = 5;         	      //auth长度 5就是最多只有4级
	private   $instance;                  //数据库PDO连接实例
	
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
	
	/*
	 * @Title  : 查询单条Auth记录 
	 * @Author : 小黄牛
	 * @param  : $id 主键ID
	*/
	public function selectAuth($id){
		$pdo = $this->instance;
		$stmt = $pdo->prepare("select * from ".$this->tablePrefix."auth where auth_id=?");
		$stmt->bindParam(1, $num);
		$num = $id;
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_ASSOC); //列名索引方式
		return $stmt->fetch();
	}
	
	/*
	 * @Title  : 查询多条Auth 
	 * @Author : 小黄牛
	 * @param  : $page   当前页数
	 * @param  : $length 分页长度
	 * @param  : $symbol 显示符
	*/
	public function listAuth($page='', $length=15, $symbol='&nbsp;&nbsp;&nbsp;&nbsp;'){
		$pdo = $this->instance;
		# 查询全部权限菜单
		if (empty($page)) {
			$stmt = $pdo->query("select * from ".$this->tablePrefix."auth order by auth_path asc");
		}else{
			# 分页查询
			if($page == 1){
				$stmt = $pdo->query("select * from ".$this->tablePrefix."auth order by auth_path asc limit ".($page-1).",$length");
			}else{
				$stmt = $pdo->query("select * from ".$this->tablePrefix."auth order by auth_path asc limit ".(($page-1)*10).",$length");
			}
		}	
		$stmt->setFetchMode(PDO::FETCH_ASSOC); //列名索引方式
		$res = $stmt->fetchAll();
		foreach ($res as $k => $v) {
			$res[$k]['auth_name'] = str_repeat("$symbol",$v['auth_level']).$res[$k]['auth_name'];
		}
		return $res;
	}
	
	/*
	 * @Title  : 删除Auth权限 
	 * @Author : 小黄牛
	 * @param  : $id 主键ID
	*/
	public function deleteAuth($id){
		$pdo = $this->instance;
		# 查询
		$info = $this->selectAuth($id);
		# 删除单条记录
		$stmt = $pdo->prepare("delete from ".$this->tablePrefix."auth where auth_id=?");
		$stmt->bindParam(1, $num);
		$num = $id;
		return $stmt->execute();
	}
	
	/*
	 * @Title  : 添加权限
	 * @Author : 小黄牛
	 * @param  : $array 添加参数 一维数组 顺序为 [权限名称,权限父类,控制器,操作方法]
	 * @return : true | false
	*/
	public function addAuth($array){
		$pdo = $this->instance;
		$stmt = $pdo->prepare("INSERT INTO ".$this->tablePrefix."auth (auth_name,auth_pid,auth_c,auth_a) VALUES (?,?,?,?)");
		$stmt->bindParam(1, $num1);
		$stmt->bindParam(2, $num2);
		$stmt->bindParam(3, $num3);
		$stmt->bindParam(4, $num4);
		$num1 = $array[0];
		$num2 = $array[1];
		$num3 = $array[2];
		$num4 = $array[3];
		$info = $stmt->execute();
		if(!$info){return false;}
		
		$id = $pdo->lastInsertId();
		
		if($array[1] == 0){
			$auth_path = $id;
        } else {
            $pinfo = $this -> selectAuth($array[1]); // 查询出父级的记录信息
            $p_path = $pinfo['auth_path'];           // 父级全路径
            $auth_path = $p_path."/".$id;
        }
		
		// auth_level数目：全路径里边中恒线的个数
        //      把全路径变为数组，计算数组的个数和减去-1，就是level的信息
		if(count(explode('/',$auth_path))> $this->LengTh){
			$auth_level = count(explode('/',$auth_path))-1;
		}else{
        	$auth_level = count(explode('/',$auth_path))-1;
		}
		
		
		$stmt = $pdo->prepare("update ".$this->tablePrefix."auth set auth_path=?,auth_level=? where auth_id=?");
		$stmt->bindParam(1, $num1);
		$stmt->bindParam(2, $num2);
		$stmt->bindParam(3, $num3);
		$num1 = $auth_path;
		$num2 = $auth_level;
		$num3 = $id;
		return $stmt->execute();
		
	}
	
	/*
	 * @Title  : 修改权限
	 * @Author : 小黄牛
	 * @param  : $array 添加参数 一维数组 顺序为 [权限id,权限名称,权限父类,控制器,操作方法]
	 * @return : true | false
	*/
	public function updAuth($array){
		$pdo = $this->instance;
        $id = $array[0];
		
		if($array[2] == 0){
			$auth_path = $id;
        } else {
            $pinfo = $this -> selectAuth($array[2]); // 查询出父级的记录信息
            $p_path = $pinfo['auth_path'];           // 父级全路径
            $auth_path = $p_path."/".$id;
        }
		
		// auth_level数目：全路径里边中恒线的个数
        //      把全路径变为数组，计算数组的个数和减去-1，就是level的信息
		if(count(explode('/',$auth_path))> $this->LengTh){
			$auth_level = count(explode('/',$auth_path))-1;
		}else{
        	$auth_level = count(explode('/',$auth_path))-1;
		}
		$stmt = $pdo->prepare("update ".$this->tablePrefix."auth set auth_name=?,auth_pid=?,auth_c=?,auth_a=?,auth_path=?,auth_level=? where auth_id=?");
		$stmt->bindParam(1, $num1);
		$stmt->bindParam(2, $num2);
		$stmt->bindParam(3, $num3);
		$stmt->bindParam(4, $num4);
		$stmt->bindParam(5, $num5);
		$stmt->bindParam(6, $num6);
		$stmt->bindParam(7, $num7);
		$num1 = $array[1];
		$num2 = $array[2];
		$num3 = $array[3];
		$num4 = $array[4];
		$num5 = $auth_path;
		$num6 = $auth_level;
		$num7 = $id;
		return $stmt->execute();
	}
	
	
	/*
	 * @Title  : 查询单条Role记录 
	 * @Author : 小黄牛
	 * @param  : $id 主键ID
	*/
	public function selectRole($id){
		$pdo = $this->instance;
		$stmt = $pdo->prepare("select * from ".$this->tablePrefix."role where role_id=?");
		$stmt->bindParam(1, $num);
		$num = $id;
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_ASSOC); //列名索引方式
		return $stmt->fetch();
	}
	
	/*
	 * @Title  : 查询多条Role
	 * @Author : 小黄牛
	 * @param  : $desc   排序
	 * @param  : $page   当前页数
	 * @param  : $length 分页长度
	*/
	public function listRole($desc='asc', $page='', $length=15){
		$pdo = $this->instance;
		# 查询全部角色菜单
		if (empty($page)) {
			$stmt = $pdo->query("select * from ".$this->tablePrefix."role order by role_id $desc");
		}else{
			# 分页查询
			if($page == 1){
				$stmt = $pdo->query("select * from ".$this->tablePrefix."role order by role_id $desc limit ".($page-1).",$length");
			}else{
				$stmt = $pdo->query("select * from ".$this->tablePrefix."role order by role_id $desc limit ".(($page-1)*10).",$length");
			}
		}	
		$stmt->setFetchMode(PDO::FETCH_ASSOC); //列名索引方式
		return $stmt->fetchAll();
	}
	
	/*
	 * @Title  : 删除Role角色 
	 * @Author : 小黄牛
	 * @param  : $id 主键ID
	*/
	public function deleteRole($id){
		$pdo = $this->instance;
		# 删除
		$stmt = $pdo->prepare("delete from ".$this->tablePrefix."role where role_id=?");
		$stmt->bindParam(1, $num);
		$num = $id;
		return $stmt->execute();
	}
	
	
	/*
	 * @Title  : 添加角色
	 * @Author : 小黄牛
	 * @param  : $array 添加参数 一维数组 顺序为 [角色名称,权限ID]
	 * @return : true | false
	*/
	public function addRole($array){
		# 把权限id信息由数组变为中间用逗号的分隔的字符串信息
        $auth_ids = implode(',',$array[1]);
		
		# 根据权限id信息查询具体操作方法信息
		$auth_ac = '';
        foreach ($array[1] as $k=>$v) {
			$info = $this->selectAuth($v);
			if(!empty($info['auth_c']) && !empty($info['auth_a'])){
                $auth_ac .= $info['auth_c']."/".$info['auth_a'].",";
            }
		}
		$auth_ac = rtrim($auth_ac,','); //删除最右边的逗号
		
		$pdo = $this->instance;
		$stmt = $pdo->prepare("INSERT INTO ".$this->tablePrefix."role (role_name,role_auth_ids,role_auth_ac) VALUES (?,?,?)");
		$stmt->bindParam(1, $num1);
		$stmt->bindParam(2, $num2);
		$stmt->bindParam(3, $num3);
		$num1 = $array[0];
		$num2 = $auth_ids;
		$num3 = $auth_ac;
		return $stmt->execute();
	}
	
	/*
	 * @Title  : 修改角色
	 * @Author : 小黄牛
	 * @param  : $array 添加参数 一维数组 顺序为 [角色ID,角色名称,权限ID]
	 * @return : true | false
	*/
	public function updRole($array){
		# 把权限id信息由数组变为中间用逗号的分隔的字符串信息
        $auth_ids = implode(',',$array[2]);
		
		# 根据权限id信息查询具体操作方法信息
		$auth_ac = '';
        foreach ($array[2] as $k=>$v) {
			$info = $this->selectAuth($v);
			if(!empty($info['auth_c']) && !empty($info['auth_a'])){
                $auth_ac .= $info['auth_c']."/".$info['auth_a'].",";
            }
		}
		$auth_ac = rtrim($auth_ac,','); //删除最右边的逗号
		
		$pdo = $this->instance;
		$stmt = $pdo->prepare("update ".$this->tablePrefix."role set role_name=?,role_auth_ids=?,role_auth_ac=? where role_id=?");
		$stmt->bindParam(1, $num1);
		$stmt->bindParam(2, $num2);
		$stmt->bindParam(3, $num3);
		$stmt->bindParam(4, $num4);
		$num1 = $array[1];
		$num2 = $auth_ids;
		$num3 = $auth_ac;
		$num4 = $array[0];
		return $stmt->execute();
	}
	
	/*
	 * @Title  : 登录权限验证
	 * @Author : 小黄牛
	 * @param  : $array 参数 一维数组 顺序为 [角色ID,当前控制器,当前操作方法, 不过滤的权限(一维数组)]
	 * @return : true | false
	*/
	public function authCheck($array){
		# 参数是否合法 
		if (!is_array($array)) {return  false;}
		# 0为超级管理员
		if ($array[0] == 0){return true;}
		
		# 获得当前用户访问的”控制器/操作方法“信息
		$nowac = $array[1].'/'.$array[2];
		
		# 是否为默认权限
		if (in_array($nowac,$array[3])) {return true;}
		
		# 查询角色对应的信息
		$info = $this->selectRole($array[0]);
		if (!$info) {return  false;}
		
		# 获得角色对应权限的控制器/操作方法信息
		$auth_ac =  explode(',', $info['role_auth_ac']);
		
		# 越权访问过滤判断 - 角色拥有的权限,与当前访问的权限做查询对比
		if (in_array($nowac,$auth_ac)) {return true;}
		return  false;
	}
	
	/*
	 * @Title  : 获得角色对应的权限列表
	 * @Author : 小黄牛
	 * @param  : $id 角色ID
	 * @return : true | false
	*/
	public function getList($id) {
		$pdo = $this->instance;
		# 超级管理员
		if ($id == 0) {
			$stmt = $pdo->query("select auth_name,auth_pid,auth_c,auth_a,auth_level from ".$this->tablePrefix."auth order by auth_path asc");
			$stmt->setFetchMode(PDO::FETCH_ASSOC); //列名索引方式
			$res = $stmt->fetchAll();
			return $res;
		}
		
		# 查询角色对应的信息
		$info = $this->selectRole($id);
		if (!$info) {return  false;}
		# 存在则获得权限ID
		$auth_ids = explode(',', $info['role_auth_ids']);
		# 查询出对应的权限
		foreach ($auth_ids as $v){
			$stmt = $pdo->query("select auth_name,auth_pid,auth_c,auth_a,auth_level from ".$this->tablePrefix."auth where auth_id= $v order by auth_path asc");
			$stmt->setFetchMode(PDO::FETCH_ASSOC); //列名索引方式
			$res = $stmt->fetch();
			$array[] = $res;
		}
		return $array;
		
	}
	
	
	# 安装Auth - Role权限表
	public function Install(){
		$pdo = $this->instance;
		
		# 权限表
		$sql = "CREATE TABLE IF NOT EXISTS `".$this->tablePrefix."auth` (
			  `auth_id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
			  `auth_name` varchar(20) NOT NULL COMMENT '名称',
			  `auth_pid` smallint(6) unsigned NOT NULL COMMENT '父id',
			  `auth_c` varchar(32) NOT NULL DEFAULT '' COMMENT '控制器',
			  `auth_a` varchar(32) NOT NULL DEFAULT '' COMMENT '操作方法',
			  `auth_path` varchar(32) NOT NULL COMMENT '全路径',
			  `auth_level` tinyint(4) NOT NULL DEFAULT '0' COMMENT '级别',
			  PRIMARY KEY (`auth_id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=80 ;";
		$pdo->exec($sql);
		
		# 角色表
		$sql = "CREATE TABLE IF NOT EXISTS `".$this->tablePrefix."role` (
			  `role_id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
			  `role_name` varchar(20) NOT NULL COMMENT '角色名称',
			  `role_auth_ids` varchar(128) NOT NULL DEFAULT '' COMMENT '权限ids,1,2,5',
			  `role_auth_ac` text COMMENT '模块-操作',
			  PRIMARY KEY (`role_id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;";
		$pdo->exec($sql);
	}
}

