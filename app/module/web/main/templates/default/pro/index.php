<script type="text/javascript">
var cart = function (id, n)
{
	$.getJSON('/web/main_pro_ajax/add_cart?id=' + id+'&n=' + n, function (json){
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
				<th width="100">编号/名称</th>
				<th width="120">所属/类型</th>
				<th width="100">伏特/容量</th>
				<th width="100">现有/库存</th>	
				<th width="150">价格</th>				
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
							<td scope='row'>
							$val[pro_code]<br/>
							$val[pro_name]
							</td>
							<td>$val[category_pname]<br/>
							".$pt[$val['pro_type']]."
							</td>
							<td>$val[pro_unit]V/$val[pro_size]Ah
							</td>
							<td>$val[pro_bale]/".intval($val[pro_depot])."</td>
							<td class='text-danger'>
							$val[pro_money]元<br/>
							<small class='text-muted'><del>$val[pro_price]元×$val[pro_discount]折</del></small>
							</td>
							<td>
							<a href='javascript:cart($val[id], $val[pro_bale]);' class='btn btn-danger iconfont'>&#xe645;&nbsp;订购</a>
							<a href='/web/main_pro_view/vproduct?id=$val[id]' class='btn btn-success iconfont my_alert'>&#xe61e;&nbsp;详细</a>
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
			<div class="alert_div mt15">
				 <div id="alert"></div>
			</div>
			<table class="table table-hover mt15">
			<thead>
			<tr>
				<th width="100">编号</th>
				<th width="120">名称</th>
				<th width="80">伏特</th>							
				<th width="80">容量</th>	
				<th width="120">数量</th>
				<th width="100">价格</th>
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
							<td>$val[pro_unit]V</td>					
							<td>$val[pro_size]Ah</td>
							<td>".$cart[$val['id']]."×$val[pro_money]元</td>
							<td>$money元</td>
							<td>
							<a href='/web/main_pro_get/add_cart?id=$val[id]&n=$val[pro_bale]' class='btn btn-success iconfont'>&#xe866;</a>
							<a href='/web/main_pro_get/reduce_cart?id=$val[id]&n=$val[pro_bale]' class='btn btn-success iconfont'>&#xe65d;</a>
							<a href='/web/main_pro_get/del_cart?id=$val[id]' class='btn btn-danger iconfont my_confirm' title='确认该项移除吗？'>&#xe64e;&nbsp;删除</a>
							</tr>";
					}
				}?>
			</tbody>
			<tfoot>
			<tr>
				<th width="200" colspan="3" style="font-size: 28px; color:red;">订单金额：<?=$count?>元</th>
				<th colspan="5" style="text-align: right;">
					<a href="/web/main_pro_get/del_cart" class="btn btn-lg my_confirm" title='确认移除所有吗？'>清空</a>
			    	<button href="/web/main_pro_view/order_mark" type="button" class="btn btn-danger btn-lg my_alert">确认下单</button>
				</th>
			</tr>
			</tfoot>			
			</table>								
		</div>
	</div>
</div>