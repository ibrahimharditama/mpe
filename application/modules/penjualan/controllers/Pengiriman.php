<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengiriman extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->form_validation->CI =& $this;
		$this->load->library('datatables');
	}
	
	public function index()
	{
		$this->load->view('templates/app_tpl', array (
			'content' => 'pengiriman_index',
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

		$this->datatables->select("id, no_transaksi, tgl, id_faktur, pelanggan, qty_semua, yg_buat, yg_ubah, supir, kenek, teknisi, is_approve, status_kembali")
                    ->from("(SELECT a.*
							, (a.qty_pesan + a.qty_nota) as qty_semua
							, UPPER(b.username) AS yg_buat
							, UPPER(c.username) AS yg_ubah
							, d.nama AS pelanggan
							, IFNULL(s.pegawai, '') AS supir
							, IFNULL(k.pegawai, '') AS kenek
							, IFNULL(t.pegawai, '') AS teknisi
							, CASE 
								WHEN x.id_pengiriman IS NOT NULL THEN 'Sudah'
								WHEN y.id_pengiriman IS NOT NULL THEN 'Belum' 
								ELSE 'Tidak Ada' 
							  END AS status_kembali
							FROM pengiriman AS a
							LEFT JOIN pengguna AS b ON a.created_by = b.id
							LEFT JOIN pengguna AS c ON a.updated_by = c.id
							JOIN pelanggan AS d ON a.id_pelanggan = d.id 
							LEFT JOIN (
								SELECT id_pengiriman 
								FROM pengembalian_pipa 
								WHERE row_status = 1 
								GROUP BY id_pengiriman
							) AS x ON x.id_pengiriman = a.id 
							LEFT JOIN (
								SELECT id_pengiriman
								FROM pengiriman_detail 
								WHERE row_status = 1 
								GROUP BY id_pengiriman
							) AS y ON y.id_pengiriman = a.id
							LEFT JOIN (
								SELECT pp.id_pengiriman, GROUP_CONCAT(p.nama  SEPARATOR ' , ') AS pegawai
								FROM pengiriman_person pp 
								JOIN pengguna p ON p.id = pp.id_pengguna 
								WHERE pp.row_status = 1 AND pp.tipe = 'supir'
								GROUP BY pp.id_pengiriman
							) s ON s.id_pengiriman = a.id
							LEFT JOIN (
								SELECT pp.id_pengiriman, GROUP_CONCAT(p.nama  SEPARATOR ' , ') AS pegawai
								FROM pengiriman_person pp 
								JOIN pengguna p ON p.id = pp.id_pengguna 
								WHERE pp.row_status = 1 AND pp.tipe = 'kenek'
								GROUP BY pp.id_pengiriman
							) k ON k.id_pengiriman = a.id
							LEFT JOIN (
								SELECT pp.id_pengiriman, GROUP_CONCAT(p.nama  SEPARATOR ' , ') AS pegawai
								FROM pengiriman_person pp 
								JOIN pengguna p ON p.id = pp.id_pengguna 
								WHERE pp.row_status = 1 AND pp.tipe = 'teknisi'
								GROUP BY pp.id_pengiriman
							) t ON t.id_pengiriman = a.id
							WHERE a.row_status = 1 AND a.status = 1 $where) a");

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
		// if ( ! $this->agent->referrer()) {
		// 	show_404();
		// }
		
		$src = $this->db
			->select('pengiriman.*,CONCAT(kode, " . ", nama) AS kode_nama')
			->from('pengiriman')
			->join('pelanggan', 'pelanggan.id = pengiriman.id_pelanggan')
			->where('pengiriman.row_status', 1)
			->where('pengiriman.id', $id)
			->get();

		if ($src->num_rows() == 0) {
			show_404();
		}

		$src = $src->row_array();
		
		// pengiriman person
		$person = $this->db
			->from('pengiriman_person')
			->where('row_status', 1)
			->where('id_pengiriman', $id)
			->get()->result();

		$src['person'] = [];
		$src['person']['supir'] = [];
		$src['person']['kenek'] = [];
		$src['person']['teknisi'] = [];

		foreach ($person as $key => $value) {
			switch ($value->tipe) {
				case 'supir':
					$src['person']['supir'][] = $value->id_pengguna;
					break;

				case 'kenek':
					$src['person']['kenek'][] = $value->id_pengguna;
					break;

				case 'teknisi':
					$src['person']['teknisi'][] = $value->id_pengguna;
					break;
			}
		}
		
		$this->_form('update', $src);
	}
	
	public function js_detail($id)
	{
		$src = $this->db
			->from('pengiriman_detail')
			->where('row_status', 1)
			->where('id_pengiriman', $id)
			->get();
		
		$src_nota = $this->db
			->from('pengiriman_detail_nota')
			->where('row_status', 1)
			->where('id_pengiriman', $id)
			->get();
		
		header('Content-Type: text/javascript');
		echo 'var details_nota = '.json_encode($src_nota->result()).';';
		echo 'var details = '.json_encode($src->result()).';';
	}
	
	public function insert()
	{
		$input = $this->input->post();
		// echo '<pre>'; print_r($input);die(); // TODO debug

		$list_produk = $this->input->post('produk');
		$list_produk_nota = $this->input->post('nota');
		
		$detail = array();
		$detail_nota = array();
		$qty_pesan = 0;
		$qty_nota = 0;
		
		foreach ($list_produk as $i => $produk) {
			
			if ($produk['qty'] > 0) {
				
				$qty = str_replace('.', '', $produk['qty']);
				
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

		foreach ($list_produk_nota as $i => $produk) {
			
			if ($produk['qty'] > 0) {
				
				$qty = str_replace('.', '', $produk['qty']);
				
				$detail_nota[] = array (
					'id_produk' => $produk['id'],
					'uraian' => $produk['uraian'],
					'id_satuan' => $produk['id_satuan'],
					'satuan' => $produk['satuan'],
					'qty' => $qty,
				);
				
				$qty_nota += $qty;
			}
		}


		$key = array('tgl', 'id_pelanggan','id_faktur','alamat', 'keterangan');
		$data = post_data($key);

		// $count_kirim = "SELECT SUM(qty_nota) AS total from pengiriman where id_faktur = '".$data['id_faktur']."'";
		// $total_kirim = $this->db->query($count_kirim)->row('total');
		// $total_all_kirim = $total_kirim+$qty_nota;
		// $qty_kirim = $this->db->query("SELECT qty_kirim from faktur where id = '".$data['id_faktur']."'")->row('qty_kirim');
		// if($qty_kirim <= $total_all_kirim){
		// 	$dtFaktur['is_kirim'] = 1;
		// 	$this->db->update('faktur', $dtFaktur, array('id' => $data['id_faktur']));
		// }

		$data['no_transaksi'] = new_number_pengiriman($data['id_faktur']);
		$data['created_by'] = user_session('id');
		$data['qty_pesan'] = $qty_pesan;
		$data['qty_nota'] = $qty_nota;
		
		$this->db->insert('pengiriman', $data);
		$id_pengiriman = $this->db->insert_id();
		
		foreach ($detail as $i => $d) {
			$detail[$i]['id_pengiriman'] = $id_pengiriman;
		}

		foreach ($detail_nota as $i => $d) {
			$detail_nota[$i]['id_pengiriman'] = $id_pengiriman;
		}

		if(count($detail) > 0) {
			$this->db->insert_batch('pengiriman_detail', $detail);
		}
		
		if(count($detail_nota) > 0) {
			$this->db->insert_batch('pengiriman_detail_nota', $detail_nota);
		}

		// payload pengiriman_person
		$pengiriman_person_payload = [];
		if(isset($input['person_supir'])) {
			foreach ($input['person_supir'] as $key => $value) {
				$pengiriman_person_payload[] = [
					"id_pengiriman" => $id_pengiriman,
					"id_pengguna" => $value, 
					"tipe" => "supir", 
				];
			}
		}
		
		if(isset($input['person_kenek'])) {
			foreach ($input['person_kenek'] as $key => $value) {
				$pengiriman_person_payload[] = [
					"id_pengiriman" => $id_pengiriman,
					"id_pengguna" => $value, 
					"tipe" => "kenek", 
				];
			}
		}

		if(isset($input['person_teknisi'])) {
			foreach ($input['person_teknisi'] as $key => $value) {
				$pengiriman_person_payload[] = [
					"id_pengiriman" => $id_pengiriman,
					"id_pengguna" => $value, 
					"tipe" => "teknisi",
				];
			}
		}

		// delete pengiriman_person
		// $this->db->where('id_pengiriman',$id_pengiriman)->delete('pengiriman_person');

		// insert pengiriman_person
		if(count($pengiriman_person_payload) > 0) {
			$this->db->insert_batch('pengiriman_person',$pengiriman_person_payload);
		}

		$this->_upd_faktur($data['id_faktur'], '-');
		
		redirect(site_url('penjualan/pengiriman/ubah/' . $id_pengiriman ));
	}
	
	public function insert_jstok($id)
	{
		$src_data = $this->db->get_where('pengiriman', ['id' => $id, 'row_status' => 1]);
		if($src_data->num_rows() > 0) {
			$dtApprove['is_approve'] = 1;
			$this->db->update('pengiriman', $dtApprove, array('id' => $id));
	
			$data = $src_data->row();
			$detail = $this->db->get_where('pengiriman_detail', ['id_pengiriman' => $id, 'row_status' => 1])->result();

			$insert = array();
			foreach ($detail as $row) {
				$insert[] = array(
					'no_referensi' => $data->no_transaksi,
					'tgl' => $data->tgl,
					'jenis_trx' => 'pengiriman',
					'id_produk' => $row->id_produk,
					'qty' => $row->qty * (-1),
					'id_header' => $row->id_pengiriman,
					'id_detail' => $row->id,
					'row_status' => 1,
					'created_by' => user_session('id'),
				);
			}

			db_delete('jstok', ['jenis_trx' => 'pengiriman', 'id_header' => $id, 'row_status' => 1]);

			if(count($insert) > 0) $this->db->insert_batch('jstok', $insert);
		}
	}

	private function _upd_faktur($id_faktur, $id_faktur_sebelumnya) 
	{
		$data = [$id_faktur, $id_faktur_sebelumnya];

		for ($i=0; $i < count($data); $i++) { 

			if($data[$i] != '-') {
				$qty_faktur = $this->db->query("SELECT IFNULL(SUM(fd.qty), 0) AS qty_faktur
											FROM faktur_detail fd 
											JOIN ref_produk p ON p.id = fd.id_produk AND p.id_tipe != 21 
											WHERE fd.id_faktur = $data[$i] 
												AND fd.row_status = 1")->row();

				$qty_kirim = $this->db->query("SELECT IFNULL(SUM(ppd.qty), 0) AS qty_kirim
											FROM pengiriman_detail_nota ppd 
											JOIN pengiriman p ON p.id = ppd.id_pengiriman 
											JOIN ref_produk pr ON pr.id = ppd.id_produk AND pr.id_tipe != 21
											WHERE p.id_faktur = $data[$i]  
												AND ppd.row_status = 1")->row();

				if($qty_kirim->qty_kirim >=  $qty_faktur->qty_faktur) {
					$this->db->update('faktur', ['is_kirim' => 1], ['id' => $data[$i]]);
				} else {
					$this->db->update('faktur', ['is_kirim' => 0], ['id' => $data[$i]]);
				}
			}
		}
		
	}

	public function update()
	{
		$input = $this->input->post();

		$id_pengiriman = $this->input->post('id');
		$list_produk = $this->input->post('produk');
		$list_produk_nota = $this->input->post('nota');
		$is_approve = $this->input->post('is_approve');
		
		$detail = array();
		$detail_nota = array();
		$qty_pesan = 0;
		$qty_nota = 0;
		
		foreach ($list_produk as $i => $produk) {
			
			if ($produk['qty'] > 0) {
				
				$qty = str_replace('.', '', $produk['qty']);
				
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

		foreach ($list_produk_nota as $i => $produk) {
			
			if ($produk['qty'] > 0) {
				
				$qty = str_replace('.', '', $produk['qty']);
				
				$detail_nota[] = array (
					'id_pengiriman' => $id_pengiriman,
					'id_produk' => $produk['id'],
					'uraian' => $produk['uraian'],
					'id_satuan' => $produk['id_satuan'],
					'satuan' => $produk['satuan'],
					'qty' => $qty,
				);
				
				$qty_nota += $qty;
			}
		}
		
		$key = array('tgl', 'id_pelanggan','id_faktur','alamat', 'keterangan');
		$data = post_data($key);
		
		$data['updated_by'] = user_session('id');
		$data['qty_pesan'] = $qty_pesan;
		$data['qty_nota'] = $qty_nota;
		
		$this->db->update('pengiriman', $data, array('id' => $id_pengiriman));
		
		$this->db->delete('pengiriman_detail', array('id_pengiriman' => $id_pengiriman));
		$this->db->delete('pengiriman_detail_nota', array('id_pengiriman' => $id_pengiriman));
		if(count($detail) > 0) {
			$this->db->insert_batch('pengiriman_detail', $detail);
		}
		
		if(count($detail_nota) > 0) {
			$this->db->insert_batch('pengiriman_detail_nota', $detail_nota);
		}
		

		// payload pengiriman_person
		$pengiriman_person_payload = [];
		if(isset($input['person_supir'])) {
			foreach ($input['person_supir'] as $key => $value) {
				$pengiriman_person_payload[] = [
					"id_pengiriman" => $id_pengiriman,
					"id_pengguna" => $value, 
					"tipe" => "supir", 
				];
			}
		}
		
		if(isset($input['person_kenek'])) {
			foreach ($input['person_kenek'] as $key => $value) {
				$pengiriman_person_payload[] = [
					"id_pengiriman" => $id_pengiriman,
					"id_pengguna" => $value, 
					"tipe" => "kenek", 
				];
			}
		}

		if(isset($input['person_teknisi'])) {
			foreach ($input['person_teknisi'] as $key => $value) {
				$pengiriman_person_payload[] = [
					"id_pengiriman" => $id_pengiriman,
					"id_pengguna" => $value, 
					"tipe" => "teknisi",
				];
			}
		}

		// delete pengiriman_person
		$this->db->where('id_pengiriman',$id_pengiriman)->delete('pengiriman_person');

		// insert pengiriman_person
		if(count($pengiriman_person_payload) > 0) {
			$this->db->insert_batch('pengiriman_person',$pengiriman_person_payload);
		}

		$this->_upd_faktur($data['id_faktur'], $input['id_faktur_sebelumnya']);

		
		if($is_approve == 1) {
			// jika sudah di approve maka langsung masukan ke JSTOK
			$this->insert_jstok($id_pengiriman);

			// menghapus pengembalian pipa
			$src_pengembalian = $this->db->get_where('pengembalian_pipa', ['id_pengiriman' => $id_pengiriman, 'row_status' => 1]);

			if($src_pengembalian->num_rows() > 0) {
				$data_pengembalian = $src_pengembalian->result();

				foreach ($data_pengembalian as $pengembalian) {
					db_delete('pengembalian_pipa', ['id' => $pengembalian->id]);
					db_delete('pengembalian_pipa_detail', ['id_pengembalian_pipa' => $pengembalian->id]);
					db_delete('jstok', ['jenis_trx' => 'pengembalian_pipa', 'id_header' => $pengembalian->id, 'row_status' => 1]);
				}
			}

			
		}
		
		redirect($this->agent->referrer());
	}

	public function ajax_open_faktur()
	{
		$id_faktur = $this->input->post('id_faktur');

		$src = $this->db->query("SELECT id, no_transaksi, tgl 
								FROM faktur 
								WHERE row_status = 1 
									AND (is_kirim = 0 OR id = $id_faktur) 
								ORDER BY no_transaksi
							");
		
		header('Content-Type: application/json');
		echo json_encode($src->result());
	}

	public function cetak($id)
	{
		$header = $this->db->query(
			"SELECT a.*,b.* FROM pengiriman a JOIN pelanggan b ON a.id_pelanggan = b.id WHERE a.id = $id"
		)->row();
		$details = $this->db->query(
			"SELECT a.*,b.* FROM pengiriman_detail a JOIN ref_produk b ON a.id_produk = b.id WHERE a.id_pengiriman = $id"
		)->result();
		$details_nota = $this->db->query(
			"SELECT a.*,b.* FROM pengiriman_detail_nota a JOIN ref_produk b ON a.id_produk = b.id WHERE a.id_pengiriman = $id"
		)->result();
		$bank = $this->db->query(
			"SELECT a.* FROM rekening a WHERE a.is_use = '1'"
		)->row();
		$data = [
			"header" => $header,
			"detail" => array_merge($details,$details_nota),
			"bank" => $bank,
			"table_count" => ceil(count($details) / 10),

		];

		$this->load->view('pengiriman_print', $data);
	}

	public function approve()
	{
		$id = $this->input->post('id');
		$this->insert_jstok($id);
		$res = array (
			'type' => "success",
		);
		$this->session->set_flashdata('post_status', 'approve');
		echo json_encode($res);
	}

	public function delete($id, $id_faktur)
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

		$this->load->helper('delete');
		
		if (check_fk('pengiriman', $id)) {
			
			db_update('faktur', ['is_kirim' => 0], ['id' => $id_faktur, 'row_status' => 1]);
			db_delete('pengiriman', ['id' => $id]);
			db_delete('pengiriman_detail', ['id_pengiriman' => $id]);
			db_delete('pengiriman_detail_nota', ['id_pengiriman' => $id]);
			db_delete('jstok', ['jenis_trx' => 'pengiriman', 'id_header' => $id, 'row_status' => 1]);

			
			$status = 'ok';
		}
		else {
			$status = 'err';
		}

		$this->session->set_flashdata('delete_status', $status);
		
		redirect($this->agent->referrer());
	}

	public function ajax_get_pelanggan($id_faktur)
	{
		$src = $this->db->query("SELECT f.id_pelanggan, p.nama AS nama_pelanggan
								FROM faktur f 
								JOIN pelanggan p ON p.id = f.id_pelanggan
								WHERE f.id = $id_faktur")->row();	
								
		echo json_encode($src);
	}

}
