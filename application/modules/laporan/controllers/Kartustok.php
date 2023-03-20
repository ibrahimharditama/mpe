<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kartustok extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->form_validation->CI =& $this;
	}
	
	public function index($periode = '')
	{   

		$this->load->view('templates/app_tpl', array (
			'content' => 'kartustok_index',
            'data' => $this->_data($periode != '' ? $periode : date('Y-m')),
            'periode' => $periode != '' ? $periode : date('Y-m'),
		));
	}

    private function _data($periode)
    {
        $data = $this->db->query("SELECT * 
                                FROM(
                                    SELECT 
                                        p.id, 
                                        p.kode,
                                        p.nama,
                                        p.id_tipe, 
                                        t.nama AS tipe,
                                        p.id_satuan,
                                        u.nama AS satuan,
                                        p.id_jenis,
                                        j.nama AS jenis,
                                        p.id_merek,
                                        m.nama AS merek, 
                                        IFNULL(sa.stokawal, 0) AS stokawal, 
                                        IFNULL(si.stokin, 0) AS stokin, 
                                        ABS(IFNULL(so.stokout, 0)) AS stokout, 
                                        ((IFNULL(sa.stokawal, 0) + IFNULL(si.stokin, 0)) - ABS(IFNULL(so.stokout, 0))) AS stokakhir
                                    FROM ref_produk p 
                                    JOIN ref_lookup t ON t.id = p.id_tipe
                                    JOIN ref_lookup u ON u.id = p.id_satuan
                                    JOIN ref_lookup j ON j.id = p.id_jenis 
                                    JOIN ref_lookup m ON m.id = p.id_merek 
                                    LEFT JOIN (
                                        SELECT id_produk, SUM(qty) AS stokawal
                                        FROM jstok 
                                        WHERE row_status = 1 AND DATE_FORMAT(tgl, '%Y-%m') < '$periode' 
                                        GROUP BY id_produk
                                    ) sa ON sa.id_produk = p.id
                                    LEFT JOIN (
                                        SELECT id_produk, SUM(qty) AS stokin
                                        FROM jstok 
                                        WHERE row_status = 1 AND DATE_FORMAT(tgl, '%Y-%m') = '$periode' AND qty > 0
                                        GROUP BY id_produk
                                    ) si ON si.id_produk = p.id
                                    LEFT JOIN (
                                        SELECT id_produk, SUM(qty) AS stokout
                                        FROM jstok 
                                        WHERE row_status = 1 AND DATE_FORMAT(tgl, '%Y-%m') = '$periode' AND qty < 0
                                        GROUP BY id_produk
                                    ) so ON so.id_produk = p.id
                                    WHERE p.row_status = 1 
                                    ORDER BY p.id_tipe, p.nama
                                ) a 
                                WHERE stokawal != 0 OR stokin != 0 OR stokout != 0 OR stokakhir != 0")->result_array();

        

        return $data;
    }

    public function ajax_detail($id_produk, $periode)
    {
        $src = $this->db->query("(SELECT 0 AS id, '' AS no_transaksi, '' AS tgl, '' AS dept, 'Saldo Awal' AS keterangan, '' AS masuk, 
                                    '' AS keluar,  
                                    IFNULL((SELECT SUM(qty) AS stokawal
                                        FROM jstok 
                                        WHERE row_status = 1 AND id_produk = $id_produk AND DATE_FORMAT(tgl, '%Y-%m') < '$periode'), 0) AS qty)
                                UNION 
                                (SELECT id, no_referensi AS no_transaksi, tgl, '' AS dept, jenis_trx AS keterangan, 
                                    IF(qty > 0, qty, 0) AS masuk, 
                                    ABS(IF(qty < 0, qty, 0)) AS keluar, 
                                    qty
                                FROM jstok
                                WHERE row_status = 1 AND id_produk = $id_produk AND DATE_FORMAT(tgl, '%Y-%m') = '$periode'
                                ORDER BY id)")->result();
        $data = array();                        
        $total = 0;
        foreach ($src as $row) {
            $total += $row->qty;
            $data[] = array(
                'no_transaksi' => $row->no_transaksi,
                'tgl' => $row->tgl,
                'dept' => $row->dept,
                'keterangan' => ucwords(str_replace('_', ' ', $row->keterangan)),
                'masuk' => number_format((int)$row->masuk, 0, "," ,"."),
                'keluar' => number_format((int)$row->keluar, 0, "," ,"."),
                'saldo' => number_format((int)$total, 0, "," ,"."),
            );
        }
        //print_r($data);
        echo json_encode($data);

        
    }

}