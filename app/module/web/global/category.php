<?php
function option_class()
{
	$parent_class = ProCategoryModel::fetchAll(array('pid' => 0));
	$chlid_class = ProCategoryModel::fetchAll(array('pid <> ?', 0));	
	if($parent_class)
	{
		foreach ($parent_class as $val) 
		{
			echo "<option value='$val[id]'>├─{$val[c_name]}</option>";
			for_option_class($chlid_class, $val['id']);	
		}			
	}

}

function for_option_class($chlid_class, $pid)
{
	if($chlid_class)
	{
		foreach ($chlid_class as $val) 
		{
			if($val['pid'] == $pid)
			{
				$str = str_repeat('└─', $val['depth']);
				echo "<option value='$val[id]'>{$str}{$val[c_name]}</option>";
				for_option_class($chlid_class, $val['id']);			
			}
		}			
	}
}