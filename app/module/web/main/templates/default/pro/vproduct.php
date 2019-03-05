<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title">产品详细</h4>
</div>
<div class="modal-body">
	<table class="table table-bordered" style="margin-bottom:0px;">
		<tr>
			<td align="right" width="100">编号：</td><td><?=$data['pro_code']?></td>
			<td align="right" width="100">名称：</td><td><?=$data['pro_name']?></td>
		</tr>
		<tr>	
			<td align="right">所属：</td><td><?=$data['category_pname']?></td>
			<td align="right">分类：</td><td><?=$data['category_name']?></td>
		</tr>
		<tr>
			<td align="right">价格：</td><td><?=$data['pro_price']?></td>
			<td align="right">折扣：</td><td><?=$data['pro_discount']?></td>
		</tr>
		<tr>
			<td align="right">类型：</td><td><?=AppConf::$PT[$data['pro_type']]?></td>
			<td align="right">存量：</td><td><?=$data['pro_bale']?></td>
		</tr>	
		<tr>
			<td align="right">伏特：</td><td><?=$data['pro_unit']?>V</td>
			<td align="right">容量：</td><td><?=$data['pro_size']?>Ah</td>
		</tr>
		<tr>
			<td align="right">规格：</td><td colspan="3"><?=$data['pro_norms']?></td>
		</tr>	
		<tr>
			<td align="right">内容：</td><td colspan="3"><?=$data['pro_content']?></td>
		</tr>	
		<tr>
			<td align="right">入库时间：</td><td colspan="3"><?=$data['add_time']?></td>
		</tr>					
	</table>
</div>
</form>