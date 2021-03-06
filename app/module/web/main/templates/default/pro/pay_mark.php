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
				FormModal.alertOk('alert_mark', json.msg);
				delay_redirect('/web/main_pro/order');
			}else{
				FormModal.alertFail('alert_mark', json.msg);
			}
		}
	});
})
</script>    
<form class="form-horizontal form1" method="post">
<input type="hidden" name="id" value="<?=$id?>" />
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title">支付信息</h4>
</div>
<div class="modal-body">		
	<div class="form-group">
		<label class="col-sm-2 control-label">支付证明</label>
		<div class="col-sm-10">
			<textarea name="mark" class="form-control"></textarea>
		</div>
	</div>			
	<div class="form-group alert_div">
		 <div class="col-sm-offset-2 col-sm-10">
			<div id="alert_mark" class="w300"></div>
		</div>
	</div>
</div> 
<div class="modal-footer"> 
	<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
	<button type="submit" class="btn btn-primary">确认</button>
</div>
</form>