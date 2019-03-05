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
					<select class="form-control input-mlg" name="t">
						<option value="1">按姓名</option>
						<option value="2">按手机</option>
					</select>
				</div>							
			    <div class="input-group">
			      <input type="text" class="form-control input-mlg" name="n" placeholder="输入对应内容">
			      <div class="input-group-addon iconfont btn-primary search">&#xe64d;<i>查找</i></div>
			    </div>
			    <div class="input-group">
			      <a class="btn btn-primary input-mlg my_alert" href="../main_view/add_user"><i>新增客户</i></a>
			    </div>			    
			</form>
			<table class="table table-hover mt15" style="margin-bottom: 0px;">
			<thead>
			<tr>
				<th width="120">客户姓名</th>
				<th width="200">客户手机</th>		
				<th width="200">录入时间</th>
				<th width="200">操作</th>
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
						echo "<td scope='row'>$val[truename]<small>(".AppConf::$SEX[$val[sex]].")</small></td>
							<td>$val[phone]</td>
							<td>$val[add_time]</td>
							<td>
							<a href='../main_view/upt_user?id=$val[id]' class='btn btn-info iconfont my_alert'>&#xe689;&nbsp;修改</a>
							<a href='../main_get/del_user?id=$val[id]' class='btn btn-danger iconfont my_confirm' title='确认删除该客户信息？'>&#xe64e;&nbsp;删除</a>
							<a href='../main/user?id=$val[id]' class='btn btn-success iconfont'>&#xe61e;&nbsp;查看</a>
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
			<table class="table mt15">
				<tr>
					<td>地址：<?=$data['address']?></td>
				</tr>					
				<tr>
					<td>备注：<?=$data['mark']?></td>
				</tr>													
			</table>	

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
					if($pro_list)
					{
						foreach ($pro_list as $val) {
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
		</div>
	</div>
</div>