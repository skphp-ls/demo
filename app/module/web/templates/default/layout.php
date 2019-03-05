<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,Chrome=1" />
<title>巨得锂代理商系统</title>
<script src="<?=$gbl_static?>/jquery/jquery.min.js"></script>
<!-- 加载 Bootstrap 的所有 JavaScript 插件。你也可以根据需要只加载单个插件。 -->
<script src="<?=$gbl_static?>/bootstrap/bootstrap.min.js"></script>
<!-- Bootstrap -->
<link href="<?=$gbl_static?>/bootstrap/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?=$static?>/css/rs.css">
<script src="<?=$gbl_static?>/com/common.js"></script>
<script src="<?=$gbl_static?>/com/cookie.js"></script>
<script src="<?=$gbl_static?>/sk/jquery-form-v3.0.js"></script>
<script src="<?=$gbl_static?>/sk/jquery-form-alert-1.0.js"></script>
<script type="text/javascript">
$(function  (){
	// alert box
	//$('.my_alert').on('click', function (){
	$(document).on('click', '.my_alert', function (){
		var $btn = $(this);
		FormModal.modal({
			'id'   	  :  'main',
			'remote'  :  $btn.attr('href')
		});
		return false;
	});

	// alert box
	//$('.my_alert_small').on('click', function (){
	$(document).on('click', '.my_alert_small', function (){
		var $btn = $(this);
		FormModal.modal({
			'id'   	  :  'small',
			'size'	  :  'sm',
			'remote'  :  $btn.attr('href')
		});
		return false;
	});	

	// 确认框
	$(document).on('click', '.my_confirm', function (){
		var $btn = $(this);
		var content = $btn.attr('title');
		FormModal.modal({
			'id'	 : 'confirm',
			'size'	 : 'sm', 
			'title'	 : '警告提示', 
			'content': content,
			'button' : function (){
				$('#confirm').modal('hide');
				if(typeof($btn.attr('href'))=="undefined") 
				{
					evt = $btn.attr('bindevt');
					eval(evt + '()');
				}else{
					location.href = $btn.attr('href');
				}
			}			
		});
		return false;
	});	

	$('.exit').click(function (){
		location.href = "/web/main_login/login_out";
	});
	
});
</script>
</head>
<body>
<div class="layout">
	<div class="header">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-1"><img src="<?=$static?>/images/rslogo.png" height="100"/></div>
				<div class="col-lg-3">
					<div class="shop_name"><?=$this->login['shopname']?></div>
				</div>	
				<div class="col-lg-7 groupbtn">
					<a type="button" class="btn btn-<?=($primary=='saleorder'?'primary':'golden');?> btn-lg" href="../main_sale/order">首页</a>
					<a type="button" class="btn btn-<?=($primary=='saleindex'?'primary':'golden');?> btn-lg" href="../main_sale/index">销售开单</a>
					<a type="button" class="btn btn-<?=($primary=='user'?'primary':'golden');?> btn-lg" href="../main/user">客户管理</a>
					<a type="button" class="btn btn-<?=($primary=='proindex'?'primary':'golden');?> btn-lg" href="../main_pro/index">进货库存</a>
					<a type="button" class="btn btn-<?=($primary=='proorder'?'primary':'golden');?> btn-lg" href="../main_pro/order">订单中心</a>		
				</div>
				<div class="col-lg-1 exit">
					<img src="<?=$static?>/images/exit.png" />
					<div class="text">[<?=$this->login['truename']?>]退出</div>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<?=$layout_content;?>
		
	</div>
</div>		
</body>
</html>