<?php

function check_fk($table, $id)
{
	$ci =& get_instance();
	
	$fk = $ci->config->item('fk');
	
	$i = 0;
	
	$sub_sql = '';
	
	foreach ($fk[$table] as $ref) {
		$ref_arr = explode('.', $ref);
		$ref_table = $ref_arr[0];
		
		$sub_sql .= $i == 0 ? '' : ' UNION ';
		$sub_sql .= "SELECT '{$ref}' AS ref, COUNT({$ref}) AS num_rows FROM {$ref_table} WHERE row_status = 1 AND {$ref} = '{$id}'";
		
		$i++;
	}
	
	$sql = "SELECT SUM(num_rows) AS num_rows FROM ({$sub_sql}) AS t";
	$num_rows = $ci->db->query($sql)->row('num_rows');
	
	return $num_rows == 0;
}
