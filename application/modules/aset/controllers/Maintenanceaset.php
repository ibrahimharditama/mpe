<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Maintenanceaset extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->form_validation->CI =& $this;
        $this->load->library('datatables');
	}
	
	public function index()
	{
		$this->load->view('templates/app_tpl', array (
			'content' => 'maintenance_aset_index',
		));
	}

    public function datatable()
	{
		$this->datatables->select("id, id_asset, nama, model, tgl_maintenance, keterangan, pegawai")
                    ->from("(SELECT am.id, am.id_asset, a.nama, a.model, am.tgl_maintenance, am.keterangan, p.nama AS pegawai
                            FROM asset_maintenance am 
                            JOIN asset a ON a.id = am.id_asset AND a.row_status = 1 
							JOIN pengguna p ON p.id = a.id_pegawai
                            WHERE am.row_status = 1 ) x");

        $result = json_decode($this->datatables->generate());

        $response['datatable'] = $result;
        $response['draw'] =  $result->draw;
        $response['recordsTotal'] =  $result->recordsTotal;
        $response['recordsFiltered'] =  $result->recordsFiltered;

		echo json_encode($response);
	}

    private function _form_data()
	{
		$rules = array (
			array (
				'field' => 'id_asset',
				'label' => 'Nama Unit',
				'rules' => 'required',
			),
			array (
				'field' => 'tgl_maintenance',
				'label' => 'Tgl. Perawatan',
				'rules' => 'required',
			),
		);
		
		$this->form_validation->set_rules($rules);
		
		if ($this->form_validation->run()) {
			
			$key = array('id_asset', 'tgl_maintenance', 'keterangan');
			$data = post_data($key);
			
			
			return showRestResponse($data);
		}
		else {
			
			foreach ($rules as $r) {
				$errors[$r['field']] = form_error($r['field']);
			}
			
			return showRestResponse($errors, 400, 'Validation Error');;
		}
	}

    public function insert()
	{
		$response = $this->_form_data();
		$response['url'] = '';
		
		if ($response['code'] == 200) {
			$data = $response['data'];		
			db_insert('asset_maintenance', $data);
			$response['url'] = base_url().'aset/maintenanceaset';
            $this->session->set_flashdata('post_status', 'ok');
			
		}

		echo json_encode($response);
	}

    public function update()
	{
		$response = $this->_form_data();
		$response['url'] = '';
		
		if ($response['code'] == 200) {
			$data = $response['data'];		
			db_update('asset_maintenance', $data, ['id' => $this->input->post('id')]);
			$response['url'] = base_url().'aset/maintenanceaset';
            $this->session->set_flashdata('post_status', 'ok');
			
		}

		echo json_encode($response);
	}

    public function delete($id)
	{
		if ( ! $this->agent->referrer()) {
			show_404();
		}

		db_delete('asset_maintenance', ['id' => $id]);
		redirect($this->agent->referrer());
	}

	public function excel()
    {
		$data = $this->db->query("SELECT am.id, am.id_asset, a.nama, a.model, am.tgl_maintenance, 
								am.keterangan, p.nama AS pegawai
								FROM asset_maintenance am 
								JOIN asset a ON a.id = am.id_asset AND a.row_status = 1 
								JOIN pengguna p ON p.id = a.id_pegawai
								WHERE am.row_status = 1 
								ORDER BY am.tgl_maintenance DESC")->result();

        $spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$sheet->setCellValue('A1', 'MAINTENANCE ASSET');
        $sheet->mergeCells('A1:F1');
		$sheet->getStyle('A1')->getFont()->setSize(14)->setBold( true );
		$sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A3', 'No.');
		$sheet->setCellValue('B3', 'Nama Unit');
		$sheet->setCellValue('C3', 'Model');
		$sheet->setCellValue('D3', 'Pegawai');
        $sheet->setCellValue('E3', 'Tgl. Perawatan');
        $sheet->setCellValue('F3', 'Keterangan');
		$sheet->getStyle('A3:F3')->getFont()->setBold( true );

        $row = 3;
        $no = 1;
        foreach ($data as $d){
            $row++;
            $sheet->setCellValue('A'.$row, $no++);
            $sheet->setCellValue('B'.$row, $d->nama);
            $sheet->setCellValue('C'.$row, $d->model);
            $sheet->setCellValue('D'.$row, $d->pegawai);
            $sheet->setCellValue('E'.$row, $d->tgl_maintenance);
            $sheet->setCellValue('F'.$row, $d->keterangan);

            
        }

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' =>  \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ]
            ]
        ];

        
        $sheet->getStyle('A3:F'.$row)->applyFromArray($styleArray);
        foreach ($sheet->getColumnIterator() as $column) {
		   	$sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
		}


        $writer = new Xlsx($spreadsheet);
		
		if (ob_get_contents()) ob_end_clean();
		header( "Content-type: application/vnd.ms-excel" );
		header('Content-Disposition: attachment; filename="maintenance-asset-'.date('ymdHi').'.xlsx"');
		header("Pragma: no-cache");
		header("Expires: 0");
		if (ob_get_contents()) ob_end_clean();
		$writer->save('php://output');
    }

}