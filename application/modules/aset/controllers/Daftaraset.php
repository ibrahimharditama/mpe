<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Daftaraset extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->form_validation->CI =& $this;
        $this->load->library('datatables');
	}
	
	public function index()
	{
		$this->load->view('templates/app_tpl', array (
			'content' => 'daftar_aset_index',
		));
	}

    public function datatable()
	{
		$this->datatables->select("id, id_pegawai, pegawai, nama, model, tgl_pembelian, periode_maintenance, tgl_maintenance, tgl_harus_maintenance, count_day_maintenance")
                    ->from("(SELECT a.id, a.id_pegawai, p.nama AS pegawai, a.nama, a.model, a.tgl_pembelian, 
                               CONCAT(a.waktu_maintenance, ' ', a.periode_maintenance) AS periode_maintenance, IFNULL(x.tgl_maintenance, '-') AS tgl_maintenance, 
                               ADDDATE(IFNULL(x.tgl_maintenance, DATE_FORMAT(NOW(),'%Y-%m-%d')), INTERVAL (IF(a.periode_maintenance = 'BULAN',30,7) * a.waktu_maintenance) DAY) AS tgl_harus_maintenance,
	                            DATEDIFF(ADDDATE(IFNULL(x.tgl_maintenance, DATE_FORMAT(NOW(),'%Y-%m-%d')), INTERVAL (IF(a.periode_maintenance = 'BULAN',30,7) * a.waktu_maintenance) DAY), DATE_FORMAT(NOW(),'%Y-%m-%d')) AS count_day_maintenance
                            FROM asset a 
                            JOIN pengguna p ON p.id = a.id_pegawai AND p.row_status = 1
                            LEFT JOIN (
                                    SELECT id_asset, MAX(tgl_maintenance) AS tgl_maintenance 
                                    FROM asset_maintenance 
                                    WHERE row_status = 1
                                    GROUP BY id_asset
                                ) x ON x.id_asset = a.id
                            WHERE a.row_status = 1) a");

        $result = json_decode($this->datatables->generate());

        $response['datatable'] = $result;
        $response['draw'] =  $result->draw;
        $response['recordsTotal'] =  $result->recordsTotal;
        $response['recordsFiltered'] =  $result->recordsFiltered;

		echo json_encode($response);
	}

}