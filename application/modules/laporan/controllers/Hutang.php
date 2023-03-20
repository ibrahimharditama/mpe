<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hutang extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->form_validation->CI =& $this;
	}
	
	public function index()
	{
		$this->load->view('templates/app_tpl', array (
			'content' => 'hutang_index',
            'data' => $this->_data(),
		));
	}

    private function _data()
    {
        $src = $this->db->query("SELECT f.id, 
                                    f.id_pelanggan, 
                                    plg.kode AS kode_pelanggan, 
                                    plg.nama AS pelanggan,
                                    f.no_transaksi, 
                                    f.grand_total AS total,
                                    IFNULL(x.total_bayar, 0) AS bayar, 
                                    (f.grand_total - IFNULL(x.total_bayar, 0)) AS sisa
                                FROM faktur f 
                                JOIN pelanggan plg ON plg.id = f.id_pelanggan 
                                LEFT JOIN (
                                    SELECT id_faktur, SUM(nominal) AS total_bayar 
                                    FROM pembayaran_faktur 
                                    WHERE row_status = 1
                                    GROUP BY id_faktur
                                ) x ON x.id_faktur = f.id
                                WHERE f.row_status = 1 ")->result_array();

        $data = array();
        
        foreach ($src as $row) {
            $data[$row['kode_pelanggan']][] = array(
                'id' => $row['id'],
                'no_transaksi' => $row['no_transaksi'],
                'pelanggan' => $row['kode_pelanggan'] .'  &middot; '. $row['pelanggan'],
                'total' => $row['total'],
                'bayar' => $row['bayar'],
                'sisa' => $row['sisa'],
            );
        }

        return $data;


    }

}