<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
		$this->datatables->select("id, id_asset, nama, model, tgl_maintenance, keterangan")
                    ->from("(SELECT am.id, am.id_asset, a.nama, a.model, am.tgl_maintenance, am.keterangan
                            FROM asset_maintenance am 
                            JOIN asset a ON a.id = am.id_asset AND a.row_status = 1 
                            WHERE am.row_status = 1 ) a");

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

}