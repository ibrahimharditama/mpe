<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pesanan extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->form_validation->CI =& $this;
	}
	
	public function index()
	{
		$this->load->view('templates/app_tpl', array (
			'content' => 'pesanan_index',
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
			FROM penjualan AS a
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
		
		foreach ($aaData as $i => $d) {
			$aaData[$i]['grand_total'] = rupiah($d['grand_total']);
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
			'content' => 'pesanan_form',
			'action_url' => site_url("penjualan/pesanan/{$aksi}"),
			'data' => $data,
			'id' => $data == null ? 0 : $data['id'],
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
		->select('penjualan.*,CONCAT(kode, " . ", nama) AS kode_nama')
		->from('penjualan')
		->join('pelanggan', 'pelanggan.id = penjualan.id_pelanggan')
		->where('penjualan.row_status', 1)
			->where('penjualan.id', $id)
			->get();
		
		if ($src->num_rows() == 0) {
			show_404();
		}
		
		$this->_form('update', $src->row_array());
	}
	
	public function js_detail($id)
	{
		$src = $this->db
			->from('penjualan_detail')
			->where('row_status', 1)
			->where('id_penjualan', $id)
			->get();
		
		header('Content-Type: text/javascript');
		echo 'var details = '.json_encode($src->result()).';';
	}
	
	public function insert()
	{
		$list_produk = $this->input->post('produk');
		
		$detail = array();
		$qty_pesan = 0;
		$total = 0;
		
		foreach ($list_produk as $i => $produk) {
			
			if ($produk['qty'] > 0) {
				
				$qty = $produk['qty'];
				$harga_jual = str_replace('.', '', $produk['harga_jual']);
				$diskon = str_replace('.', '', $produk['diskon']);
				$sub_total = $produk['qty'] * ($harga_jual - $diskon);
				
				$detail[] = array (
					'id_produk' => $produk['id'],
					'uraian' => $produk['uraian'],
					'id_satuan' => $produk['id_satuan'],
					'satuan' => $produk['satuan'],
					'qty' => $qty,
					'harga_satuan' => $harga_jual,
					'diskon' => $diskon,
					'sub_total' => $sub_total,
				);
				
				$qty_pesan += $qty;
				$total += $sub_total;
			}
		}
		
		if (count($detail) > 0) {
			$key = array('tgl', 'tgl_kirim', 'id_pelanggan', 'keterangan', 'diskon_faktur|number', 'biaya_lain|number');
			$data = post_data($key);
			
			$data['no_transaksi'] = new_number('penjualan');
			$data['created_by'] = user_session('id');
			$data['qty_pesan'] = $qty_pesan;
			$data['total'] = $total;
			$data['grand_total'] = $total - $data['diskon_faktur'] + $data['biaya_lain'];
			
			$this->db->insert('penjualan', $data);
			$id_penjualan = $this->db->insert_id();
			
			foreach ($detail as $i => $d) {
				$detail[$i]['id_penjualan'] = $id_penjualan;
			}
			
			$this->db->insert_batch('penjualan_detail', $detail);
		}
		
		redirect(site_url('penjualan/pesanan'));
	}
	
	public function update()
	{
		$id_penjualan = $this->input->post('id');
		$list_produk = $this->input->post('produk');
		
		$detail = array();
		$qty_pesan = 0;
		$total = 0;
		
		foreach ($list_produk as $i => $produk) {
			
			if ($produk['qty'] > 0) {
				
				$qty = $produk['qty'];
				$harga_jual = str_replace('.', '', $produk['harga_jual']);
				$diskon = str_replace('.', '', $produk['diskon']);
				$sub_total = $produk['qty'] * ($harga_jual - $diskon);
				
				$detail[] = array (
					'id_penjualan' => $id_penjualan,
					'id_produk' => $produk['id'],
					'uraian' => $produk['uraian'],
					'id_satuan' => $produk['id_satuan'],
					'satuan' => $produk['satuan'],
					'qty' => $qty,
					'harga_satuan' => $harga_jual,
					'diskon' => $diskon,
					'sub_total' => $sub_total,
				);
				
				$qty_pesan += $qty;
				$total += $sub_total;
			}
		}
		
		if (count($detail) > 0) {
			$key = array('tgl', 'tgl_kirim', 'id_pelanggan', 'keterangan', 'diskon_faktur|number', 'biaya_lain|number');
			$data = post_data($key);
			
			$data['updated_by'] = user_session('id');
			$data['qty_pesan'] = $qty_pesan;
			$data['total'] = $total;
			$data['grand_total'] = $total - $data['diskon_faktur'] + $data['biaya_lain'];
			
			$this->db->update('penjualan', $data, array('id' => $id_penjualan));
			
			$this->db->delete('penjualan_detail', array('id_penjualan' => $id_penjualan));
			$this->db->insert_batch('penjualan_detail', $detail);
		}
		
		redirect(site_url('penjualan/pesanan'));
	}

	public function cetak($id, $tipe)
	{
		$this->load->library('pdf');
		$header = $this->db->query(
			"SELECT a.*,b.* FROM penjualan a JOIN pelanggan b ON a.id_pelanggan = b.id WHERE a.id = $id"
		)->row();
		$details = $this->db->query(
			"SELECT a.*,b.* FROM penjualan_detail a JOIN ref_produk b ON a.id_produk = b.id WHERE a.id_penjualan = $id"
		)->result();
		$bank = $this->db->query(
			"SELECT a.* FROM rekening a WHERE a.is_use = '1'"
		)->row();
		$data = [
			"tipe" => $tipe,
			"header" => $header,
			"detail" => $details,
			"bank" => $bank,
			"table_count" => ceil(count($details) / 10),
		];
		$this->load->view('pesanan_print', $data);
		
	}

}
