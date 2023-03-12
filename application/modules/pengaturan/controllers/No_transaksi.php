<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class No_transaksi extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->form_validation->CI =& $this;
	}
	
	public function index()
	{
		$this->load->view('templates/app_tpl', array (
			'content' => 'no_transaksi_index',
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
		
		$bindings = array("%{$keyword}%");
		
		$base_sql = "
			FROM no_transaksi AS a
			WHERE
				a.row_status = 1
				AND a.nama LIKE ?
		";
		
		$data_sql = "
			SELECT
				a.*
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
		
		$response = array (
			'draw' => intval($draw),
			'iTotalRecords' => $src->num_rows(),
			'iTotalDisplayRecords' => $total_records,
			'aaData' => $src->result_array(),
		);
		
		echo json_encode($response);
	}
	
	private function _form($aksi = 'insert', $data = null)
	{
		if ($this->session->flashdata('data')) {
			$data = $this->session->flashdata('data');
		}
		
		$this->load->view('templates/app_tpl', array (
			'content' => 'no_transaksi_form',
			'action_url' => site_url("pengaturan/no-transaksi/{$aksi}"),
			'data' => $data,
		));
	}
	
	public function ubah($id)
	{
		if ( ! $this->agent->referrer()) {
			show_404();
		}
		
		$src = $this->db
			->from('no_transaksi')
			->where('row_status', 1)
			->where('id', $id)
			->get();
		
		if ($src->num_rows() == 0) {
			show_404();
		}
		
		$this->_form('update', $src->row_array());
	}
	
	private function _form_data()
	{
		$rules = array (
			array (
				'field' => 'nama',
				'label' => 'Nama Transaksi',
				'rules' => 'required',
			),
			array (
				'field' => 'format',
				'label' => 'Format No. Transaksi',
				'rules' => 'required',
			),
			array (
				'field' => 'digit_serial',
				'label' => 'Jumlah Digit Serial',
				'rules' => 'required',
			),
			array (
				'field' => 'serial_berikutnya',
				'label' => 'Serial Berikutnya',
				'rules' => 'required',
			),
		);
		
		$this->form_validation->set_rules($rules);
		
		if ($this->form_validation->run()) {
			
			$key = array('format', 'digit_serial', 'reset_serial', 'serial_berikutnya');
			return post_data($key);
		}
		else {
			
			foreach ($rules as $r) {
				$errors[$r['field']] = form_error($r['field']);
			}
			
			$periode_arr = explode('-', $this->input->post('periode'));
			
			$data = $this->input->post();
			$data['tahun_sekarang'] = $periode_arr[0];
			$data['bulan_sekarang'] = $periode_arr[1];
			
			$this->session->set_flashdata('post_status', 'err');
			$this->session->set_flashdata('errors', $errors);
			$this->session->set_flashdata('data', $data);
			
			return null;
		}
	}
	
	public function update()
	{
		$data = $this->_form_data();
		$id = $this->input->post('id');
		
		if ($data != null) {
			$data['updated_by'] = user_session('id');
			$this->db->update('no_transaksi', $data, array('id' => $id));
			$this->session->set_flashdata('post_status', 'updated');
			redirect(site_url('pengaturan/no-transaksi/'));
		}
		else {
			redirect(site_url('pengaturan/no-transaksi/ubah/'.$id));
		}
	}
}
