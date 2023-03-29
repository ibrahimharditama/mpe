<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produk extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->form_validation->CI =& $this;
	}
	
	public function index()
	{
		$this->load->view('templates/app_tpl', array (
			'content' => 'produk_index',
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
		$tipe = $this->input->post('tipe');
		$bindings = array("%{$keyword}%", "%{$keyword}%");
		$where = "";
		if($tipe == "inventory"){
			$where = " AND d.nama = 'Inventory'";
		}
		elseif($tipe == "jasa"){
			$where = " AND d.nama = 'Jasa'";
		}
		$base_sql = "
			FROM ref_produk AS a
			LEFT JOIN pengguna AS b ON a.created_by = b.id
			LEFT JOIN pengguna AS c ON a.updated_by = c.id
			JOIN ref_lookup AS d ON a.id_tipe = d.id
			JOIN ref_lookup AS e ON a.id_satuan = e.id
			JOIN ref_lookup AS f ON a.id_jenis = f.id
			JOIN ref_lookup AS g ON a.id_merek = g.id
			LEFT JOIN (SELECT a.id_produk,sum(a.qty) as qty FROM jstok a GROUP BY a.id_produk) h ON a.id = h.id_produk
			WHERE
				a.row_status = 1
				AND a.id_tipe != 22
				AND (
					a.kode LIKE ?
					OR a.nama LIKE ?
				)
				$where
		";
		
		$data_sql = "
			SELECT
				a.*
				, UPPER(b.username) AS yg_buat
				, UPPER(c.username) AS yg_ubah
				, d.nama AS tipe
				, e.nama AS satuan
				, f.nama AS jenis
				, g.nama AS merek
				,(CASE
					WHEN h.qty IS NULL THEN 0
					ELSE h.qty
				END)AS stok
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
		
		foreach ($aaData as $i => $d) {
			$aaData[$i]['harga_beli'] = rupiah($d['harga_beli']);
			$aaData[$i]['harga_jual'] = rupiah($d['harga_jual']);
		}
		
		$response = array (
			'draw' => intval($draw),
			'iTotalRecords' => $src->num_rows(),
			'iTotalDisplayRecords' => $total_records,
			'aaData' => $aaData,
		);
		
		echo json_encode($response);
	}
	
	private function _form($aksi = 'insert', $data = null)
	{
		if ($this->session->flashdata('data')) {
			$data = $this->session->flashdata('data');
		}
		
		$this->load->view('templates/app_tpl', array (
			'content' => 'produk_form',
			'action_url' => site_url("master/produk/{$aksi}"),
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
			->from('ref_produk')
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
				'field' => 'kode',
				'label' => 'Kode',
				'rules' => 'required|callback_check_kode',
			),
			array (
				'field' => 'id_tipe',
				'label' => 'Tipe',
				'rules' => 'required',
			),
			array (
				'field' => 'nama',
				'label' => 'Nama Item',
				'rules' => 'required',
			),
			array (
				'field' => 'id_jenis',
				'label' => 'Jenis',
				'rules' => 'required',
			),
			array (
				'field' => 'id_satuan',
				'label' => 'Satuan',
				'rules' => 'required',
			),
			array (
				'field' => 'id_merek',
				'label' => 'Merek',
				'rules' => 'required',
			),
			array (
				'field' => 'harga_beli',
				'label' => 'Harga Beli',
				'rules' => 'required',
			),
			array (
				'field' => 'harga_jual',
				'label' => 'Harga Jual',
				'rules' => 'required',
			),
		);
		
		$this->form_validation->set_rules($rules);
		
		if ($this->form_validation->run()) {
			
			$key = array('kode','id_tipe', 'nama', 'id_jenis', 'id_satuan', 'id_merek', 'harga_beli|number', 'harga_jual|number', 'keterangan');
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
	
	function check_kode($kode) {        
        if($this->input->post('kode'))
            $kode = $this->input->post('kode');
        else
            $kode = '';
		$result = $this->db->from('ref_produk')->where('kode', $kode)->get()->num_rows();
        if($result == 0)
            $response = true;
        else {
            $this->form_validation->set_message('check_kode', 'Kode sudah ada');
            $response = false;
        }
        return $response;
    }

	public function insert()
	{
		$data = $this->_form_data();
		
		if ($data != null) {
			$data['created_by'] = user_session('id');
			$this->db->insert('ref_produk', $data);
			$this->session->set_flashdata('post_status', 'inserted');
			redirect(site_url('master/produk'));
		}
		else {
			redirect(site_url('master/produk/tambah'));
		}	
	}
	
	public function update()
	{
		$data = $this->_form_data();
		$id = $this->input->post('id');
		
		if ($data != null) {
			$data['updated_by'] = user_session('id');
			$this->db->update('ref_produk', $data, array('id' => $id));
			$this->session->set_flashdata('post_status', 'updated');
			redirect(site_url('master/produk'));
		}
		else {
			redirect(site_url('master/produk/ubah/'.$id));
		}
	}

	public function hapus($id)
	{
		if ( ! $this->agent->referrer()) {
			show_404();
		}

		$src = $this->db
			->from('ref_produk')
			->where('row_status', 1)
			->where('id', $id)
			->get();

		if ($src->num_rows() == 0) {
			show_404();
		}

		$this->load->helper('delete');
		
		if (check_fk('ref_produk', $id)) {
			
			db_delete('ref_produk', ['id' => $id]);
			
			$status = 'ok';
		}
		else {
			$status = 'err';
		}

		$this->session->set_flashdata('delete_status', $status);
		redirect(site_url('master/produk'));
	}
}
