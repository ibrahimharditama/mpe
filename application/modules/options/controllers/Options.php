<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Options extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('datatables');
	}

	public function urutan($table, $selected = '')
	{
		$src = $this->db
			->select("urutan, CONCAT(urutan, ' - ', nama) AS urutan_nama")
			->from($table)
			->where('row_status', 1)
			->order_by('urutan')
			->get();
		
		$options = options($src, 'urutan', $selected, 'urutan_nama');
		
		$sql = "SELECT MAX(urutan) AS last_urutan FROM {$table} WHERE row_status = 1";
		$src = $this->db->query($sql);
		
		$next_urutan = $src->num_rows() == 0 ? 1 : $src->row('last_urutan') + 1;
		
		if ($selected == '' OR $selected == $next_urutan) {
			$options .= '<option value="'.$next_urutan.'" selected>'.$next_urutan.'</option>';
		}
		
		return $options;
	}
	
	public function number($start, $end, $selected = '')
	{
		$options = '';
		for ($i = $start; $i <= $end; $i++) {
			$selected_prop = $i == $selected ? 'selected' : '';
			$options .= '<option value="'.$i.'" '.$selected_prop.'>'.$i.'</option>';
		}
		
		return $options;
	}
	
	public function jam($selected = '', $increment = 15)
	{
		$options = '';
		
		for ($jam = 0; $jam < 24; $jam++) {
			for ($menit = 0; $menit < 60; $menit += $increment) {
				$jam_menit = str_pad($jam, 2, '0', STR_PAD_LEFT).':'.str_pad($menit, 2, '0', STR_PAD_LEFT);
				$selected_attr = $jam_menit.':00' == $selected ? 'selected' : '';
				
				$options .= '<option value="'.$jam_menit.':00" '.$selected_attr.'>'.$jam_menit.'</option>';
			}
		}
		
		return $options;
	}

	public function pengguna($selected = '')
	{
		$src = $this->db
			->from('pengguna')
			->where('row_status', 1)
			->order_by('nama')
			->get();
		
		return options($src, 'id', $selected, 'nama');
	}
	
	public function pengguna_grup($selected = '')
	{
		$src = $this->db
			->from('pengguna_grup')
			->where('row_status', 1)
			->order_by('urutan')
			->get();
		
		return options($src, 'id', $selected, 'nama');
	}
	
	public function lookup($kategori, $selected = '')
	{
		$src = $this->db
			->from('ref_lookup')
			->where('row_status', 1)
			->where('kategori', $kategori)
			->order_by('nama')
			->get();
		
		return options($src, 'id', $selected, 'nama');
	}
	
	public function supplier($selected = '')
	{
		$src = $this->db
			->select("id, CONCAT(kode, ' &middot; ', nama) AS kode_nama")
			->from('supplier')
			->where('row_status', 1)
			->order_by('kode')
			->get();
		
		return options($src, 'id', $selected, 'kode_nama');
	}
	
	public function pelanggan($selected = '')
	{
		$src = $this->db
			->select("id, CONCAT(kode, ' &middot; ', nama) AS kode_nama")
			->from('pelanggan')
			->where('row_status', 1)
			->order_by('kode')
			->get();
		
		return options($src, 'id', $selected, 'kode_nama');
	}
	
	public function data_pelanggan()
    {
        $q = $this->input->get('nama');
		$pelanggan = pelanggan($q);
		echo json_encode($pelanggan);
    }

	public function rekening($selected = '')
	{
		$src = $this->db
			->select("id, CONCAT(bank, ' &middot; ', no_rekening) AS kode_nama")
			->from('rekening')
			->where('row_status', 1)
			->order_by('bank')
			->get();
		
		return options($src, 'id', $selected, 'kode_nama');
	}
	
	public function produk($selected = '', $kategori = '')
	{
		if($kategori != '') {
			$this->db->where('a.id_tipe', $kategori);
		}

		$src = $this->db
			->select("a.*, CONCAT(a.kode, ' &middot; ', a.nama) AS kode_nama, b.nama AS satuan")
			->from('ref_produk AS a')
			->join('ref_lookup AS b', 'a.id_satuan = b.id')
			->where('a.row_status', 1)
			->order_by('a.kode')
			->get();
		
		$options = '';
		
		foreach ($src->result() as $row) {
			$selected_attr = $selected == $row->id ? 'selected' : '';
			$options .= '<option value="'.$row->id.'" data-nama="'.$row->nama.'" data-id-satuan="'.$row->id_satuan.'" data-satuan="'.$row->satuan.'" data-harga-beli="'.$row->harga_beli.'" data-harga-jual="'.$row->harga_jual.'" '.$selected_attr.'>'.$row->kode_nama.'</option>';
		}
		
		return $options;
	}

	public function produk_qty($selected = '')
	{
		$src = $this->db
			->select("
				a.*, CONCAT(a.kode, ' &middot; ', a.nama) AS kode_nama, b.nama AS satuan,
				(select sum(qty) from jstok x where x.row_status = 1 and x.id_produk = a.id) qty
			")
			->from('ref_produk AS a')
			->join('ref_lookup AS b', 'a.id_satuan = b.id')
			->where('a.row_status', 1)
			->where('a.id_tipe', 22)
			->order_by('a.kode')
			->get();
		
		$options = '';
		
		foreach ($src->result() as $row) {
			$selected_attr = $selected == $row->id ? 'selected' : '';
			$options .= '<option value="'.$row->id.'" data-qty="'.$row->qty.'" data-nama="'.$row->nama.'" data-id-satuan="'.$row->id_satuan.'" data-satuan="'.$row->satuan.'" data-harga-beli="'.$row->harga_beli.'" data-harga-jual="'.$row->harga_jual.'" '.$selected_attr.'>'.$row->kode_nama.'</option>';
		}
		
		return $options;
	}

	public function produk_nota($id_nota,$json= 0 ,$selected = '')
	{
		$src = $this->db
			->select("a.*, c.uraian AS uraian_produk, CONCAT(a.kode, ' &middot; ', a.nama) AS kode_nama, b.nama AS satuan, c.qty")
			->from('ref_produk AS a')
			->join('ref_lookup AS b', 'a.id_satuan = b.id')
			->join('faktur_detail c','c.id_produk = a.id')
			->where('a.row_status', 1)
			->where('c.id_faktur', $id_nota)
			->order_by('a.kode')
			->get();

		if($json == "1") {
			echo json_encode($src->result());
			return;
		}
		
		$options = '';
		
		foreach ($src->result() as $row) {
			$selected_attr = $selected == $row->id ? 'selected' : '';
			$options .= '<option value="'.$row->id.'" data-nama="'.$row->nama.'" data-id-satuan="'.$row->id_satuan.'" data-satuan="'.$row->satuan.'" data-harga-beli="'.$row->harga_beli.'" data-harga-jual="'.$row->harga_jual.'" '.$selected_attr.'>'.$row->kode_nama.'</option>';
		}
		
		return $options;
	}

	public function aset($selected = '')
	{
		$src = $this->db
			->select("a.id, CONCAT(a.nama, ' - ', a.model, ' - ', p.nama ) AS nama")
			->from('asset a')
			->join('pengguna p', 'a.id_pegawai = p.id')
			->where('a.row_status', 1)
			->order_by('a.nama')
			->get();
		
		return options($src, 'id', $selected, 'nama');
	}

	public function pengiriman_penjualan($selected = '')
	{
		$src = $this->db->query("SELECT p.id, CONCAT(p.no_transaksi, ' &middot; ', pl.nama) AS kode
								FROM pengiriman p 
								JOIN pelanggan pl ON pl.id = p.id_pelanggan
								JOIN (
									SELECT id_pengiriman
									FROM pengiriman_detail 
									WHERE row_status = 1 
									GROUP BY id_pengiriman
								) AS y ON y.id_pengiriman = p.id
								WHERE p.row_status = 1 
									AND (p.id NOT IN (
											SELECT id_pengiriman 
											FROM pengembalian_pipa 
											WHERE row_status = 1 
												AND is_approve = 1 
											GROUP BY id_pengiriman) OR p.id = '$selected')
								ORDER BY p.id");

		// $src = $this->db
		// 	->select("p.id, CONCAT(p.no_transaksi, ' &middot; ', pl.nama) AS kode")
		// 	->from('pengiriman AS p')
		// 	->join('pelanggan AS pl', 'pl.id = p.id_pelanggan') 
		// 	->where('p.row_status', 1)
		// 	->order_by('p.id')
		// 	->get();
		
		return options($src, 'id', $selected, 'kode');
	}
	
	public function supplier_db()
	{
		$this->datatables->select("id, kode, nama, alamat, kontak, data")
                    ->from("(SELECT id, kode, nama, alamat, CONCAT(no_telp, ' - ', no_hp) AS kontak,
                    		CONCAT(id, '|', kode, '|', nama) AS data
                    		FROM supplier 
                    		WHERE row_status = 1) a");

        $result = json_decode($this->datatables->generate());

        $response['datatable'] = $result;
        $response['draw'] =  $result->draw;
        $response['recordsTotal'] =  $result->recordsTotal;
        $response['recordsFiltered'] =  $result->recordsFiltered;

        echo json_encode($response);
	}

	public function pelanggan_db()
	{
		$this->datatables->select("id, kode, nama, alamat, kontak, data")
                    ->from("(SELECT id, kode, nama, alamat, CONCAT(no_telp, ' - ', no_hp) AS kontak,
                    		CONCAT(id, '|', kode, '|', nama) AS data
                    		FROM pelanggan 
                    		WHERE row_status = 1) a");

        $result = json_decode($this->datatables->generate());

        $response['datatable'] = $result;
        $response['draw'] =  $result->draw;
        $response['recordsTotal'] =  $result->recordsTotal;
        $response['recordsFiltered'] =  $result->recordsFiltered;

        echo json_encode($response);
	}

	public function pembelian_db()
	{
		$where = '';
		$input = $this->input->get();

		if(isset($input['id_supplier'])) 
			if($input['id_supplier'] != null && $input['id_supplier'] != '') {
				$where = " AND a.id_supplier = '".$input['id_supplier']."' AND a.qty_pesan > a.qty_kirim ";
			}

		if(isset($input['id_pembelian'])) 
			if($input['id_pembelian'] != null && $input['id_pembelian'] != '') {
				$where = " AND a.id_supplier = '".$input['id_supplier']."' AND (a.qty_pesan > a.qty_kirim OR a.id = '".$input['id_pembelian']."')";
			}
		


		$this->datatables->select("id, no_transaksi, tgl, tgl_kirim, qty_pesan, qty_kirim, grand_total, supplier")
                    ->from("(SELECT a.id, a.no_transaksi, a.tgl, a.tgl_kirim, a.qty_pesan, a.qty_kirim, a.grand_total,
							CONCAT(d.kode, ' &middot; ', d.nama) AS supplier
							FROM pembelian AS a
							JOIN supplier AS d ON a.id_supplier = d.id
							WHERE a.row_status = 1 $where) a");

        $result = json_decode($this->datatables->generate());

        $response['datatable'] = $result;
        $response['draw'] =  $result->draw;
        $response['recordsTotal'] =  $result->recordsTotal;
        $response['recordsFiltered'] =  $result->recordsFiltered;

		echo json_encode($response);
	}

	public function penjualan_db()
	{
		$where = '';
		$input = $this->input->get();

		if(isset($input['id_pelanggan'])) 
			if($input['id_pelanggan'] != null && $input['id_pelanggan'] != '') {
				$where = " AND a.id_pelanggan = '".$input['id_pelanggan']."' AND a.qty_pesan > a.qty_kirim ";
			}

		if(isset($input['id_penjualan'])) 
			if($input['id_penjualan'] != null && $input['id_penjualan'] != '') {
				$where = " AND a.id_pelanggan = '".$input['id_pelanggan']."' AND (a.qty_pesan > a.qty_kirim OR a.id = '".$input['id_penjualan']."')";
			}
		


		$this->datatables->select("id, no_transaksi, tgl, tgl_kirim, qty_pesan, qty_kirim, grand_total, pelanggan")
                    ->from("(SELECT a.id, a.no_transaksi, a.tgl, a.tgl_kirim, a.qty_pesan, a.qty_kirim, a.grand_total,
							CONCAT(d.kode, ' &middot; ', d.nama) AS pelanggan
							FROM penjualan AS a
							JOIN pelanggan AS d ON a.id_pelanggan = d.id
							WHERE a.row_status = 1 $where) a");

        $result = json_decode($this->datatables->generate());

        $response['datatable'] = $result;
        $response['draw'] =  $result->draw;
        $response['recordsTotal'] =  $result->recordsTotal;
        $response['recordsFiltered'] =  $result->recordsFiltered;

		echo json_encode($response);
	}
}
