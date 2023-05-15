<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

    public function excel()
    {
        $data = $this->db->query("SELECT * FROM (
                            SELECT a.id, a.id_pegawai, p.nama AS pegawai, a.nama, a.model, a.tgl_pembelian, 
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
                        WHERE a.row_status = 1) a 
                        ORDER BY a.count_day_maintenance")->result();

        $spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$sheet->setCellValue('A1', 'DAFTAR ASSET');
        $sheet->mergeCells('A1:I1');
		$sheet->getStyle('A1')->getFont()->setSize(14)->setBold( true );
		$sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A3', 'No.');
		$sheet->setCellValue('B3', 'Nama Unit');
		$sheet->setCellValue('C3', 'Model');
		$sheet->setCellValue('D3', 'Tgl. Perolehan');
        $sheet->setCellValue('E3', 'Usia Asset');
        $sheet->setCellValue('F3', 'Periode Maintenance');
        $sheet->setCellValue('G3', 'Nama Pegawai');
        $sheet->setCellValue('H3', 'Tgl. Perawatan Terakhir');
        $sheet->setCellValue('I3', 'Waktu Maintenance');
		$sheet->getStyle('A3:I3')->getFont()->setBold( true );

        $row = 3;
        $no = 1;
        foreach ($data as $d){
            $row++;
            $sheet->setCellValue('A'.$row, $no++);
            $sheet->setCellValue('B'.$row, $d->nama);
            $sheet->setCellValue('C'.$row, $d->model);
            $sheet->setCellValue('D'.$row, $d->tgl_pembelian);
            $sheet->setCellValue('E'.$row, umur_bulan($d->tgl_pembelian));
            $sheet->setCellValue('F'.$row, $d->periode_maintenance);
            $sheet->setCellValue('G'.$row, $d->pegawai);
            $sheet->setCellValue('H'.$row, $d->tgl_maintenance);
            $sheet->setCellValue('I'.$row, $d->tgl_harus_maintenance . ' (' .  $d->count_day_maintenance . ' hari)');

            
        }

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' =>  \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ]
            ]
        ];

        
        $sheet->getStyle('A3:I'.$row)->applyFromArray($styleArray);
        foreach ($sheet->getColumnIterator() as $column) {
		   	$sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
		}


        $writer = new Xlsx($spreadsheet);
		
		if (ob_get_contents()) ob_end_clean();
		header( "Content-type: application/vnd.ms-excel" );
		header('Content-Disposition: attachment; filename="daftar-asset-'.date('ymdHi').'.xlsx"');
		header("Pragma: no-cache");
		header("Expires: 0");
		if (ob_get_contents()) ob_end_clean();
		$writer->save('php://output');
    }

}