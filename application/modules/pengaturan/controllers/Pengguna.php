<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengguna extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->form_validation->CI =& $this;
	}
	
	public function index()
	{
		$this->load->view('templates/app_tpl', array (
			'content' => 'pengguna_index',
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
			FROM pengguna AS a
			JOIN pengguna_grup AS b ON
				a.id_pengguna_grup = b.id
				AND b.row_status = 1
			WHERE
				a.row_status = 1
				AND (
					a.nama LIKE ?
					OR a.email LIKE ?
					OR a.username LIKE ?
					OR b.nama LIKE ?
				)
		";
		
		$data_sql = "
			SELECT
				a.*
				, b.nama AS grup_pengguna
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
			'content' => 'pengguna_form',
			'action_url' => site_url("pengaturan/pengguna/{$aksi}"),
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
			->from('pengguna')
			->where('row_status', 1)
			->where('id', $id)
			->get();
		
		if ($src->num_rows() == 0) {
			show_404();
		}
		
		$this->_form('update', $src->row_array());
	}
	
	public function check_email($email)
	{
		$id = $this->input->post('id');
		
		if (is_exists_email($email, $id)) {
			$this->form_validation->set_message('check_email', '<b>%s</b> sudah terdaftar. Mohon gunakan alamat email lain.');
			return false;
		}
		else {
			return true;
		}
	}
	
	public function check_username($username)
	{
		$id = $this->input->post('id');
		
		if (is_exists_username($username, $id)) {
			$this->form_validation->set_message('check_username', '<b>%s</b> sudah digunakan. Mohon ganti dengan username lain.');
			return false;
		}
		else {
			return true;
		}
	}
	
	private function _form_data()
	{
		$rules = array (
			array (
				'field' => 'nama',
				'label' => 'Nama Pelanggan',
				'rules' => 'required',
			),
			array (
				'field' => 'email',
				'label' => 'Email',
				'rules' => 'required|valid_email|callback_check_email',
			),
			array (
				'field' => 'username',
				'label' => 'Username',
				'rules' => 'callback_check_username',
			),
			array (
				'field' => 'id_pengguna_grup',
				'label' => 'Grup Pengguna',
				'rules' => 'required',
			),
		);
		
		if ($this->input->post('id') == '') {
			$rules[] = array (
				'field' => 'password',
				'label' => 'Password',
				'rules' => 'required',
			);
		}
		
		$this->form_validation->set_rules($rules);
		
		if ($this->form_validation->run()) {
			
			$key = array('nama', 'email', 'username', 'password', 'id_pengguna_grup');
			$data = post_data($key);
			
			if ($data['username'] == '') {
				unset($data['username']);
			}
			
			if ($data['password'] != '') {
				$data['password'] = hash_password($data['password']);
			}
			else {
				unset($data['password']);
			}
			
			return $data;
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
			$data['created_by'] = user_session('id');
			$this->db->insert('pengguna', $data);
			$this->session->set_flashdata('post_status', 'inserted');
			redirect(site_url('pengaturan/pengguna'));
		}
		else {
			redirect(site_url('pengaturan/pengguna/tambah'));
		}	
	}
	
	public function update()
	{
		$data = $this->_form_data();
		$id = $this->input->post('id');
		
		if ($data != null) {
			$data['updated_by'] = user_session('id');
			$this->db->update('pengguna', $data, array('id' => $id));
			$this->session->set_flashdata('post_status', 'updated');
			redirect(site_url('pengaturan/pengguna'));
		}
		else {
			redirect(site_url('pengaturan/pengguna/ubah/'.$id));
		}
	}
}
