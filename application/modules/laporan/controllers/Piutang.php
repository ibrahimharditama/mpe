<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Piutang extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->form_validation->CI =& $this;
	}
	
	public function index()
	{
		$this->load->view('templates/app_tpl', array (
			'content' => 'piutang_index',
            'data' => $this->_data(),
		));
	}

    private function _data()
    {
        $src = $this->db->query("SELECT p.id, 
                                    p.id_supplier, 
                                    s.kode AS kode_supplier, 
                                    s.nama AS supplier,
                                    s.alamat AS alamat_supplier,
                                    p.no_transaksi, 
                                    p.grand_total AS total,
                                    IFNULL(x.total_bayar, 0) AS bayar, 
                                    (p.grand_total - IFNULL(x.total_bayar, 0)) AS sisa
                                FROM penerimaan p 
                                JOIN supplier s ON s.id = p.id_supplier 
                                LEFT JOIN (
                                    SELECT id_pembelian, SUM(nominal) AS total_bayar 
                                    FROM pembayaran_beli 
                                    WHERE row_status = 1
                                    GROUP BY id_pembelian
                                ) x ON x.id_pembelian = p.id
                                WHERE p.row_status = 1  
                                ORDER BY s.nama")->result_array();

        $data = array();
        
        foreach ($src as $row) {

            if($row['sisa'] <= 0) continue;

            $data[$row['kode_supplier']][] = array(                
                'id' => $row['id'],
                'no_transaksi' => $row['no_transaksi'],
                'supplier' => $row['supplier'] .'  &middot; '. $row['kode_supplier'] .'<br>'. $row['alamat_supplier'],
                'total' => $row['total'],
                'bayar' => $row['bayar'],
                'sisa' => $row['sisa'],
            );
        }

        return $data;


    }

}