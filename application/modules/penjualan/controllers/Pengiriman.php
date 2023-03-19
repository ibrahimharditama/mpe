<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengiriman extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->form_validation->CI =& $this;
	}
	
	public function index()
	{
		$this->load->view('templates/app_tpl', array (
			'content' => 'pengiriman_index',
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
			FROM pengiriman AS a
			LEFT JOIN pengguna AS b ON a.created_by = b.id
			LEFT JOIN pengguna AS c ON a.updated_by = c.id
			JOIN pelanggan AS d ON a.id_pelanggan = d.id
			WHERE
				a.row_status = 1
				AND (
					a.no_transaksi LIKE ?
					OR d.kode LIKE ?
					OR d.nama LIKE ?
				)
		";
		
		$data_sql = "
			SELECT
				a.*
				, UPPER(b.username) AS yg_buat
				, UPPER(c.username) AS yg_ubah
				, CONCAT(d.kode, ' &middot; ', d.nama) AS pelanggan
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
	
	private function _form($aksi = 'insert', $data = null)
	{
		if ($this->session->flashdata('data')) {
			$data = $this->session->flashdata('data');
		}
		
		$this->load->view('templates/app_tpl', array (
			'content' => 'pengiriman_form',
			'action_url' => site_url("penjualan/pengiriman/{$aksi}"),
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
			->from('pengiriman')
			->where('row_status', 1)
			->where('id', $id)
			->get();
		
		if ($src->num_rows() == 0) {
			show_404();
		}
		
		$this->_form('update', $src->row_array());
	}
	
	public function js_detail($id)
	{
		$src = $this->db
			->from('pengiriman_detail')
			->where('row_status', 1)
			->where('id_pengiriman', $id)
			->get();
		
		header('Content-Type: text/javascript');
		echo 'var details = '.json_encode($src->result()).';';
	}
	
	public function insert()
	{
		$list_produk = $this->input->post('produk');
		
		$detail = array();
		$qty_pesan = 0;
		
		foreach ($list_produk as $i => $produk) {
			
			if ($produk['qty'] > 0) {
				
				$qty = $produk['qty'];
				
				$detail[] = array (
					'id_produk' => $produk['id'],
					'uraian' => $produk['uraian'],
					'id_satuan' => $produk['id_satuan'],
					'satuan' => $produk['satuan'],
					'qty' => $qty,
				);
				
				$qty_pesan += $qty;
			}
		}

		// echo '<pre>'; print_r($detail);die(); // TODO debug
		
		if (count($detail) > 0) {
			$key = array('tgl', 'id_pelanggan','id_faktur','alamat', 'keterangan');
			$data = post_data($key);
			
			$data['no_transaksi'] = new_number_pengiriman($data['id_faktur']);
			$data['created_by'] = user_session('id');
			$data['qty_pesan'] = $qty_pesan;
			
			$this->db->insert('pengiriman', $data);
			$id_pengiriman = $this->db->insert_id();
			
			foreach ($detail as $i => $d) {
				$detail[$i]['id_pengiriman'] = $id_pengiriman;
			}
			
			$this->db->insert_batch('pengiriman_detail', $detail);
		}
		
		redirect(site_url('penjualan/pengiriman'));
	}
	
	public function update()
	{
		$id_pengiriman = $this->input->post('id');
		$list_produk = $this->input->post('produk');
		
		$detail = array();
		$qty_pesan = 0;
		
		foreach ($list_produk as $i => $produk) {
			
			if ($produk['qty'] > 0) {
				
				$qty = $produk['qty'];
				
				$detail[] = array (
					'id_pengiriman' => $id_pengiriman,
					'id_produk' => $produk['id'],
					'uraian' => $produk['uraian'],
					'id_satuan' => $produk['id_satuan'],
					'satuan' => $produk['satuan'],
					'qty' => $qty,
				);
				
				$qty_pesan += $qty;
			}
		}
		
		if (count($detail) > 0) {
			$key = array('tgl', 'id_pelanggan','id_faktur','alamat', 'keterangan');
			$data = post_data($key);
			
			$data['updated_by'] = user_session('id');
			$data['qty_pesan'] = $qty_pesan;
			
			$this->db->update('pengiriman', $data, array('id' => $id_pengiriman));
			
			$this->db->delete('pengiriman_detail', array('id_pengiriman' => $id_pengiriman));
			$this->db->insert_batch('pengiriman_detail', $detail);
		}
		
		redirect(site_url('penjualan/pengiriman'));
	}

	public function ajax_open_faktur()
	{
		$id_pelanggan = $this->input->post('id_pelanggan');
		
		$src = $this->db
					->select('id, no_transaksi, tgl')
					->from('faktur')
					->where('row_status', 1)
					->where('id_pelanggan', $id_pelanggan)
					->order_by('no_transaksi')
					->get();
		
		header('Content-Type: application/json');
		echo json_encode($src->result());
	}
}
