<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengembalian_pipa extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->form_validation->CI =& $this;
        $this->load->library('datatables');
	}
	
	public function index()
	{
		$this->load->view('templates/app_tpl', array (
			'content' => 'pengembalian_pipa_index',
		));
	}

    public function datatable()
	{
		$this->datatables->select("id, tgl, id_pengiriman, no_transaksi, no_pengiriman, qty, keterangan, created_by, updated_by")
                    ->from("(SELECT pp.id, pp.tgl, pp.id_pengiriman, pp.no_transaksi, p.no_transaksi AS no_pengiriman, 
							pp.qty, pp.keterangan, IFNULL(b.nama, '') AS created_by, IFNULL(c.nama, '') AS updated_by 
							FROM pengembalian_pipa pp 
							JOIN pengiriman p ON p.id = pp.id_pengiriman AND p.row_status = 1 
							LEFT JOIN pengguna AS b ON pp.created_by = b.id
							LEFT JOIN pengguna AS c ON pp.updated_by = c.id
							WHERE pp.row_status = 1) a");

        $result = json_decode($this->datatables->generate());

        $response['datatable'] = $result;
        $response['draw'] =  $result->draw;
        $response['recordsTotal'] =  $result->recordsTotal;
        $response['recordsFiltered'] =  $result->recordsFiltered;

		echo json_encode($response);
	}

    private function _form($aksi = 'insert', $data = null)
	{		
		$this->load->view('templates/app_tpl', array (
			'content' => 'pengembalian_pipa_form',
			'action_url' => site_url("penjualan/pengembalian-pipa/{$aksi}"),
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
			->from('pengembalian_pipa')
			->where('row_status', 1)
			->where('id', $id)
			->get();
		
		if ($src->num_rows() == 0) {
			show_404();
		}

		$src_detail = $this->db
					->select("ppd.*, CONCAT(p.kode, ' &middot; ', p.nama) AS nama_produk")
					->from('pengembalian_pipa_detail AS ppd')
					->join('ref_produk AS p', 'p.id = ppd.id_produk')
					->where('ppd.row_status', 1)
					->where('ppd.id_pengembalian_pipa', $id)
					->get();

		$data = $src->row_array();
		$data['detail'] = $src_detail->result_array();
		
		$this->_form('update', $data);
	}

	private function _form_data()
	{
		$input = $this->input->post();

		$rules = array (
			array (
				'field' => 'tgl',
				'label' => 'Tgl. Pengembalian',
				'rules' => 'required',
			),
			array (
				'field' => 'id_pengiriman',
				'label' => 'No. Pengiriman',
				'rules' => 'required',
			),
			
		);

		if(isset($input['produk'])){
			foreach ($input['produk'] as $key => $value) {
                foreach ($value as $i => $v) {
					if($key == 'qty_kembali') {
						$rules[] = array (
							'field' => 'produk['.$key.']['.$i.']',
							'label' => "Qty Dikembalikan",
							'rules' => 'required|callback_qtycheck['.$i.']',
						); 
						
					}
					
				}
            }
		}
		
		$this->form_validation->set_rules($rules);
		
		if ($this->form_validation->run()) {
			
			$data = post_data(array('tgl', 'id_pengiriman', 'keterangan', 'qty|number'));

			$detail = array();

			if(isset($input['produk'])){
				foreach ($input['produk']['id_produk'] as $i => $value) {
					$detail[] = post_data(
										array(
											'produk[id_produk]['.$i.']', 
											'produk[id_satuan]['.$i.']',  
											'produk[satuan]['.$i.']', 
											'produk[qty_bawa]['.$i.']|number',
											'produk[qty_kembali]['.$i.']|number',
										)
									);
				}
			}

			$res = array (
				'data' => $data,
				'detail' => $detail,
			);
			
			
			return showRestResponse($res);
		}
		else {
			
			foreach ($rules as $r) {
				$errors[$r['field']] = form_error($r['field']);
			}
			
			return showRestResponse($errors, 400, 'Validation Error');;
		}
	}

	public function qtycheck($str, $i)
	{	
		$qty_bawa = (int) $this->input->post('produk[qty_bawa]['.$i.']');

		$qty = (int) str_replace('.', '', $str);
		$qty_bawa = (int) str_replace('.', '', $qty_bawa);
		
		if ($qty > $qty_bawa)
		{
			$this->form_validation->set_message('qtycheck', '{field} <= Qty dibawa');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

    public function insert()
	{
		$response = $this->_form_data();
		$response['url'] = base_url().'penjualan/pengembalian-pipa';
		
		if ($response['code'] == 200) {

			$this->db->trans_start();

			$data = $response['data'];		
			$data['data']['no_transaksi'] = new_number('pengembalian_pipa');

			$id = db_insert('pengembalian_pipa', $data['data']);

			if(count($data['detail']) > 0){
				$detail = array();
				foreach($data['detail'] as $key => $value) {
					$detail[] = array(
						'id_pengembalian_pipa' => $id,
						'id_produk' => $value['produk[id_produk]['.$key.']'],
						'id_satuan' => $value['produk[id_satuan]['.$key.']'],
						'satuan' => $value['produk[satuan]['.$key.']'],
						'qty_bawa' => $value['produk[qty_bawa]['.$key.']'],
						'qty_kembali' => $value['produk[qty_kembali]['.$key.']'],
						'created_by' => user_session('id'),
					);
				}
				$this->db->insert_batch('pengembalian_pipa_detail', $detail);
			}

			$this->insert_delete_stok($id);

			$this->db->trans_complete();	
            $this->session->set_flashdata('post_status', 'ok');
			
		}

		echo json_encode($response);
	}

    public function update()
	{
		$response = $this->_form_data();
		$id = $this->input->post('id');
		$response['url'] = base_url().'penjualan/pengembalian-pipa/ubah/'. $id;
		
		if ($response['code'] == 200) {

			$this->db->trans_start();

			$data = $response['data'];		

			db_update('pengembalian_pipa', $data['data'], ['id' => $id]);
			db_delete('pengembalian_pipa_detail', ['id_pengembalian_pipa' => $id]);

			if(count($data['detail']) > 0){
				$detail = array();
				foreach($data['detail'] as $key => $value) {
					$detail[] = array(
						'id_pengembalian_pipa' => $id,
						'id_produk' => $value['produk[id_produk]['.$key.']'],
						'id_satuan' => $value['produk[id_satuan]['.$key.']'],
						'satuan' => $value['produk[satuan]['.$key.']'],
						'qty_bawa' => $value['produk[qty_bawa]['.$key.']'],
						'qty_kembali' => $value['produk[qty_kembali]['.$key.']'],
						'created_by' => user_session('id'),
					);
				}
				$this->db->insert_batch('pengembalian_pipa_detail', $detail);
			}

			$this->insert_delete_stok($id);

			$this->db->trans_complete();	
            $this->session->set_flashdata('post_status', 'ok');
			
		}

		echo json_encode($response);
	}

    public function delete($id)
	{
		if ( ! $this->agent->referrer()) {
			show_404();
		}

		db_delete('pengembalian_pipa', ['id' => $id]);
		db_delete('pengembalian_pipa_detail', ['id_pengembalian_pipa' => $id]);
		db_delete('jstok', ['jenis_trx' => 'pengembalian_pipa', 'id_header' => $id, 'row_status' => 1]);
		
		redirect($this->agent->referrer());
	}

	public function ajax_get_detail_pengembalian($id)
	{
		$src = $this->db->query("SELECT pdp.*, CONCAT(p.kode, ' &middot; ', p.nama) AS nama_produk
								FROM pengiriman_detail pdp 
								JOIN ref_produk p ON p.id = pdp.id_produk 
								WHERE pdp.row_status = 1 AND pdp.id_pengiriman = $id")->result();
								
		echo json_encode($src);
	}

	public function insert_delete_stok($id)
	{
		$src_data = $this->db->get_where('pengembalian_pipa', ['id' => $id, 'row_status' => 1]);

		if($src_data->num_rows() > 0) {
			$data = $src_data->row();
			$detail = $this->db->get_where('pengembalian_pipa_detail', ['id_pengembalian_pipa' => $id, 'row_status' => 1])->result();
			db_delete('jstok', ['id_header' => $data->id]);

			$insert = array();
			foreach ($detail as $row) {

				if($row->qty_kembali == 0) continue;

				$insert[] = array(
					'no_referensi' => $data->no_transaksi,
					'tgl' => date('Y-m-d'),
					'jenis_trx' => 'pengembalian_pipa',
					'id_produk' => $row->id_produk,
					'qty' => $row->qty_kembali,
					'id_header' => $row->id_pengembalian_pipa,
					'id_detail' => $row->id,
					'row_status' => 1,
					'created_by' => user_session('id'),
				);
			}

			if(count($insert) > 0) $this->db->insert_batch('jstok', $insert);
		}

	}

}