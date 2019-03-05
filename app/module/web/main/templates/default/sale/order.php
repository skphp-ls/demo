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
					<th width="250">开单日期</th>
					<th width="120">客户姓名</th>
					<th width="200">开单人</th>
					<th width="120">订单数量</th>
					<th width="150">订单金额</th>
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
							echo "<td scope='row'>$val[add_time]</td>
								<td>$val[sname]</td>
								<td>$val[cashname]</td>
								<td>$val[og_num]</td>
								<td>$val[amonut]元</td>
								<td>
								<a href='/web/main_sale/order?id=$val[id]' class='btn btn-success iconfont'>&#xe61e;&nbsp;查看</a>
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
			<table class="table mt15">
				<tr>
					<td>备注：<?=$data['mark']?></td>
				</tr>													
			</table>											
		</div>
	</div>
</div>