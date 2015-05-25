<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="blank" />
    <meta name="format-detection" content="telephone=no" />
    <title>红岩网校 - 2014年新生笑脸专题活动</title>
    <meta  charset="utf-8">
    <link rel="stylesheet" type="text/css" href="/newface/Public/mobile/css/reset.css">
    <link rel="stylesheet" type="text/css" href="/newface/Public/mobile/css/mystyle.css">
    <link rel="stylesheet" type="text/css" href="/newface/Public/mobile/css/mobile.css">
</head>
<body>
<div class="wrapper">
    <div class="login_bar form_wrapper">
        <!-- <form action="#" method="#"> -->
            <a class="exit" href="##"></a>
            <h6>登录</h6>
            <div class="input_wrap clear">
                <input class="stu_number" name="stu_number" type="text" placeholder="学号">
            </div>
            <div class="input_wrap clear">
                <input class="stu_id" name="stu_id" type="password" placeholder="身份证后六位">
            </div>
            <input id="login" type="submit" value="登录">
        <!-- </form> -->
    </div>
    
    </if>
    <?php
 if(!isLogin()) echo '<a class="login_btn" href="##">登陆</a>'; else echo '<a class="logout_btn" href="/newface/index.php/Mobile/Index/logout">注销</a>'; ?>
    <div class="top_bg">
        <img src="/newface/Public/mobile/images/wrapper_bg.png">
    </div>
    <div class="return_top"></div>
<!--     <div class="return">
        <h3>返回</h3>
    </div> -->
    <div class="rule">
        <h1>活动规则</h1>
        <p>1、9月10号到12号，新同学们可以在风红莲和风雨操场参加活动，也可以微信发送照片到重邮小帮手参与活动哦~</p>
        <p>2、9月14号晚7点新生笑脸见面会，带你游戏带你飞~</p>
        <p>3、9月10号到20号，每人每天有30次投票机会，为你喜欢的笑脸投票，选出重邮最受欢迎的新生笑脸！</p>
    </div>
    <div class="classify">
        <div class="classify_click classify_click1" data-id='0'>人气笑脸</div>
        <div class="classify_click classify_click2">学院分类</div>
    </div>
    <div class="classify_select">
        <ul>
            <li data-id="1">通信与信息工程学院</li>
            <li data-id="2">计算机科学与技术学院</li>
			<li data-id="3">自动化学院</li>
			<li data-id="4">国际学院</li>
			<li data-id="5">生物信息学院</li>
			<li data-id="6">体育学院</li>
			<li data-id="7">经管学院</li>
			<li data-id="8">传媒学院</li>
			<li data-id="9">光电学院</li>
			<li data-id="10">国际半导体学院</li>
			<li data-id="11">外国语学院</li>
			<li data-id="12">软件学院</li>
			<li data-id="13">法学院</li>
			<li data-id="14">理学院</li>
 
        </ul>
    </div>
    <div class="face">
        <ul data-page='1'>
        	<?php if(is_array($res)): $i = 0; $__LIST__ = $res;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
                <div class="face_wrapper">
                    <div class="face_pic">
                    <a href="##" class='face_a'><img src="/newface/Public/thumb/<?php echo ($vo["img_name"]); ?>"></a>
                    </div>
                    
                    <button class="face_info">查看详情</button>
                    <input type="hidden" class="stu_id" value="<?php echo ($vo["stu_id"]); ?>"/>
                    
                    <div class="face_heart zan_<?php echo ($vo["stu_id"]); ?>">
                    <?php if($vo["isPraise"] == 0): ?><img src="/newface/Public/mobile/images/non_Heart.png"><?php endif; ?>
					<?php if($vo["isPraise"] == 1): ?><img src="/newface/Public/mobile/images/Heart.png"><?php endif; ?>
                           <span>
                               <?php echo ($vo["stu_praise_count"]); ?>
                           </span>
                    </div>
                </div>
            </li><?php endforeach; endif; else: echo "" ;endif; ?>
            
            
        </ul>
    </div>
    <div class="load"><h6>正在加载.<span>(´；ω；‘)</span></h6></div>
    <div class="coverflow"></div>
</div>
</body>
<script type="text/javascript">
    var zanUrl = "/newface/Public/mobile/images/";
    var moreUrl = "/newface/index.php/Mobile/Index/more";
    
    //跳转到详情页
    var detailUrl = "/newface/index.php/Mobile/Detail/";
    //点赞的url
    var doZanUrl = "/newface/index.php/Mobile/Index/doZan";
	//登陆的url
	var loginUrl = "/newface/index.php/Mobile/Index/login";
	//登陆成功跳回当前页
	var indexUrl = "/newface/index.php/Mobile/Index/index";
	//mobile的public文件夹
	var publicUrl = "/newface/Public/";
</script>
<script src="/newface/Public/mobile/js/zepto.min.js"></script>
<script src="/newface/Public/mobile/js/fastclick.js"></script>
<script src="/newface/Public/mobile/js/function.js"></script>
<script src="/newface/Public/mobile/js/login.js"></script>

</html>