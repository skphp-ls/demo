<script type="text/javascript">
$(function () {
	$('.form1').form({
		action:'<?=$ajax_url?>',
		ready : function (items)
		{
			<?=js_check($arr_rule, 'blur')?>	
		},
		callback : function (json)
		{
			if(json.ret)
			{
				FormModal.alertOk('alert', json.msg);
				delay_redirect();
			}else{
				FormModal.alertFail('alert', json.msg);
			}
		}
	});
	$('[name=sex][value=<?=$data['sex']?>]').attr('checked', true);
})
</script>    
<style type="text/css">
.check_btn{
	font-size: 14px;
	background: #337ab7;
}
</style>
<form class="form-horizontal form1" method="post">
<input type="hidden" name="id" value="<?=$data['id']?>" />
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title">客户信息</h4>
</div>
<div class="modal-body">			
		<div class="form-group">
			<label class="col-sm-2 control-label">真实姓名</label>
			<div class="col-sm-10">
				<input type="text" name="truename" class="form-control w300 input-lg" value="<?=$data['truename']?>">
			</div>
		</div>			
		<div class="form-group">
			<label class="col-sm-2 control-label">性别</label>
			<div class="col-sm-10">
					<label class="radio-inline">
					  <input type="radio" name="sex" value="1"> 先生
					</label>
					<label class="radio-inline">
					  <input type="radio" name="sex" value="2"> 女生
					</label>
			</div>
		</div>					
		<div class="form-group">
			<label class="col-sm-2 control-label">联系手机</label>
			<div class="col-sm-10">
				<input type="text" name="phone" class="form-control w300 input-lg" value="<?=$data['phone']?>">
			</div>
		</div>	
		<div class="form-group">
			<label class="col-sm-2 control-label">发货地址</label>
			<div class="col-sm-10">
				<input type="text" name="address" class="form-control w300 input-lg" value="<?=$data['address']?>">
			</div>
		</div>				
		<div class="form-group alert_div">
			 <div class="col-sm-offset-2 col-sm-10">
				<div id="alert" class="w300"></div>
			</div>
		</div>
</div> 
<div class="modal-footer"> 
	<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
	<button type="submit" class="btn btn-primary">确认</button>
</div>
</form>