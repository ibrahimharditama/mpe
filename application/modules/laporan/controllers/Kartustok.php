<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Kartustok extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->form_validation->CI =& $this;
	}
	
	public function index($periode = '',$nama = '')
	{   
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $periode = $this->input->post("periode");
            $nama = $this->input->post("nama_cari");
        }
		$this->load->view('templates/app_tpl', array (
			'content' => 'kartustok_index',
            'data' => $this->_data($periode != '' ? $periode : date('Y-m'),$nama != '' ? $nama : ''),
            'periode' => $periode != '' ? $periode : date('Y-m'),
            'nama' => $nama != '' ? $nama : ''
		));
	}

    private function _data($periode,$nama)
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
                                    WHERE p.row_status = 1 and t.nama != 'Jasa' and LOWER(p.nama) LIKE  LOWER('%".$nama."%')
                                    ORDER BY p.id_tipe, p.nama
                                ) a 
                                WHERE stokawal != 0 OR stokin != 0 OR stokout != 0 OR stokakhir != 0 
                                ORDER BY nama")->result_array();

        

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
                'masuk' => $row->keterangan != 'Saldo Awal' ? number_format((int)$row->masuk, 0, "," ,".") : '',
                'keluar' => $row->keterangan != 'Saldo Awal' ? number_format((int)$row->keluar, 0, "," ,".") : '',
                'saldo' => number_format((int)$total, 0, "," ,"."),
            );
        }
        //print_r($data);
        echo json_encode($data);

        
    }

    public function excel()
    {   
        $periode = date('Y-m');
        $nama = '';

        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $periode = $this->input->post("periode");
            $nama = $this->input->post("nama_cari");
        }

        $data = $this->_data($periode,$nama);

        $spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$sheet->setCellValue('A1', 'KARTU STOK PERIODE '.$periode);
        $sheet->mergeCells('A1:G1');
		$sheet->getStyle('A1')->getFont()->setSize(14)->setBold( true );
		$sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A3', 'Kode Produk');
        $sheet->mergeCells('A3:A4');
		$sheet->setCellValue('B3', 'Nama Produk');
        $sheet->mergeCells('B3:B4');
		$sheet->setCellValue('C3', 'STOK');
        $sheet->mergeCells('C3:G3');

		$sheet->setCellValue('C4', 'Satuan');
        $sheet->setCellValue('D4', 'Awal');
        $sheet->setCellValue('E4', 'Masuk');
        $sheet->setCellValue('F4', 'Keluar');
        $sheet->setCellValue('G4', 'Akhir');

		$sheet->getStyle('A3:G4')->getFont()->setBold( true );
        $sheet->getStyle('A3:G4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $row = 4;
        

        foreach ($data as $d){
            $row++;
            $sheet->setCellValue('A'.$row, $d['kode']);
            $sheet->setCellValue('B'.$row, $d['nama'].' - '.$d['jenis'].' - '.$d['merek']);
            $sheet->setCellValue('C'.$row, $d['satuan']);
            $sheet->setCellValue('D'.$row, $d['stokawal']);
            $sheet->setCellValue('E'.$row, $d['stokin']);
            $sheet->setCellValue('F'.$row, $d['stokout']);
            $sheet->setCellValue('G'.$row, $d['stokakhir']);
            

            
        }

        $sheet->getStyle('D5:G'.$row)->getNumberFormat()->setFormatCode('#,##0');

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' =>  \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ]
            ]
        ];

        
        $sheet->getStyle('A3:G'.$row)->applyFromArray($styleArray);
        foreach ($sheet->getColumnIterator() as $column) {
		   	$sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
		}


        $writer = new Xlsx($spreadsheet);
		
		if (ob_get_contents()) ob_end_clean();
		header( "Content-type: application/vnd.ms-excel" );
		header('Content-Disposition: attachment; filename="kartu-stok-'.date('ymdHi').'.xlsx"');
		header("Pragma: no-cache");
		header("Expires: 0");
		if (ob_get_contents()) ob_end_clean();
		$writer->save('php://output');
    }

}