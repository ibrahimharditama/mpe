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
	
	private function _form($aksi = 'insert', $data = null, $asset = null)
	{
		if ($this->session->flashdata('data')) {
			$data = $this->session->flashdata('data');
		}
		
		$this->load->view('templates/app_tpl', array (
			'content' => 'pengguna_form',
			'action_url' => site_url("pengaturan/pengguna/{$aksi}"),
			'data' => $data,
			'asset' => $asset,
			'aksi' => $aksi,
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

		$src_asset = $this->db->query("SELECT a.*, IFNULL(x.tgl_maintenance, '-') AS tgl_maintenance, x.total_record
									FROM asset a 
									LEFT JOIN (
											SELECT id_asset, MAX(tgl_maintenance) AS tgl_maintenance, COUNT(id) AS total_record 
											FROM asset_maintenance 
											WHERE row_status = 1
											GROUP BY id_asset
										) x ON x.id_asset = a.id
									WHERE a.id_pegawai = $id
										AND a.row_status = 1 
									ORDER BY a.id");
		
		$this->_form('update', $src->row_array(), $src_asset->result_array());
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
				'rules' => 'required|callback_check_username',
			),
			array (
				'field' => 'id_pengguna_grup',
				'label' => 'Grup Pengguna',
				'rules' => 'required',
			),
			array (
				'field' => 'id_jabatan',
				'label' => 'Jabatan',
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
			
			$key = array('nama', 'email', 'username', 'password', 'id_pengguna_grup', 'id_jabatan');
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
			
			return showRestResponse($data);
		}
		else {
			
			foreach ($rules as $r) {
				$errors[$r['field']] = form_error($r['field']);
			}
			
			return showRestResponse($errors, 400, 'Validation Error');;
		}
	}
	
	public function insert()
	{
		$response = $this->_form_data();
		$response['url'] = '';
		
		if ($response['code'] == 200) {
			$data = $response['data'];
			$id = db_insert('pengguna', $data);
			$response['url'] = base_url().'pengaturan/pengguna/ubah/'.$id;
			$this->session->set_flashdata('post_status', 'ok');
			
		}

		echo json_encode($response);
	}
	
	public function update()
	{
		$response = $this->_form_data();
		$response['url'] = '';
		
		if ($response['code'] == 200) {
			$data = $response['data'];
			$id = $this->input->post('id');
			db_update('pengguna', $data, array('id' => $id));
			$response['url'] = base_url().'pengaturan/pengguna/ubah/'.$id;
			$this->session->set_flashdata('post_status', 'ok');
			
		}

		echo json_encode($response);
	}

	public function delete($id)
	{
		if ( ! $this->agent->referrer()) {
			show_404();
		}

		db_delete('pengguna', ['id' => $id]);
		redirect($this->agent->referrer());
	}

	private function _form_data_asset()
	{
		$rules = array (
			array (
				'field' => 'nama',
				'label' => 'Nama Unit',
				'rules' => 'required',
			),
			array (
				'field' => 'model',
				'label' => 'Model',
				'rules' => 'required',
			),
			array (
				'field' => 'tgl_pembelian',
				'label' => 'Tgl. Perolehan',
				'rules' => 'required',
			),
			array (
				'field' => 'waktu_maintenance',
				'label' => 'Periode Maintenance',
				'rules' => 'required|is_natural_no_zero',
			),
			
		);

		if ($this->input->post('total_record') <= 1) {
			$rules[] = array (
				'field' => 'tgl_maintenance',
				'label' => 'Tgl. Terakhir Perawatan',
				'rules' => 'required',
			);
		}
		
		$this->form_validation->set_rules($rules);
		
		if ($this->form_validation->run()) {
			
			$key = array('nama', 'model', 'tgl_pembelian', 'waktu_maintenance', 'periode_maintenance', 'id_pegawai');
			$data = post_data($key);
			
			
			return showRestResponse($data);
		}
		else {
			
			foreach ($rules as $r) {
				$errors[$r['field']] = form_error($r['field']);
			}
			
			return showRestResponse($errors, 400, 'Validation Error');;
		}
	}

	public function insert_asset()
	{
		$response = $this->_form_data_asset();
		$response['url'] = '';
		
		if ($response['code'] == 200) {
			$data = $response['data'];
			$tgl_maintenance = $this->input->post('tgl_maintenance');			
			$id = db_insert('asset', $data);

			$data_maintenance = array(
				'id_asset' => $id,
				'tgl_maintenance' => $tgl_maintenance,
			); 
			db_insert('asset_maintenance', $data_maintenance);
			

			$response['url'] = base_url().'pengaturan/pengguna/ubah/'.$data['id_pegawai'];
			
		}

		echo json_encode($response);
	}

	public function update_asset()
	{
		$response = $this->_form_data_asset();
		$response['url'] = '';
		
		if ($response['code'] == 200) {

			$data = $response['data'];
			$id_asset = $this->input->post('id_asset');
			$tgl_maintenance = $this->input->post('tgl_maintenance');
			db_update('asset', $data, ['id' => $id_asset]);

			if($this->input->post('total_record') <= 1) {
				
				$data_maintenance = array(
					'tgl_maintenance' => $tgl_maintenance,
				); 

				db_update('asset_maintenance', $data_maintenance, ['id_asset' => $id_asset]);
			}

			$response['url'] = base_url().'pengaturan/pengguna/ubah/'.$data['id_pegawai'];
			
		}

		echo json_encode($response);
	}

	public function delete_asset($id)
	{
		if ( ! $this->agent->referrer()) {
			show_404();
		}

		db_delete('asset', ['id' => $id]);
		db_delete('asset_maintenance', ['id_asset' => $id]);
		redirect($this->agent->referrer());
	}
}
