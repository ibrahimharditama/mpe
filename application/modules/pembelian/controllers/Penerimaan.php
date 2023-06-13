<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penerimaan extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->form_validation->CI =& $this;
		$this->load->library('datatables');
	}
	
	public function index()
	{
		$this->load->view('templates/app_tpl', array (
			'content' => 'penerimaan_index',
		));
	}
	
	public function datatable()
	{
		$this->datatables->select("id, no_transaksi, tgl, supplier, id_pembelian, no_pembelian, tgl_pembelian, qty_pesan, 
								qty_terima, yg_buat, yg_ubah, keterangan_pay, grand_total")
                    ->from("(SELECT a.*
								, UPPER(b.username) AS yg_buat
								, UPPER(c.username) AS yg_ubah
								, CONCAT(d.kode, ' &middot; ', d.nama) AS supplier
								, IFNULL(e.no_transaksi, '') AS no_pembelian
								, IFNULL(e.tgl, '') AS tgl_pembelian
								, IFNULL(e.qty_pesan, 0) AS qty_pesan
								, CONCAT(a.keterangan, ' ', IFNULL(x.keterangan, '')) AS keterangan_pay
							FROM penerimaan AS a
							LEFT JOIN pengguna AS b ON a.created_by = b.id
							LEFT JOIN pengguna AS c ON a.updated_by = c.id
							JOIN supplier AS d ON a.id_supplier = d.id
							LEFT JOIN pembelian AS e ON a.id_pembelian = e.id
							LEFT JOIN (
								SELECT id_pembelian, GROUP_CONCAT(CONCAT(r.no_rekening, ' (', r.bank, ') - Rp', p.nominal, ' ', p.keterangan) SEPARATOR ' | ') AS keterangan
								FROM pembayaran_beli p 
								JOIN rekening r ON r.id = p.rek_pembayaran 
								WHERE p.row_status = 1 
								GROUP BY id_pembelian
								ORDER BY tgl DESC
							) x ON x.id_pembelian = a.id
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
			'content' => 'penerimaan_form',
			'action_url' => site_url("pembelian/penerimaan/{$aksi}"),
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

		$src = $this->db->query("SELECT p.*, IFNULL(pb.no_transaksi, '') AS pembelian, 
								CONCAT(s.kode, ' - ', s.nama) AS supplier, 
								IFNULL(x.total_bayar, 0) AS total_bayar
								FROM penerimaan p 
								LEFT JOIN pembelian pb ON pb.id = p.id_pembelian 
								JOIN supplier s ON s.id = p.id_supplier 
								LEFT JOIN (
									SELECT id_pembelian, SUM(nominal) AS total_bayar 
									FROM pembayaran_beli 
									WHERE row_status = 1 
									GROUP BY id_pembelian
								) x ON x.id_pembelian = p.id
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
			->from('penerimaan_detail')
			->where('row_status', 1)
			->where('id_penerimaan', $id)
			->get();
		
		header('Content-Type: text/javascript');
		echo 'var details = '.json_encode($src->result()).';';
	}
	
	public function ajax_open_pesanan()
	{
		$id_supplier = $this->input->post('id_supplier');
		$id_pembelian = $this->input->post('id_pembelian');
		
		$src = $this->db
					->select('id, no_transaksi, tgl, diskon_faktur, biaya_lain')
					->from('pembelian')
					->where('row_status', 1)
					->where('id_supplier', $id_supplier)
					->where("(qty_pesan > qty_kirim OR id = '$id_pembelian')")
					->order_by('no_transaksi')
					->get();
		
		header('Content-Type: application/json');
		echo json_encode($src->result());
	}
	
	public function ajax_pembelian_detail()
	{
		$id_pembelian = $this->input->post('id_pembelian');

		$src = $this->db
					->from('pembelian')
					->where('row_status', 1)
					->where('id', $id_pembelian)
					->get();
		
		$src_detail = $this->db
					->from('pembelian_detail')
					->where('row_status', 1)
					->where('id_pembelian', $id_pembelian)
					->order_by('id')
					->get();

		$data = (object) array(
			'pembelian' => $src->row(),
			'detail' => $src_detail->result()
		);
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function insert()
	{
		$list_produk = $this->input->post('produk');
		
		$detail = array();
		$qty_terima = 0;
		$total = 0;
		
		foreach ($list_produk as $i => $produk) {
			
			if ($produk['qty'] > 0) {
				
				$qty = str_replace('.', '', $produk['qty']);
				$harga_beli = str_replace('.', '', $produk['harga_beli']);
				$diskon = str_replace('.', '', $produk['diskon']);
				$sub_total = $qty * ($harga_beli - $diskon);
				
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
				
				$qty_terima += $qty;
				$total += $sub_total;
			}
		}
		
		if (count($detail) > 0) {
			$key = array('tgl', 'id_supplier', 'id_pembelian', 'keterangan', 'keterangan_biaya_lain', 'diskon_faktur|number', 'biaya_lain|number');
			$data = post_data($key);
			
			$data['no_transaksi'] = new_number('tagihan');
			$data['created_by'] = user_session('id');
			$data['qty_terima'] = $qty_terima;
			$data['total'] = $total;
			$data['grand_total'] = $total - $data['diskon_faktur'] + $data['biaya_lain'];

			$this->db->insert('penerimaan', $data);
			$id_penerimaan = $this->db->insert_id();

			foreach ($detail as $i => $d) {
				$detail[$i]['id_penerimaan'] = $id_penerimaan;
			}
			
			$this->db->insert_batch('penerimaan_detail', $detail);
			
			
			# MASUKKAN JURNAL STOK

			$this->_ins_del_stok($data['tgl'], $data['no_transaksi'], $id_penerimaan);
			
			
			# UPDATE PEMBELIAN

			if(isset($data['id_pembelian']) && $data['id_pembelian'] != null && $data['id_pembelian'] != '') {
				$this->_upd_pembelian($data['id_pembelian']);
			}
		}
		
		redirect(site_url('pembelian/penerimaan/ubah/' . $id_penerimaan));
	}
	
	public function update()
	{
		$id_penerimaan = $this->input->post('id');
		$id_pembelian_sebelumnya = $this->input->post('id_pembelian_sebelumnya');
		$list_produk = $this->input->post('produk');
		
		$detail = array();
		$qty_terima = 0;
		$total = 0;
		
		foreach ($list_produk as $i => $produk) {
			
			if ($produk['qty'] > 0) {
				
				$qty = str_replace('.', '', $produk['qty']);
				$harga_beli = str_replace('.', '', $produk['harga_beli']);
				$diskon = str_replace('.', '', $produk['diskon']);
				$sub_total = $qty * ($harga_beli - $diskon);
				
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
				
				$qty_terima += $qty;
				$total += $sub_total;
			}
		}
		
		if (count($detail) > 0) {
			$key = array('no_transaksi', 'tgl', 'id_supplier', 'id_pembelian', 'keterangan', 'keterangan_biaya_lain', 'diskon_faktur|number', 'biaya_lain|number');
			$data = post_data($key);
			
			$data['updated_by'] = user_session('id');
			$data['qty_terima'] = $qty_terima;
			$data['total'] = $total;
			$data['grand_total'] = $total - $data['diskon_faktur'] + $data['biaya_lain'];
			
			$this->db->update('penerimaan', $data, array('id' => $id_penerimaan));
			
			foreach ($detail as $i => $d) {
				$detail[$i]['id_penerimaan'] = $id_penerimaan;
			}
			
			$this->db->delete('penerimaan_detail', array('id_penerimaan' => $id_penerimaan));
			$this->db->insert_batch('penerimaan_detail', $detail);

			# MASUKKAN JURNAL STOK

			$this->_ins_del_stok($data['tgl'], $data['no_transaksi'], $id_penerimaan);
			
			
			# UPDATE PEMBELIAN

			if(isset($data['id_pembelian']) && $data['id_pembelian'] != null && $data['id_pembelian'] != '') {
				$this->_upd_pembelian($data['id_pembelian']);
			}
			
			#
			# UPDATE PEMBELIAN SEBELUMNYA
			#
			$sql = "
				UPDATE pembelian
				SET qty_kirim = (
					SELECT SUM(qty)
					FROM penerimaan_detail
					WHERE id_penerimaan IN (
						SELECT id
						FROM penerimaan
						WHERE id_pembelian = '{$id_pembelian_sebelumnya}'
					)
				)
				WHERE id = '{$id_pembelian_sebelumnya}'
			";
			$this->db->query($sql);
		}
		
		redirect($this->agent->referrer());
	}

	private function _ins_del_stok($tgl, $no_transaksi, $id_penerimaan)
	{
		$this->db->delete('jstok', ['jenis_trx' => 'pembelian', 'id_header' => $id_penerimaan]);

		$src = $this->db
					->from('penerimaan_detail')
					->where('row_status', 1)
					->where('id_penerimaan', $id_penerimaan)
					->order_by('id')
					->get();
			
		$jstok = array();
		
		foreach ($src->result() as $row) {
			$jstok[] = array (
				'no_referensi' => $no_transaksi,
				'tgl' => $tgl,
				'jenis_trx' => 'pembelian',
				'id_produk' => $row->id_produk,
				'qty' => $row->qty,
				'id_header' => $id_penerimaan,
				'id_detail' => $row->id,
				'created_by' => user_session('id'),
			);
		}
		
		$this->db->insert_batch('jstok', $jstok);
	}

	private function _upd_pembelian($id_pembelian) 
	{
		$this->db->query("UPDATE pembelian
						SET qty_kirim = (
							SELECT SUM(qty)
							FROM penerimaan_detail
							WHERE id_penerimaan IN (
								SELECT id
								FROM penerimaan
								WHERE id_pembelian = $id_pembelian
							)
						)
						WHERE id = $id_pembelian");
	}
	
	public function pembayaran()
	{
		$id_pembayaran = $this->input->post('id_pembayaran');
		$id_pembelian = $this->input->post('id_beli');
		//---membuat no pembayaran
		$data_pembelian = $this->db->from('penerimaan')->where('id', $id_pembelian)->get()->row();
		$no_transaksi=$data_pembelian->no_transaksi;
		$jml_pembayaran = $this->db->from('pembayaran_beli')->where('id_pembelian', $id_pembelian)->get()->num_rows();
		$no_selanjutnya = $jml_pembayaran+1;
		$no_pembayaran = "P-".$no_transaksi."-".$no_selanjutnya;
		//--------------------------
		$tgl_pembayaran = $this->input->post('tgl_pembayaran');
		$rek_pembayaran = $this->input->post('rek_pembayaran');
		$nominal_pembayaran = $this->input->post('nominal_pembayaran');
		$keterangan = $this->input->post('keterangan');
		
		$data['no_transaksi'] = $no_pembayaran;
		$data['id_pembelian'] = $id_pembelian;
		$data['tgl'] = $tgl_pembayaran;
		$data['rek_pembayaran'] = $rek_pembayaran;
		$data['nominal'] =  str_replace('.', '', $nominal_pembayaran);
		$data['keterangan'] = $keterangan;
		$data['created_by'] = user_session('id');
		$res = [];
		if($id_pembayaran !== ""){
			// var_dump($id_pembayaran);
			// exit();
			$result = $this->db->update('pembayaran_beli', $data, array('id' => $id_pembayaran));
			if($result){
				$res = [
					"type" => "warning",
					"message" => "Data berhasil di ubah"
				];
			}
		}
		else{
			$result = $this->db->insert('pembayaran_beli', $data);
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
		$id_pembelian = $this->input->get('id');
		$src = $this->db
		->select('`pembayaran_beli`.*, `rekening`.`no_rekening`, `rekening`.`bank`')
					->from('pembayaran_beli')
					->join('rekening', 'rekening.id = pembayaran_beli.rek_pembayaran')
					->where('pembayaran_beli.row_status', 1)
					->where('id_pembelian', $id_pembelian)
					->order_by('pembayaran_beli.id')
					->get();
		
		header('Content-Type: application/json');
		echo json_encode($src->result());
	}

	public function hapus_pembayaran()
	{
		$id = $this->input->get('id');
		$data['row_status'] = 0;
		$result = $this->db->update('pembayaran_beli', $data, array('id' => $id));
		header('Content-Type: application/json');
		echo json_encode($result);
	}

	public function ajax_open_penerimaan()
	{
		$id = $this->input->post('id_penerimaan');		
		$src = $this->db
					->select('*')
					->from('penerimaan')
					->where('id', $id)
					->get();
		
		header('Content-Type: application/json');
		echo json_encode($src->row());
	}

	public function cetak($id)
	{
		$this->load->library('pdf');
		$header = $this->db->query("SELECT a.*, b.*, IFNULL(x.total_bayar, 0) AS total_bayar 
									FROM penerimaan a 
									JOIN supplier b ON a.id_supplier = b.id 
									LEFT JOIN (
										SELECT id_pembelian, SUM(nominal) AS total_bayar 
										FROM pembayaran_beli 
										WHERE row_status = 1 
										GROUP BY id_pembelian
									) x ON x.id_pembelian = a.id
									WHERE a.id = $id 
								")->row();
		$details = $this->db->query(
			"SELECT a.*,b.* FROM penerimaan_detail a JOIN ref_produk b ON a.id_produk = b.id WHERE a.id_penerimaan = $id"
		)->result();
		$data = [
			"header" => $header,
			"detail" => $details,
			"table_count" => ceil(count($details) / 10),
		];

		$this->load->view('penerimaan_print',$data);				
	}

}
