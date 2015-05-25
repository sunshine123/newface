<?php
namespace Mobile\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
		
		//获取openid 保存在session里
		if(I("get.openid"))
			//第一次从微信到小帮手来 会有get数据 保存在session里
			session("openid",I("get.openid"));
		
		//取出openid
		$openid = session("openid");
		//判断这个用户以前是否登录过
		//如果登陆过 就免登陆 在session里保存uid即可使用所有功能
		$uid = M("openid")->where("openid = '$openid'")->getField("uid");
		if($uid)
			session("uid",$uid);
		//echo $openid;
		
    	//模拟登陆
    	//session("uid","1");
    	
    	$m = M('Student');
    	$res = $m->field("stu_id,stu_praise_count,img_name")->
    		join("JOIN image ON student.stu_id=image.img_uid")->
     		order("stu_praise_count desc")->
    		limit(0,6)->
    		select();
    	
    	//获取被uid赞过的id
    	$res = $this->getPraiseId($res);
    	//var_dump($res);

    	$this->assign('res',$res);
    	$this->display();
    }
    public function more() {
    	$m = M('Student');
    	//学院id
    	$academyId = (int)I('post.aid');
    	$page = (int)I('post.page');
    	$page = $page > 0 ? $page : 1;
    	
    	$academyId = $academyId >= 1 && $academyId <=14 ? $academyId : 0;
    	$academyHash = array(
    			'1' => '通信与信息工程学院',
    			'2' => '计算机科学与技术学院',
    			'3' => '自动化学院',
    			'4' => '国际学院',
    			'5' => '生物信息学院',
    			'6' => '体育学院',
    			'7' => '经管学院',
    			'8' => '传媒学院',
    			'9' => '光电学院',
    			'10' => '国际半导体学院',
    			'11' => '外国语学院',
    			'12' => '软件学院',
    			'13' => '法学院',
    			'14' => '理学院'
    	);
    	$_aca = $academyHash[$academyId];
    	$first = ($page-1)*6;
    	if($academyId==0) {
    		$res = $m->field("stu_id,stu_praise_count,img_name")->
    		join("JOIN image ON student.stu_id=image.img_uid")->
    		order("stu_praise_count desc")->
    		limit($first,6)->
    		select();
    		//拼接标志位
    		$res = $this->getPraiseId($res);
    	}else {
    		$res = $m->field("stu_id,stu_praise_count,img_name")->
    		join("JOIN image ON student.stu_id=image.img_uid")->
    		order("stu_praise_count desc")->
    		where("stu_academy='$_aca'")->
    		limit($first,6)->
    		select();
    		//拼接标志位
    		$res = $this->getPraiseId($res);
    	}
    	$result = array(
    			"page" => $page,
    			"data" => $res
    	);
    	echo json_encode($result);
    }
    
    /**
     * 取出被这个人赞过的id 并拼接在res数组上
     */
    public function getPraiseId($res){
    	//取出所有被这个人uid赞过的
    	$uid = session("uid");
    	$result = M('praise')->where("praise_stu_id = '$uid'")->select();
    	$arr = array();
    	//把二维数组变成一位数组
    	foreach ($result as $k => $v){
    		$arr[] = $v['praise_bstu_id'];
    	}
    	
    	//给$res加一个标志位 0表示没赞 1表示赞
    	foreach ($res as $k => &$v){
    		if(in_array($v['stu_id'],$arr))
    			$v['isPraise'] = 1;
    		else 
    			$v['isPraise'] = 0;
    	}
    	
    	return $res;
    }
    
    
    
    //执行点赞操作
    public function doZan(){
	
		if(isLogin()){
			//被赞的id
			$data['praise_bstu_id'] = I("post.praise_bstu_id");
			//赞的id
			$data['praise_stu_id'] = session("uid");
			
			$uid = session("uid");
			//判断这个人今天是不是点赞超过30个
			$today = date("d",time());
			$num = M("praise")->where("praise_stu_id = '$uid' AND praise_addtime = '$today'")->count();
			if($num > 30){
				echo 4;
			}else{
				//判断是不是点过赞了
				$isPraise = M('praise') -> where($data) -> select();
				if($isPraise)
					echo 2;
				else{
					//如果没有点过就加
					$data['praise_addtime'] = date("d",time());
					$result = M('praise')->add($data);
					
					//在student表里把点赞的总数+1
					$stu_id = I("post.praise_bstu_id");
					$praise['stu_praise_count'] = (M('student')->where("stu_id = '$stu_id'")->getField("stu_praise_count") ) + 1;
					M('student')->where("stu_id = '$stu_id'")->save($praise);

					if($result){
						echo 1;
					}
					else{
						echo 2;
					}
				}
			}
		}
		else{
			echo 3;
			
		}
    	
    	
    }
	
	//登陆
	public function login(){
		//获得学号密码
		$data['stu_xuehao'] = I("post.stu_xuehao");
		$data['stu_idcard'] = I("post.stu_idcard");
		
		//从数据库中查找
		$result = M("student")->where($data)->getField("stu_id");
		
		if($result){
			//如果存在 与openid一起保存在数据库中 下次该openid再次访问 则不需要登录
			if(session("openid") != ""){
				$openid['uid'] = $result;
				
				$isSave = M("openid")->where($openid)->getField("id");
				if($isSave){
					//如果数据库里有这个数据则更新数据库
					$uid = $openid['uid'];
					$openid['openid'] = session("openid");
					M("openid")->where("uid = '$uid'")->save($openid);
				}else{
					//如果数据库里没有这个数据则添加
					$openid['openid'] = session("openid");
					M("openid")->add($openid);
				}
			}
			//将uid存在session里
			session("uid",$result);
			echo 1;
		}else{
			//如果不存在 则输出账号密码错误
			echo 2;
			
			
			
		}
	}
    
    /**
      * 保存openid和图片的url至数据库
      * 加在Mobile的IndexController里吧，好人一生平安
      */
    public function openidUrl(){
        //获取openid和url
        $openid = I('get.openid');
        //base64解密openid 并 切割字符串
        $data['openid']=$openid;
        $data['url'] = I('get.url');
        
        //保存至数据库
        $result = M('url')->add($data);
    
    }
	
    /**
	  *注销登录
	  */
	 public function logout(){
		//注销session
		session("uid",null);
		//跳转
		$this->redirect('Index/index',0);
	 
	 }
    
    
    
}