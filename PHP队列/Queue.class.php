<?php
/*
 +----------------------------------------------------------------------
 + Title        : PHP+MYSQL 实现消息队列
 + Author       : 小黄牛
 + Version      : V1.0.0.1
 + Initial-Time : 2017-5-16 10:31:00
 + Last-time    : 2017-5-16 15:27:00 + 小黄牛
 + Desc         : 
 +
 +    队列实现一般要遵循以下几点原则：
 +    0、理念：先进先出
 +    1、队首入队
 +    2、队尾出队
 +    3、队列元素统计
 +    4、清空队列
 +    5、选择一个合适的存储容器，有服务器的人，推荐使用Redis
 +
 +    队列一般使用到的场景：
 +    1、邮件群发，一般用在订阅模式下
 +    2、短信通知
 +    3、消息推送，当需要通过推送端口，群发信息给所有在线用户时
 +    4、商城活动：秒杀，抢购：
 +           例如，A商品需要做10元秒杀活动，当前库存为100，秒杀数为10件，那么
             则需要先从库存中取走10件商品，以免秒杀过程中出现断货的情况，由于
             参与活动的人数众多，我们就能使用到队列，先将参与活动的请求，存储
             进队列容器中，当秒杀活动过期时，再读取队列中先进的前10条信息，即
             为中奖成员。
 +
 +    本案例使用Mysql作为队列容器，表需求如下：
 +    1、需要一个唯一标识：id
 +    2、需要一个类型标题：type
 +    3、需要一个内容标识：content
 +    4、需要一个处理标识：status
 +
 +    表结构如下：
CREATE TABLE IF NOT EXISTS `queue` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '唯一标记',
  `type` tinyint(2) NOT NULL COMMENT '队列类型',
  `content` varchar(255) NOT NULL COMMENT '处理内容',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '处理状态，默认1  1|2  待处理|已处理',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='队列容器' AUTO_INCREMENT=1 ;
 +----------------------------------------------------------------------
*/

header("Content-type: text/html; charset=utf-8");
class Queue{
    private $conf_data = []; // 数据库参数
    private $PDO;            // 存储PDO的实例    
    
    /**
     * 构造方法，填充PDO参数
    */
    public function __construct($pdo_data){
        $this->conf_data = $pdo_data;
    }

    /**
     * 链接PDO
    */
    private function Mysql(){
		if (!empty($this->PDO)) { return false; }
        $dbn = $this->conf_data['DB_TYPE'].':host='.$this->conf_data['DB_HOST'].';port='.$this->conf_data['DB_PORT'].';dbname='.$this->conf_data['DB_NAME'].';charset='.$this->conf_data['DB_CHARSET'];
        $dbh = new PDO($dbn, $this->conf_data['DB_USER'], $this->conf_data['DB_PWD']);
        $this->PDO = $dbh;
        $this->PDO->query('set names '.$this->conf_data['DB_CHARSET'].';');
    }

    /**
     * 执行sql语句
     * @param string : $sql 语句
     * @return mixed : 执行结果
    */
    private function Sql($sql){
        $this->Mysql();
        $pdo = $this->PDO;

        # select操作
		$select = stripos($sql, 'select');
		if( $select=== 0 ){
			$res = $pdo->query($sql);
            $res->setFetchMode(PDO::FETCH_ASSOC); //列名索引方式
            return $res->fetchAll();
		}

        # 其余操作
        return $pdo->exec($sql);
    }

    /**
     * 入队
     * @param int        : $type     类型
     * @param int|string : $content  队列内容
    */
    public function tailEn($type, $content){
        $sql  = "insert into queue (type,content) values ({$type},'{$content}');";
        $info = $this->Sql($sql);
        if($info){
            return true;
        }
        return false;
    }

    /**
     * 出队
     * @param int         : $type 类型
     * @return array|bool : 执行结果
    */
    public function tailDe($type) {
        $sql  = "select * from queue where type={$type} and status=1 order by id limit 1;";
        $info = $this->Sql($sql);
        if($info){
            # 取出时就修改处理状态，就好比排队，既然都轮到你了，不管出于什么原因处理失败，都不是该流程需要考虑的问题
            $sql = 'update queue set status=2 where id=' .$info[0]['id']. ';';
            $res = $this->Sql($sql);
            if($res){
                return $info;
            }
        }
        return false;
    }

    /**
     * 队列长度
     * @param int  : $type 类型
     * @return int ：返回队列的长度
    */
    public function Length($type) {
        $sql  = "select count(*) from queue where type={$type} and status=1";
        $info = $this->Sql($sql);
        return $info[0]['count(*)'];
    }

    /*
     * 清空队列
     * @param int   : $type 类型
     * @return bool : 执行结果 
    */
    public function Head($type) {
        $sql  = "update queue set status=2 where type={$type} and status=1;";
        $info = $this->Sql($sql);
        return $info;
    }

}



# DEMO
$data = [
    'DB_TYPE' => 'mysql',
    'DB_HOST' => 'localhost',
    'DB_NAME' => 'test',
    'DB_USER' => 'root',
    'DB_PWD'  => 'root',
    'DB_PORT' => '3306',
    'DB_CHARSET' => 'utf8',
];
$obj = new Queue($data);
# 先进队
$obj->tailEn(1,'1731223728@qq.com');
# 取队尾
var_dump($obj->tailDe(1));
# 取长度
var_dump($obj->Length(1));
# 清空队列
var_dump($obj->Head(1));