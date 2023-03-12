<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends MX_Controller {

	public function index()
	{
		$this->load->view('templates/app_tpl', array (
			'content' => 'menu_index',
		));
	}
	
	public function datatable()
	{
		$draw = $this->input->post('draw');
		$keyword = $_POST['search']['value'];
		
		$bindings = array("%{$keyword}%");
		
		$base_sql = "
			FROM menu AS a
			WHERE
				a.row_status = 1
				AND (
					a.teks LIKE ?
				)
		";
		
		$data_sql = "
			SELECT
				a.*
				, ROW_NUMBER() OVER (
					ORDER BY a.urutan
				  ) AS nomor
			{$base_sql}
			ORDER BY a.urutan
		";
		$src = $this->db->query($data_sql, $bindings);
		
		$count_sql = "
			SELECT COUNT(*) AS total
			{$base_sql}
		";
		$total_records = $this->db->query($count_sql, $bindings)->row('total');
		
		$response = array (
			'draw' => intval($draw),
			'iTotalRecords' => $src->num_rows(),
			'iTotalDisplayRecords' => $total_records,
			'aaData' => $src->result_array(),
		);
		
		echo json_encode($response);
	}
}
