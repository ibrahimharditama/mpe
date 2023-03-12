<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengguna_grup extends MX_Controller {

	public function index()
	{
		$this->load->view('templates/app_tpl', array (
			'content' => 'pengguna_grup_index',
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
			FROM pengguna_grup AS a
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
	
	private function _menu($id_pengguna_grup)
	{
		$sql = "
			SELECT
				a.id AS id_menu
				, a.id_induk
				, a.teks
				, a.actions
				, b.permissions
			FROM (
				SELECT *
				FROM menu
				WHERE row_status = 1
			) AS a
			LEFT JOIN (
				SELECT *
				FROM pengguna_grup_menu
				WHERE
					row_status = 1
					AND id_pengguna_grup = ?
			) AS b
				ON a.id = b.id_menu
			ORDER BY a.urutan
		";
		$src = $this->db->query($sql, array($id_pengguna_grup));
		
		$menu = array();
		
		foreach ($src->result() as $row) {
			
			if ($row->id_induk == null) {
				$menu[$row->id_menu] = array (
					'teks' => $row->teks,
					'actions' => array_flip(explode(',', $row->actions)),
					'permissions' => array_flip(explode(',', $row->permissions)),
					'submenu' => array(),
				);
			}
			else {
				$menu[$row->id_induk]['submenu'][] = array (
					'id_menu' => $row->id_menu,
					'teks' => $row->teks,
					'actions' => array_flip(explode(',', $row->actions)),
					'permissions' => array_flip(explode(',', $row->permissions)),
				);
			}
		}
		
		return $menu;
	}
	
	private function _form($aksi = 'insert', $data = null)
	{
		if ($this->session->flashdata('data')) {
			$data = $this->session->flashdata('data');
		}
		
		$this->load->view('templates/app_tpl', array (
			'content' => 'pengguna_grup_form',
			'action_url' => site_url("pengaturan/pengguna-grup/{$aksi}"),
			'data' => $data,
			'menu' => $this->_menu($data == null ? 0 : $data['id'])
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
			->from('pengguna_grup')
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
				'label' => 'Nama Grup Pengguna',
				'rules' => 'required',
			),
		);
		
		$this->form_validation->set_rules($rules);
		
		if ($this->form_validation->run()) {
			
			$key = array('nama', 'urutan');
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
	
	private function _insert_menu($id_pengguna_grup, $permissions)
	{
		$data = array();
		
		if ( ! $permissions) return;
		
		foreach ($permissions as $id_menu => $p) {
			$data[] = array (
				'id_pengguna_grup' => $id_pengguna_grup,
				'id_menu' => $id_menu,
				'permissions' => implode(',', $p),
			);
		}
		
		$this->db->delete('pengguna_grup_menu', array('id_pengguna_grup' => $id_pengguna_grup));
		
		if (count($data) > 0) {
			$this->db->insert_batch('pengguna_grup_menu', $data);
		}
	}
	
	public function insert()
	{
		$data = $this->_form_data();
		
		if ($data != null) {
			update_urutan('pengguna_grup', $data['urutan']);
			
			$data['created_by'] = user_session('id');
			$this->db->insert('pengguna_grup', $data);
			$id_pengguna_grup = $this->db->insert_id();
			
			$this->_insert_menu($id_pengguna_grup, $this->input->post('permissions'));
			
			$this->session->set_flashdata('post_status', 'inserted');
			redirect(site_url('pengaturan/pengguna-grup'));
		}
		else {
			redirect(site_url('pengaturan/pengguna-grup/tambah'));
		}	
	}
	
	public function update()
	{
		$data = $this->_form_data();
		$id = $this->input->post('id');
		
		if ($data != null) {
			update_urutan('pengguna_grup', $data['urutan'], $this->input->post('urutan_sekarang'), 'swap');
			
			$data['updated_by'] = user_session('id');
			$this->db->update('pengguna_grup', $data, array('id' => $id));
			
			$this->_insert_menu($id, $this->input->post('permissions'));
			
			$this->session->set_flashdata('post_status', 'updated');
			redirect(site_url('pengaturan/pengguna-grup'));
		}
		else {
			redirect(site_url('pengaturan/pengguna-grup/ubah/'.$id));
		}
	}
}
