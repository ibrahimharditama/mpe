<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pesanan extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->form_validation->CI =& $this;
		$this->load->library('datatables');
	}
	
	public function index()
	{
		$this->load->view('templates/app_tpl', array (
			'content' => 'pesanan_index',
		));
	}
	
	public function datatable()
	{
		$input = $this->input->get();
		$where = "";

		if(isset($input['datahari']))
			if($input['datahari'] != '' && $input['datahari'] != null && $input['datahari'] != 'all') {
				$tambah = $input['datahari'];
				$pastdate = date('Y-m-d', strtotime("-$tambah day", strtotime(date('Y-m-d'))));
				$where .= " AND a.tgl >= '". $pastdate. "' ";
			}


		$this->datatables->select("id, no_transaksi, tgl, tgl_kirim, qty_pesan, qty_kirim, grand_total, yg_buat, yg_ubah, supplier")
                    ->from("(SELECT a.id, a.no_transaksi, a.tgl, a.tgl_kirim, a.qty_pesan, a.qty_kirim, a.grand_total,
							UPPER(b.username) AS yg_buat,
							UPPER(c.username) AS yg_ubah,
							CONCAT(d.kode, ' &middot; ', d.nama) AS supplier
							FROM pembelian AS a
							LEFT JOIN pengguna AS b ON a.created_by = b.id
							LEFT JOIN pengguna AS c ON a.updated_by = c.id
							JOIN supplier AS d ON a.id_supplier = d.id
							WHERE a.row_status = 1 $where) a");

        $result = json_decode($this->datatables->generate());

        $response['datatable'] = $result;
        $response['draw'] =  $result->draw;
        $response['recordsTotal'] =  $result->recordsTotal;
        $response['recordsFiltered'] =  $result->recordsFiltered;

		echo json_encode($response);
	}
	
	private function _form($aksi = 'insert', $data = null)
	{
		if ($this->session->flashdata('data')) {
			$data = $this->session->flashdata('data');
		}
		
		$this->load->view('templates/app_tpl', array (
			'content' => 'pesanan_form',
			'action_url' => site_url("pembelian/pesanan/{$aksi}"),
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

		$src = $this->db->query("SELECT p.*, CONCAT(s.kode, ' - ', s.nama) AS supplier 
								FROM pembelian p 
								LEFT JOIN supplier s ON s.id = p.id_supplier
								WHERE p.row_status = 1 AND p.id = $id
							");
		
		if ($src->num_rows() == 0) {
			show_404();
		}
		
		$this->_form('update', $src->row_array());
	}
	
	public function js_detail($id)
	{
		$src = $this->db
			->from('pembelian_detail')
			->where('row_status', 1)
			->where('id_pembelian', $id)
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
				$harga_beli = str_replace('.', '', $produk['harga_beli']);
				$diskon = str_replace('.', '', $produk['diskon']);
				$sub_total = $produk['qty'] * ($harga_beli - $diskon);
				
				$detail[] = array (
					'id_produk' => $produk['id'],
					'uraian' => $produk['uraian'],
					'id_satuan' => $produk['id_satuan'],
					'satuan' => $produk['satuan'],
					'qty' => $qty,
					'harga_satuan' => $harga_beli,
					'diskon' => $diskon,
					'sub_total' => $sub_total,
				);
				
				$qty_pesan += $qty;
				$total += $sub_total;
			}
		}
		if (count($detail) > 0) {
			$key = array('tgl', 'tgl_kirim', 'id_supplier', 'keterangan', 'diskon_faktur|number', 'biaya_lain|number');
			$data = post_data($key);
			
			$data['no_transaksi'] = new_number('pembelian');
			$data['created_by'] = user_session('id');
			$data['qty_pesan'] = $qty_pesan;
			$data['total'] = $total;
			$data['grand_total'] = $total - $data['diskon_faktur'] + $data['biaya_lain'];
			
			$this->db->insert('pembelian', $data);
			$id_pembelian = $this->db->insert_id();
			
			foreach ($detail as $i => $d) {
				$detail[$i]['id_pembelian'] = $id_pembelian;
			}
			
			$this->db->insert_batch('pembelian_detail', $detail);
		}
		
		redirect(site_url('pembelian/pesanan/ubah/' . $id_pembelian));
	}
	
	public function update()
	{
		$id_pembelian = $this->input->post('id');
		$list_produk = $this->input->post('produk');
		
		$detail = array();
		$qty_pesan = 0;
		$total = 0;
		
		foreach ($list_produk as $i => $produk) {
			
			if ($produk['qty'] > 0) {
				
				$qty = str_replace('.', '', $produk['qty']);
				$harga_beli = str_replace('.', '', $produk['harga_beli']);
				$diskon = str_replace('.', '', $produk['diskon']);
				$sub_total = $produk['qty'] * ($harga_beli - $diskon);
				
				$detail[] = array (
					'id_pembelian' => $id_pembelian,
					'id_produk' => $produk['id'],
					'uraian' => $produk['uraian'],
					'id_satuan' => $produk['id_satuan'],
					'satuan' => $produk['satuan'],
					'qty' => $qty,
					'harga_satuan' => $harga_beli,
					'diskon' => $diskon,
					'sub_total' => $sub_total,
				);
				
				$qty_pesan += $qty;
				$total += $sub_total;
			}
		}
		
		if (count($detail) > 0) {
			$key = array('tgl', 'tgl_kirim', 'id_supplier', 'keterangan', 'diskon_faktur|number', 'biaya_lain|number');
			$data = post_data($key);
			
			$data['updated_by'] = user_session('id');
			$data['qty_pesan'] = $qty_pesan;
			$data['total'] = $total;
			$data['grand_total'] = $total - $data['diskon_faktur'] + $data['biaya_lain'];
			
			$this->db->update('pembelian', $data, array('id' => $id_pembelian));
			
			$this->db->delete('pembelian_detail', array('id_pembelian' => $id_pembelian));
			$this->db->insert_batch('pembelian_detail', $detail);
		}
		
		redirect($this->agent->referrer());
	}

	public function cetak($id)
	{
		$this->load->library('pdf');
		$header = $this->db->query(
			"SELECT a.*,b.*, a.keterangan AS keterangan_pembelian FROM pembelian a JOIN supplier b ON a.id_supplier = b.id WHERE a.id = $id"
		)->row();
		$details = $this->db->query(
			"SELECT a.*,b.* FROM pembelian_detail a JOIN ref_produk b ON a.id_produk = b.id WHERE a.id_pembelian = $id"
		)->result();
		$data = [
			"header" => $header,
			"detail" => $details,
			"table_count" => ceil(count($details) / 10),
		];

		$this->load->view('pesanan_print',$data);		
	}
}
