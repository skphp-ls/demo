<script type="text/javascript">
$(function (){
	$('.search').click(function (){
		$('.form1').submit();
	});
	$('[name=n]').val('<?=$this->page_params['n']?>');
});
</script>
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-6">
			<form class="form-inline mt15 form1">			
			    <div class="input-group">
			      <input type="text" class="form-control input-mlg" name="n" placeholder="输入订单编号">
			      <div class="input-group-addon iconfont btn-primary search">&#xe64d;<i>查找</i></div>
			    </div>
			</form>			
			<table class="table table-hover mt15" style="margin-bottom: 0px;">
			<thead>
			<tr>
				<th width="250">订单编号</th>
				<th width="120">下单人</th>
				<th width="200">下单时间</th>
				<th width="120">订单件量</th>
				<th width="150">订单状态</th>
				<th width="150">操作</th>
			</tr>
			</thead>
			<tbody>
				<?php
				if($data_list)
				{
					foreach ($data_list as $val) {
						if($data['id'] == $val['id'])
						{
							echo "<tr class='success'>";
						}else{
							echo "<tr>";
						}
						echo "<td scope='row'>$val[orderno]</td>
							<td>$val[cashname]</td>
							<td>$val[add_time]</td>
							<td>$val[og_num]</td>
							<td>".AppConf::$ST[$val['status']]."</td>
							<td>
							<a href='/web/main_pro/order?id=$val[id]' class='btn btn-success iconfont'>&#xe61e;&nbsp;查看</a>
							</td>
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
			<table class="table mt15">
				<tr>
					<td>订单编号：<?=$data['orderno']?></td>
					<td>下单人：<?=$data['cashname']?></td>
					<td>订单状态：<?=AppConf::$ST[$val['status']]?></td>
				</tr>
				<tr>
					<td colspan="2">备注：<?=$data['mark']?></td>
					<td>下单时间：<?=$data['add_time']?></td>
				</tr>
				<tr>
					<td colspan="2">回执：<?=$data['status_mark']?></td>
					<td>回执时间：<?=($data['upt_time'] == 0 ? '未回执' : date('Y-m-d H:i:s', $data['upt_time']))?></td>
				</tr>	
			<?php
			if($data['status'] == AppConf::ORDER_STATUS_NOPAY)
			{				
				echo '<tr>
					<td colspan="2">凭证：<a class="btn btn-info my_alert" href="../main_pro_view/pay_mark?id='. $data['id']  .'">支付确认</a></td>
					<td class="text-danger">请进行线下支付，再提交支付证明核对</td></tr>';
			}else{
				echo '<tr>
					<td colspan="2">凭证：'.$data['pay_mark'].'</td>
					<td>支付时间：'. date('Y-m-d H:i:s', $data['pay_time']) .'</td></tr>
					<tr>
					<td colspan="2">收货：<a class="btn btn-info my_confirm" title="确认收货？" href="../main_pro_get/pay_status?id='. $data['id']  .'">收货确认</a></td>
					<td>发货时间：'. date('Y-m-d H:i:s', $data['deliver_time']) .'</td></tr>';
			}
			?>														
			</table>
			<table class="table table-hover mt15">
			<thead>
			<tr>
				<th width="120">编号</th>
				<th width="150">名称</th>
				<th width="150">所属</th>	
				<th width="100">类型</th>	
				<th width="100">伏特</th>							
				<th width="100">容量</th>	
				<th width="100">数量</th>
				<th>操作</th>
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
							<td>$val[pro_name]</td>
							<td>$val[category_pname]</td>
							<td>".$pt[$val['pro_type']]."</td>
							<td>$val[pro_unit]V</td>
							<td>$val[pro_size]Ah</td>
							<td>$val[og_num]</td>
							<td>
							<a href='/web/main_pro_view/vproduct?id=$val[pro_id]' class='btn btn-success iconfont my_alert'>&#xe61e;&nbsp;详细</a>
							</tr>";
					}
				}?>
			</tbody>	
			</table>								
		</div>
	</div>
</div>