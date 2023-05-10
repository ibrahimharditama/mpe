<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
                                    f.tgl,
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
                                WHERE f.row_status = 1 
                                ORDER BY plg.nama")->result_array();

        $data = array();
        
        foreach ($src as $row) {

            if($row['sisa'] <= 0) continue;

            $data[$row['kode_pelanggan'] .' - '. $row['pelanggan']][] = array(
                'id' => $row['id'],
                'no_transaksi' => $row['no_transaksi'],
                'tgl' => $row['tgl'],
                'total' => $row['total'],
                'bayar' => $row['bayar'],
                'sisa' => $row['sisa'],
            );
        }

        return $data;


    }

    public function excel()
    {
        $data = $this->_data();

        $spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$sheet->setCellValue('A1', 'LAPORAN HUTANG');
        $sheet->mergeCells('A1:D1');
		$sheet->getStyle('A1')->getFont()->setSize(14)->setBold( true );
		$sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A3', 'No. Transaksi');
		$sheet->setCellValue('B3', 'Tanggal');
		$sheet->setCellValue('C3', 'Nominal');
		$sheet->setCellValue('D3', 'Sisa');
		$sheet->getStyle('A3:D3')->getFont()->setBold( true );

        $row = 4;
        $total = 0; 
        $sisa = 0;

        foreach ($data as $key => $value){
            $total_tagihan = 0;
            $sisa_tagihan = 0;

            $sheet->setCellValue('A'.$row, $key);
			$sheet->mergeCells('A'.$row.':D'.$row);
			$sheet->getStyle('A'.$row)->getFont()->setBold( true );
			$row++;

            foreach ($data[$key] as $index => $r){
                $total_tagihan += $data[$key][$index]['total'];
                $sisa_tagihan += $data[$key][$index]['sisa'];

                $sheet->setCellValue('A'.$row, $data[$key][$index]['no_transaksi']);
                $sheet->setCellValue('B'.$row, $data[$key][$index]['tgl']);
                $sheet->setCellValue('C'.$row, $data[$key][$index]['total']);
                $sheet->setCellValue('D'.$row, $data[$key][$index]['sisa']);

                $sheet->getStyle('C'.$row.':D'.$row)->getNumberFormat()->setFormatCode('#,##0');
                $row++;
            }

            
            $sheet->setCellValue('A'.$row, 'Sub Total:');
            $sheet->mergeCells('A'.$row.':B'.$row);
            $sheet->setCellValue('C'.$row, $total_tagihan);
            $sheet->setCellValue('D'.$row, $sisa_tagihan);

            $sheet->getStyle('A'.$row.':D'.$row)->getFont()->setBold( true );
            $sheet->getStyle('A'.$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle('C'.$row.':D'.$row)->getNumberFormat()->setFormatCode('#,##0');
            $row++;

            $total += $total_tagihan; 
            $sisa += $sisa_tagihan;
        }

        
        $sheet->setCellValue('A'.$row, 'Total');
        $sheet->mergeCells('A'.$row.':B'.$row);
        $sheet->setCellValue('C'.$row, $total);
        $sheet->setCellValue('D'.$row, $sisa);

        $sheet->getStyle('A'.$row.':D'.$row)->getFont()->setBold( true );
        $sheet->getStyle('C'.$row.':D'.$row)->getNumberFormat()->setFormatCode('#,##0');

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' =>  \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ]
            ]
        ];

        
        $sheet->getStyle('A3:D'.$row)->applyFromArray($styleArray);
        foreach ($sheet->getColumnIterator() as $column) {
		   	$sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
		}


        $writer = new Xlsx($spreadsheet);
		
		if (ob_get_contents()) ob_end_clean();
		header( "Content-type: application/vnd.ms-excel" );
		header('Content-Disposition: attachment; filename="laporan-hutang-'.date('ymdHi').'.xlsx"');
		header("Pragma: no-cache");
		header("Expires: 0");
		if (ob_get_contents()) ob_end_clean();
		$writer->save('php://output');
    }

}