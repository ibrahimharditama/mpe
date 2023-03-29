<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Supplier extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->form_validation->CI =& $this;
	}
	
	public function index()
	{
		$this->load->view('templates/app_tpl', array (
			'content' => 'supplier_index',
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
		
		$bindings = array("%{$keyword}%", "%{$keyword}%", "%{$keyword}%", "%{$keyword}%");
		
		$base_sql = "
			FROM supplier AS a
			LEFT JOIN pengguna AS b ON a.created_by = b.id
			LEFT JOIN pengguna AS c ON a.updated_by = c.id
			WHERE
				a.row_status = 1
				AND (
					a.kode LIKE ?
					OR a.nama LIKE ?
					OR a.alamat LIKE ?
					OR a.email LIKE ?
				)
		";
		
		$data_sql = "
			SELECT
				a.*
				, UPPER(b.username) AS yg_buat
				, UPPER(c.username) AS yg_ubah
				, REPLACE(alamat, '\n', ', ') AS alamat_tidy
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
			'content' => 'supplier_form',
			'action_url' => site_url("master/supplier/{$aksi}"),
			'data' => $data,
		));
	}
	
	public function tambah()
	{
		$this->_form();
	}
	
	public function ubah($id)
	{
		if ( ! $this->agent->referrer()) {
			show_404();
		}
		
		$src = $this->db
			->from('supplier')
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
				'label' => 'Nama Supplier',
				'rules' => 'required',
			),
		);
		
		$this->form_validation->set_rules($rules);
		
		if ($this->form_validation->run()) {
			
			$key = array('nama', 'alamat', 'kota', 'provinsi', 'kode_pos', 'no_telp', 'no_hp', 'email', 'bank', 'no_rekening', 'pemilik_rekening', 'npwp', 'keterangan');
			return post_data($key);
		}
		else {
			
			foreach ($rules as $r) {
				$errors[$r['field']] = form_error($r['field']);
			}
			
			$this->session->set_flashdata('post_status', 'err');
			$this->session->set_flashdata('errors', $errors);
			$this->session->set_flashdata('data', $this->input->post());
			
			return null;
		}
	}
	
	public function insert()
	{
		$data = $this->_form_data();
		
		if ($data != null) {
			$data['kode'] = new_number('supplier');
			$data['created_by'] = user_session('id');
			$this->db->insert('supplier', $data);
			$this->session->set_flashdata('post_status', 'inserted');
			redirect(site_url('master/supplier'));
		}
		else {
			redirect(site_url('master/supplier/tambah'));
		}	
	}
	
	public function update()
	{
		$data = $this->_form_data();
		$id = $this->input->post('id');
		
		if ($data != null) {
			$data['updated_by'] = user_session('id');
			$this->db->update('supplier', $data, array('id' => $id));
			$this->session->set_flashdata('post_status', 'updated');
			redirect(site_url('master/supplier'));
		}
		else {
			redirect(site_url('master/supplier/ubah/'.$id));
		}
	}

	public function hapus($id)
	{
		if ( ! $this->agent->referrer()) {
			show_404();
		}

		$src = $this->db
			->from('supplier')
			->where('row_status', 1)
			->where('id', $id)
			->get();

		if ($src->num_rows() == 0) {
			show_404();
		}

		$this->load->helper('delete');
		
		if (check_fk('supplier', $id)) {
			
			db_delete('supplier', ['id' => $id]);
			
			$status = 'ok';
		}
		else {
			$status = 'err';
		}

		$this->session->set_flashdata('delete_status', $status);
		redirect(site_url('master/supplier'));
	}
}
