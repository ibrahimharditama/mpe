<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Perusahaan extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->form_validation->CI =& $this;
	}
	
	public function index()
	{
		$data = $this->db->get_where('profil', array('id' => 1))->row_array();
		
		$this->load->view('templates/app_tpl', array (
			'content' => 'perusahaan_index',
			'data' => $data,
		));
	}
	
	public function update()
	{
		$rules = array (
			array (
				'field' => 'nama',
				'label' => 'Nama Usaha',
				'rules' => 'required',
			),
			array (
				'field' => 'email',
				'label' => 'Email',
				'rules' => 'valid_email',
			),
		);
		
		$this->form_validation->set_rules($rules);
		
		if ($this->form_validation->run()) {
			
			$key = array('nama', 'alamat', 'no_telp', 'email', 'website');
			$data = post_data($key);
			
			$this->db->update('profil', $data, array('id' => 1));
			$this->session->set_userdata('nama_usaha', $data['nama']);
			
			$this->session->set_flashdata('post_status', 'ok');
		}
		else {
			
			foreach ($rules as $r) {
				$errors[$r['field']] = form_error($r['field']);
			}
			
			$this->session->set_flashdata('post_status', 'err');
			$this->session->set_flashdata('errors', $errors);
			$this->session->set_flashdata('data', $this->input->post());
		}
		
		redirect(site_url('pengaturan/perusahaan'));
	}
}
