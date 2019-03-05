<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<title>巨得锂代理商系统</title>
<script src="http://res.wx.qq.com/connect/zh_CN/htmledition/js/wxLogin.js"></script>
</head>
<body style="background:url('<?=$static?>/images/bg.jpg') no-repeat; background-size: cover; background-color: #4280d8;">
<div style="display: flex;width: 100%; justify-content: center; ">
	<div style="display: block; text-align: center; padding-top: 8%;">
		<div class="logo" style="font-size: 40px; color:#fff;">巨得锂代理商管理系统</div>
		<div style="width: 400px;height: 400px;background:url('<?=$static?>/images/qrbg.png') no-repeat; text-align: center; margin-top: 20px;">
			<div class="login_fast" id="login_container"></div>
		</div>
	</div>
	<div style="position: absolute; bottom: 20px; text-align: center; color:#3c65b8;">&copy;2018&emsp;湖南巨得锂有限公司&emsp;鄂ICP备18022919</div>
</div>

<script type="text/javascript">
var obj = new WxLogin({
	self_redirect:true,
	id:"login_container",
	appid:"wxb590f39393f231ec",
	scope:"snsapi_login",
	redirect_uri:"http%3A%2F%2Frs.jdlxny.com%2Fmain_login%2Fcallback",
	state:"",
	style: "black"
});	
</script>
</body>
</html>