<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Absensi extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->form_validation->CI =& $this;
	}
	
	public function index()
	{
		$this->load->view('templates/app_tpl', array (
			'content' => 'absensi_index',
		));
	}
	
	public function datatable()
	{
		$draw = $this->input->post('draw');
		$offset = $this->input->post('start');
		$num_rows = $this->input->post('length');
		$order_index = $_POST['order'][0]['column'];
		$order_by = $_POST['columns'][$order_index]['data'];
		$order_direction = $_POST['order'][0]['dir'];
		$keyword = $_POST['search']['value'];
		
		$bindings = array("%{$keyword}%", "%{$keyword}%", "%{$keyword}%");
		
		$base_sql = "
			FROM absensi AS a
			LEFT JOIN pengguna AS b ON a.created_by = b.id
			LEFT JOIN pengguna AS c ON a.updated_by = c.id
			LEFT JOIN pengguna AS d ON a.approved_by = d.id
			LEFT JOIN pengguna AS x ON a.id_pengguna = x.id
			WHERE
				a.row_status = 1
				AND (
					x.nama LIKE ?
					OR x.username LIKE ?
					OR x.email LIKE ?
				)
		";
		
		$data_sql = "
			SELECT
				a.*
				, UPPER(b.username) AS yg_buat
				, UPPER(c.username) AS yg_ubah
				, UPPER(d.username) AS yg_approve
				, UPPER(x.username) AS pegawai
				, ROW_NUMBER() OVER (
					ORDER BY {$order_by} {$order_direction}
				  ) AS nomor
			{$base_sql}
			ORDER BY
				{$order_by} {$order_direction}
			LIMIT {$offset}, {$num_rows}
		";
		$src = $this->db->query($data_sql, $bindings);
		
		$count_sql = "
			SELECT COUNT(*) AS total
			{$base_sql}
		";
		$total_records = $this->db->query($count_sql, $bindings)->row('total');
		
		$aaData = $src->result_array();
		
		$response = array (
			'draw' => intval($draw),
			'iTotalRecords' => $src->num_rows(),
			'iTotalDisplayRecords' => $total_records,
			'aaData' => $aaData,
		);
		
		echo json_encode($response);
	}

	public function approve($id)
	{
		$input = $this->input->post();
		$input["id"] = $id;

		// update
		$this->db
				->where(
					"id", $input["id"]
				)
				->update('absensi',[
					"status" 	=> 1,
					"approved_at" 	=> date('Y-m-d H:i:s'),
					"approved_by"	=> user_session('id')
				]);

		// redirect
		redirect(site_url('absensi'));
	}
}
