<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Faktur extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->form_validation->CI =& $this;
	}
	
	public function index()
	{
		$this->load->view('templates/app_tpl', array (
			'content' => 'faktur_index',
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
			FROM faktur AS a
			LEFT JOIN pengguna AS b ON a.created_by = b.id
			LEFT JOIN pengguna AS c ON a.updated_by = c.id
			JOIN pelanggan AS d ON a.id_pelanggan = d.id
			LEFT JOIN penjualan AS e ON a.id_penjualan = e.id
			WHERE
				a.row_status = 1
				AND (
					a.no_transaksi LIKE ?
					OR d.kode LIKE ?
					OR d.nama LIKE ?
					OR e.no_transaksi LIKE ?
				)
		";
		
		$data_sql = "
			SELECT
				a.*
				, UPPER(b.username) AS yg_buat
				, UPPER(c.username) AS yg_ubah
				, CONCAT(d.kode, ' &middot; ', d.nama) AS pelanggan
				, e.no_transaksi AS no_pesanan
				, e.tgl AS tgl_pesanan
				, e.qty_pesan
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
			'content' => 'faktur_form',
			'action_url' => site_url("penjualan/faktur/{$aksi}"),
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
			->select('faktur.*,CONCAT(kode, " . ", nama) AS kode_nama')
			->from('faktur')
			->join('pelanggan', 'pelanggan.id = faktur.id_pelanggan')
			->where('faktur.row_status', 1)
			->where('faktur.id', $id)
			->get();
		
		if ($src->num_rows() == 0) {
			show_404();
		}
		
		$this->_form('update', $src->row_array());
	}
	
	public function js_detail($id)
	{
		$src = $this->db
			->from('faktur_detail')
			->where('row_status', 1)
			->where('id_faktur', $id)
			->get();
		
		header('Content-Type: text/javascript');
		echo 'var details = '.json_encode($src->result()).';';
	}
	
	public function ajax_open_pesanan()
	{
		$id_pelanggan = $this->input->post('id_pelanggan');
		
		$src = $this->db
					->select('id, no_transaksi, tgl, diskon_faktur, biaya_lain')
					->from('penjualan')
					->where('row_status', 1)
					->where('id_pelanggan', $id_pelanggan)
					->order_by('no_transaksi')
					->get();
		
		header('Content-Type: application/json');
		echo json_encode($src->result());
	}
	
	public function ajax_pesanan_detail()
	{
		$id_penjualan = $this->input->post('id_penjualan');
		
		$src = $this->db
					->from('penjualan_detail')
					->where('row_status', 1)
					->where('id_penjualan', $id_penjualan)
					->order_by('id')
					->get();
		
		header('Content-Type: application/json');
		echo json_encode($src->result());
	}
	
	public function insert()
	{
		$list_produk = $this->input->post('produk');
		
		$detail = array();
		$qty_kirim = 0;
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
				
				$qty_kirim += $qty;
				$total += $sub_total;
			}
		}
		
		if (count($detail) > 0) {
			$key = array('tgl', 'id_pelanggan', 'id_penjualan', 'keterangan', 'diskon_faktur|number', 'biaya_lain|number', 'uang_muka|number', 'rek_pembayaran_dp|number');
			$data = post_data($key);
			
			$data['no_transaksi'] = new_number('faktur');
			$data['created_by'] = user_session('id');
			$data['qty_kirim'] = $qty_kirim;
			$data['total'] = $total;
			$data['grand_total'] = $total - $data['diskon_faktur'] + $data['biaya_lain'];
			$uang_muka = str_replace('.', '', $data['uang_muka']);
			$data['sisa_tagihan'] = $data['grand_total']-$uang_muka;
			
			$this->db->insert('faktur', $data);
			$id_faktur = $this->db->insert_id();
			//---membuat no pembayaran
			$data_pembelian = $this->db->from('faktur')->where('id', $id_faktur)->get()->row();
			$no_transaksi=$data_pembelian->no_transaksi;
			$jml_pembayaran = $this->db->from('pembayaran_faktur')->where('id_faktur', $id_faktur)->get()->num_rows();
			$no_selanjutnya = $jml_pembayaran+1;
			$no_pembayaran = "P-".$no_transaksi."-".$no_selanjutnya;
			
			$dataDp['no_transaksi'] = $no_pembayaran;
			$dataDp['id_faktur'] = $id_faktur;
			$dataDp['tgl'] = date("Y-m-d");
			$dataDp['rek_pembayaran'] = $data['rek_pembayaran_dp'];
			$dataDp['nominal'] =  $uang_muka;
			$dataDp['created_by'] = user_session('id');
			$this->db->insert('pembayaran_faktur', $dataDp);

			//--------------------------

			foreach ($detail as $i => $d) {
				$detail[$i]['id_faktur'] = $id_faktur;
			}
			
			$this->db->insert_batch('faktur_detail', $detail);
			
			#
			# MASUKKAN JURNAL STOK
			#
			$src = $this->db
					->from('faktur_detail')
					->where('row_status', 1)
					->where('id_faktur', $id_faktur)
					->order_by('id')
					->get();
			
			$jstok = array();
			
			foreach ($src->result() as $row) {
				$jstok[] = array (
					'no_referensi' => $data['no_transaksi'],
					'tgl' => $data['tgl'],
					'jenis_trx' => 'penjualan',
					'id_produk' => $row->id_produk,
					'qty' => $row->qty * (-1),
					'id_header' => $row->id_faktur,
					'id_detail' => $row->id,
					'created_by' => $data['created_by'],
				);
			}
			
			$this->db->insert_batch('jstok', $jstok);
			
			#
			# UPDATE PENJUALAN
			#
			$sql = "
				UPDATE penjualan
				SET qty_kirim = (
					SELECT SUM(qty)
					FROM faktur_detail
					WHERE id_faktur IN (
						SELECT id
						FROM faktur
						WHERE id_penjualan = '{$data['id_penjualan']}'
					)
				)
				WHERE id = '{$data['id_penjualan']}'
			";
			$this->db->query($sql);
		}
		
		redirect(site_url('penjualan/faktur'));
	}
	
	public function update()
	{
		$id_faktur = $this->input->post('id');
		$id_penjualan_sebelumnya = $this->input->post('id_penjualan_sebelumnya');
		$list_produk = $this->input->post('produk');
		
		$detail = array();
		$qty_kirim = 0;
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
				
				$qty_kirim += $qty;
				$total += $sub_total;
			}
		}
		
		if (count($detail) > 0) {
			$key = array('no_transaksi', 'tgl', 'id_pelanggan', 'id_penjualan', 'keterangan', 'diskon_faktur|number', 'biaya_lain|number');
			$data = post_data($key);
			
			$data['updated_by'] = user_session('id');
			$data['qty_kirim'] = $qty_kirim;
			$data['total'] = $total;
			$data['grand_total'] = $total - $data['diskon_faktur'] + $data['biaya_lain'];
			
			$this->db->update('faktur', $data, array('id' => $id_faktur));
			
			foreach ($detail as $i => $d) {
				$detail[$i]['id_faktur'] = $id_faktur;
			}
			
			$this->db->delete('faktur_detail', array('id_faktur' => $id_faktur));
			$this->db->insert_batch('faktur_detail', $detail);
			
			#
			# MASUKKAN JURNAL STOK
			#
			$src = $this->db
					->from('faktur_detail')
					->where('row_status', 1)
					->where('id_faktur', $id_faktur)
					->order_by('id')
					->get();
			
			$jstok = array();
			
			foreach ($src->result() as $row) {
				$jstok[] = array (
					'no_referensi' => $data['no_transaksi'],
					'tgl' => $data['tgl'],
					'jenis_trx' => 'penjualan',
					'id_produk' => $row->id_produk,
					'qty' => $row->qty * (-1),
					'id_header' => $row->id_faktur,
					'id_detail' => $row->id,
					'created_by' => $data['updated_by'],
				);
			}
			
			$this->db->delete('jstok', array('jenis_trx' => 'penjualan', 'id_header' => $id_faktur));
			$this->db->insert_batch('jstok', $jstok);
			
			#
			# UPDATE PENJUALAN
			#
			$sql = "
				UPDATE penjualan
				SET qty_kirim = (
					SELECT SUM(qty)
					FROM faktur_detail
					WHERE id_faktur IN (
						SELECT id
						FROM faktur
						WHERE id_penjualan = '{$data['id_penjualan']}'
					)
				)
				WHERE id = '{$data['id_penjualan']}'
			";
			$this->db->query($sql);
			
			#
			# UPDATE PENJUALAN SEBELUMNYA
			#
			$sql = "
				UPDATE penjualan
				SET qty_kirim = (
					SELECT SUM(qty)
					FROM faktur_detail
					WHERE id_faktur IN (
						SELECT id
						FROM faktur
						WHERE id_penjualan = '{$id_penjualan_sebelumnya}'
					)
				)
				WHERE id = '{$id_penjualan_sebelumnya}'
			";
			$this->db->query($sql);
		}
		
		redirect(site_url('penjualan/faktur'));
	}
	
		public function pembayaran()
	{
		$id_pembayaran = $this->input->post('id_pembayaran');
		$id_faktur = $this->input->post('id_faktur');
		//---membuat no pembayaran
		$data_pembelian = $this->db->from('faktur')->where('id', $id_faktur)->get()->row();
		$no_transaksi=$data_pembelian->no_transaksi;
		$jml_pembayaran = $this->db->from('pembayaran_faktur')->where('id_faktur', $id_faktur)->get()->num_rows();
		$no_selanjutnya = $jml_pembayaran+1;
		$no_pembayaran = "P-".$no_transaksi."-".$no_selanjutnya;
		//--------------------------
		$tgl_pembayaran = $this->input->post('tgl_pembayaran');
		$rek_pembayaran = $this->input->post('rek_pembayaran');
		$nominal_pembayaran = $this->input->post('nominal_pembayaran');
		
		$data['no_transaksi'] = $no_pembayaran;
		$data['id_faktur'] = $id_faktur;
		$data['tgl'] = $tgl_pembayaran;
		$data['rek_pembayaran'] = $rek_pembayaran;
		$data['nominal'] =  str_replace('.', '', $nominal_pembayaran);
		$data['created_by'] = user_session('id');
		$res = [];
		if($id_pembayaran !== ""){
			$result = $this->db->update('pembayaran_faktur', $data, array('id' => $id_pembayaran));
			if($result){
				$res = [
					"type" => "warning",
					"message" => "Data berhasil di ubah"
				];
			}
		}
		else{
			$result = $this->db->insert('pembayaran_faktur', $data);
			if($result){
				$res = [
					"type" => "success",
					"message" => "Data berhasil di tambahkan"
				];
			}
		}
		header('Content-Type: application/json');
		echo json_encode($res);
	}
	
	public function ajax_load_pembayaran()
	{
		$id_faktur = $this->input->get('id');
		$src = $this->db
					->select('`pembayaran_faktur`.*, `rekening`.`no_rekening`, `rekening`.`bank`')
					->from('pembayaran_faktur')
					->join('rekening', 'rekening.id = pembayaran_faktur.rek_pembayaran')
					->where('pembayaran_faktur.row_status', 1)
					->where('id_faktur', $id_faktur)
					->order_by('pembayaran_faktur.id')
					->get();
		
		header('Content-Type: application/json');
		echo json_encode($src->result());
	}

	public function hapus_pembayaran()
	{
		$id = $this->input->get('id');
		$data['row_status'] = 0;
		$result = $this->db->update('pembayaran_faktur', $data, array('id' => $id));
		header('Content-Type: application/json');
		echo json_encode($result);
	}
	public function ajax_open_faktur()
	{
		$id = $this->input->post('id_faktur');		
		$src = $this->db
					->select('*')
					->from('faktur')
					->where('id', $id)
					->get();
		
		header('Content-Type: application/json');
		echo json_encode($src->row());
	}

	public function cetak($id, $tipe = 'faktur', $no_header = false)
	{
		$this->load->library('pdf');
		$header = $this->db->query(
			"SELECT a.*,b.* FROM faktur a JOIN pelanggan b ON a.id_pelanggan = b.id WHERE a.id = $id"
		)->row();
		$details = $this->db->query(
			"SELECT a.*,b.* FROM faktur_detail a JOIN ref_produk b ON a.id_produk = b.id WHERE a.id_faktur = $id"
		)->result();
		$bank = $this->db->query(
			"SELECT a.* FROM rekening a WHERE a.is_use = '1'"
		)->row();

		$data = [
			"header" => $header,
			"detail" => $details,
			"bank" => $bank,
			"no_header" => $no_header
		];
		//$this->pdf->load_view('nota',$data,"a5","landscape",$header->no_transaksi.".pdf");

		if($tipe == 'faktur') {
			$this->pdf->load_pdf('nota2', $data, $header->no_transaksi.".pdf");
		} else {
			$this->pdf->load_pdf('nota_surat_jalan', $data, $header->no_transaksi.".pdf");
		}
		
	}
}
