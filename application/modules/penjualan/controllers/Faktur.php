<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Faktur extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->form_validation->CI =& $this;
		$this->load->library('datatables');
	}
	
	public function index()
	{
		$this->load->view('templates/app_tpl', array (
			'content' => 'faktur_index',
		));
	}
	
	public function datatable()
	{
		$this->datatables->select("id, no_transaksi, tgl, id_penjualan, no_pesanan, tgl_pesanan, pelanggan, keterangan_pay, 
								grand_total, qty_pesan, qty_kirim, yg_buat, yg_ubah")
                    ->from("(SELECT a.*
								, UPPER(b.username) AS yg_buat
								, UPPER(c.username) AS yg_ubah
								, CONCAT(d.kode, ' &middot; ', d.nama) AS pelanggan
								, IFNULL(e.no_transaksi, '') AS no_pesanan
								, IFNULL(e.tgl, '') AS tgl_pesanan
								, IFNULL(e.qty_pesan, 0) AS qty_pesan
								, CONCAT(a.keterangan, ' ', IFNULL(x.keterangan, '')) AS keterangan_pay
							FROM faktur AS a
							LEFT JOIN pengguna AS b ON a.created_by = b.id
							LEFT JOIN pengguna AS c ON a.updated_by = c.id
							JOIN pelanggan AS d ON a.id_pelanggan = d.id
							LEFT JOIN penjualan AS e ON a.id_penjualan = e.id
							LEFT JOIN (
								SELECT id_faktur, GROUP_CONCAT(CONCAT(r.no_rekening, ' (', r.bank, ') - Rp', FORMAT(p.nominal,0), ' ', IFNULL(p.keterangan, '')) SEPARATOR ' | ') AS keterangan
								FROM pembayaran_faktur p 
								JOIN rekening r ON r.id = p.rek_pembayaran 
								WHERE p.row_status = 1 
								GROUP BY id_faktur
								ORDER BY tgl DESC
							) x ON x.id_faktur = a.id
							WHERE a.row_status = 1) a");

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

		$src = $this->db->query("SELECT f.*, IFNULL(pj.no_transaksi, '') AS penjualan, 
								CONCAT(p.kode, ' - ', p.nama) AS pelanggan,
								IFNULL(x.total_bayar, 0) AS total_bayar
								FROM faktur f 
								LEFT JOIN penjualan pj ON pj.id = f.id_penjualan
								JOIN pelanggan p ON p.id = f.id_pelanggan
								LEFT JOIN (
									SELECT id_faktur, SUM(nominal) AS total_bayar 
									FROM pembayaran_faktur 
									WHERE row_status = 1 
									GROUP BY id_faktur
								) x ON x.id_faktur = f.id
								WHERE f.row_status = 1 
									AND f.id = $id
							");
		
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
					->from('penjualan')
					->where('row_status', 1)
					->where('id', $id_penjualan)
					->get();
		
		$src_detail = $this->db
					->from('penjualan_detail')
					->where('row_status', 1)
					->where('id_penjualan', $id_penjualan)
					->order_by('id')
					->get();

		$data = (object) array(
			'penjualan' => $src->row(),
			'detail' => $src_detail->result()
		);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function insert()
	{
		$list_produk = $this->input->post('produk');
		
		$detail = array();
		$qty_kirim = 0;
		$total = 0;
		
		foreach ($list_produk as $i => $produk) {
			
			if ($produk['qty'] > 0) {
				
				$qty = str_replace('.', '', $produk['qty']);
				$harga_jual = str_replace('.', '', $produk['harga_jual']);
				$diskon = str_replace('.', '', $produk['diskon']);
				$sub_total = $qty * ($harga_jual - $diskon);
				
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
			$key = array('tgl', 'id_pelanggan', 'id_penjualan', 'keterangan', 'keterangan_biaya_lain', 'diskon_faktur|number', 'biaya_lain|number');
			$data = post_data($key);
			
			$data['no_transaksi'] = new_number('faktur');
			$data['created_by'] = user_session('id');
			$data['qty_kirim'] = $qty_kirim;
			$data['total'] = $total;
			$data['grand_total'] = $total - $data['diskon_faktur'] + $data['biaya_lain'];
			
			$this->db->insert('faktur', $data);
			$id_faktur = $this->db->insert_id();

			//--------------------------

			foreach ($detail as $i => $d) {
				$detail[$i]['id_faktur'] = $id_faktur;
			}
			
			$this->db->insert_batch('faktur_detail', $detail);

			# MASUKKAN JURNAL STOK

			$this->_ins_del_stok($data['tgl'], $data['no_transaksi'], $id_faktur);
			
			
			# UPDATE PENJUALAN

			if(isset($data['id_penjualan']) && $data['id_penjualan'] != null && $data['id_penjualan'] != '') {
				$this->_upd_penjualan($data['id_penjualan']);
			}
		}
		
		redirect(site_url('penjualan/faktur/ubah/' . $id_faktur));
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
				
				$qty = str_replace('.', '', $produk['qty']);
				$harga_jual = str_replace('.', '', $produk['harga_jual']);
				$diskon = str_replace('.', '', $produk['diskon']);
				$sub_total = $qty * ($harga_jual - $diskon);
				
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
			
			# MASUKKAN JURNAL STOK

			$this->_ins_del_stok($data['tgl'], $data['no_transaksi'], $id_faktur);
			
			
			# UPDATE PENJUALAN

			if(isset($data['id_penjualan']) && $data['id_penjualan'] != null && $data['id_penjualan'] != '') {
				$this->_upd_penjualan($data['id_penjualan']);
			}
			
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
		
		redirect($this->agent->referrer());
	}

	private function _ins_del_stok($tgl, $no_transaksi, $id_faktur)
	{
		$this->db->delete('jstok', ['jenis_trx' => 'penjualan', 'id_header' => $id_faktur]);

		$src = $this->db->query("SELECT fd.*, p.id_tipe
								FROM faktur_detail fd 
								LEFT JOIN ref_produk p ON p.id = fd.id_produk
								WHERE fd.row_status = 1 
									AND fd.id_faktur = $id_faktur
								ORDER BY fd.id
							");	
			
		$jstok = array();
		
		foreach ($src->result() as $row) {

			if($row->id_tipe == 21) continue;

			$jstok[] = array (
				'no_referensi' => $no_transaksi,
				'tgl' => $tgl,
				'jenis_trx' => 'penjualan',
				'id_produk' => $row->id_produk,
				'qty' => $row->qty * (-1),
				'id_header' => $id_faktur,
				'id_detail' => $row->id,
				'created_by' => user_session('id'),
			);
		}
		
		if(count($jstok)) $this->db->insert_batch('jstok', $jstok);

	}

	private function _upd_penjualan($id_penjualan) 
	{
		$this->db->query("UPDATE penjualan
						SET qty_kirim = (
							SELECT SUM(qty)
							FROM faktur_detail
							WHERE id_faktur IN (
								SELECT id
								FROM faktur
								WHERE id_penjualan = $id_penjualan
							)
						)
						WHERE id = $id_penjualan");
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
		$keterangan = $this->input->post('keterangan');
		
		$data['no_transaksi'] = $no_pembayaran;
		$data['id_faktur'] = $id_faktur;
		$data['tgl'] = $tgl_pembayaran;
		$data['rek_pembayaran'] = $rek_pembayaran;
		$data['nominal'] =  str_replace('.', '', $nominal_pembayaran);
		$data['keterangan'] = $keterangan;
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
		$id = $this->input->post('id');
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

	public function cetak($id, $tipe = 'faktur', $no_header = false, $is_rekening = true)
	{
		$this->load->library('pdf');
		$header = $this->db->query("SELECT a.*,b.*, a.keterangan AS keterangan_faktur, 
									IFNULL(x.total_bayar, 0) AS total_bayar  
									FROM faktur a 
									JOIN pelanggan b ON a.id_pelanggan = b.id 
									LEFT JOIN (
										SELECT id_faktur, SUM(nominal) AS total_bayar 
										FROM pembayaran_faktur 
										WHERE row_status = 1 
										GROUP BY id_faktur
									) x ON x.id_faktur = a.id
									WHERE a.id = $id
								")->row();

		$details = $this->db->query(
			"SELECT a.*,b.* FROM faktur_detail a JOIN ref_produk b ON a.id_produk = b.id WHERE a.id_faktur = $id"
		)->result();

		$detailPipas = $this->db->query(
			"SELECT b.uraian as nama,b.qty,b.satuan FROM pengiriman a JOIN pengiriman_detail b ON b.id_pengiriman = a.id where a.id_faktur = $id"
		)->result();

		$bank = $this->db->query(
			"SELECT a.* FROM rekening a WHERE a.is_use = '1'"
		)->row();

		$data = [
			"tipe" => $tipe,
			"header" => $header,
			"detail" => $details,
			"detailPipa" => $detailPipas,
			"bank" => $bank,
			"no_header" => $no_header,
			"is_rekening" => $is_rekening,
			"table_count" => ceil(count($details) / 10),
		];

		$this->load->view($tipe == 'faktur' ? 'faktur_penjualan_print' : 'faktur_penjualan_sj_print', $data);

		//$this->pdf->load_view('nota',$data,"a5","landscape",$header->no_transaksi.".pdf");
		// if($tipe == 'faktur') {
		// 	$this->pdf->load_pdf('nota2', $data, $header->no_transaksi.".pdf");
		// } else {
		// 	$this->pdf->load_pdf('nota_surat_jalan', $data, $header->no_transaksi.".pdf");
		// }
		
	}
}
