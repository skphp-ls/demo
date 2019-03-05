<script type="text/javascript">
var cart = function (id, n)
{
	$.getJSON('/web/main_sale_ajax/add_cart?id=' + id+'&n=' + n, function (json){
		if(json.ret == false)
		{
			FormModal.alertFail('alert', json.msg);
		}else{
			redirect();
		}	
	});
}
$(function (){
	$('.search').click(function (){
		$('.form1').submit();
	});
	$('.category').change(function (){
		var c = $(this).val();
		redirect('?c=' + c);
	}).val(<?=$this->page_params['c']?>);
	
	$('[name=t]').val(<?=$this->page_params['t']?>);
	$('[name=n]').val('<?=$this->page_params['n']?>');


	$('.form2').form({
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
				//delay_redirect('/web/main_pro/order');
			}else{
				FormModal.alertFail('alert', json.msg);
			}
		}
	});	
});
</script>
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-6">
			<form class="form-inline mt15 form1">
				<div class="input-group">
					<select class="form-control input-mlg category">
						<option value="0">选择产品分类</option>
						<?php option_class();?>	
					</select>
				</div>
				<div class="input-group">
					<select class="form-control input-mlg" name="t">
						<option value="1">按名称</option>
						<option value="2">按编号</option>
					</select>
				</div>
				<div class="input-group">
			      <input type="text" class="form-control input-mlg" name="n" placeholder="输入对应内容">
			      <div class="input-group-addon iconfont btn-primary search">&#xe64d;<i>查找</i></div>
			    </div>
			</form>
			<table class="table table-hover mt15" style="margin-bottom: 0px;">
			<thead>
			<tr>
				<th width="100">编号</th>
				<th width="120">名称</th>
				<th width="220">品牌</th>
				<th width="100">伏特/容量</th>
				<th width="100">库存</th>				
				<th width="220">操作</th>
			</tr>
			</thead>
			<tbody>  
				<?php
				if($pro_list)
				{
					$pt = AppConf::$PT;
					foreach ($pro_list as $val) {
						echo "<tr>
							<td scope='row'>$val[pro_code]</td>
							<td scope='row'>$val[pro_name]</td>
							<td>$val[category_pname]/".$pt[$val['pro_type']]."
							</td>
							<td>$val[pro_unit]V/$val[pro_size]Ah
							</td>
							<td>".$val[pro_depot]."</td>
							<td>
							<a href='/web/main_pro_view/vproduct?id=$val[id]' class='btn btn-success iconfont my_alert'>&#xe61e;&nbsp;详细</a>
							<a href='javascript:cart($val[id], $val[pro_bale]);' class='btn btn-danger iconfont'>&#xe866;&nbsp;选择</a>
							</tr>";
					}
				}?>
			</tbody>	
			</table>
			<nav>
				<?=$this->getPagination()?>
			</nav>			
		</div>
		<div class="col-lg-6">
			<div class="mt15 userinfo bg-success">
				销售开单（请先选择开单的产品及数量，再填写客户信息后，完成确认开单。）
			</div>				
			<table class="table table-hover mt15">
			<thead>
			<tr>
				<th width="100">编号</th>
				<th width="120">名称</th>
				<th width="120">伏特/容量</th>
				<th width="120">数量</th>
				<th width="200">操作</th>
			</tr>
			</thead>
			<tbody>  
				<?php
				if($cart_list)
				{
					foreach ($cart_list as $val) {
						$money = $cart[$val['id']]*$val[pro_money];
						$count += $money;
						echo "<tr>
							<td scope='row'>$val[pro_code]</td>
							<td>$val[pro_name]</td>
							<td>$val[pro_unit]V/$val[pro_size]Ah</td>
							<td>".$cart[$val['id']]."</td>
							<td>
							<a href='/web/main_sale_get/add_cart?id=$val[id]&n=$val[pro_bale]' class='btn btn-success iconfont'>&#xe866;</a>
							<a href='/web/main_sale_get/reduce_cart?id=$val[id]&n=$val[pro_bale]' class='btn btn-success iconfont'>&#xe65d;</a>
							<a href='/web/main_sale_get/del_cart?id=$val[id]' class='btn btn-danger iconfont my_confirm' title='确认该项移除吗？'>&#xe64e;&nbsp;删除</a>
							</tr>";
					}
				}?>
			</tbody>			
			</table>
			<form class="form-horizontal form2" style="background: #fff;padding: 10px; border-radius: 6px;" method="POST">
			  <div class="form-group mt15">
			    <label for="inputEmail3" class="col-sm-2 control-label">选择客户</label>
			    <div class="col-sm-10">
				     <select multiple name="userid" class="form-control" style="font-size: 18px;">
				     <?php
				     foreach ($user_list as $key => $val) 
				     {
				     	echo "<option value='$val[id]'>$val[truename] (".AppConf::$SEX[$val['sex']].") $val[phone]</option>";
				     }
				     ?>
					</select>
			    </div>
			  </div>				
			  <div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label">订单金额</label>
			    <div class="col-sm-10">
			      <input type="text" name="amonut" class="form-control" value="0"/>
			    </div>
			  </div>
			  <div class="form-group">
			    <label for="inputPassword3" class="col-sm-2 control-label">订单备注</label>
			    <div class="col-sm-10">
			      <textarea name="mark" class="form-control" rows="3"></textarea>
			    </div>
			  </div>
			<div class="form-group alert_div">
				 <div class="col-sm-offset-2 col-sm-10">
					<div id="alert" class="w300"></div>
				</div>
			</div>
			  <div class="form-group">
			    <div class="col-sm-offset-2 col-sm-10">
			      <button type="submit" class="btn btn-primary">确认开单</button>
			    </div>
			  </div>
			</form>

		</div>
	</div>
</div>