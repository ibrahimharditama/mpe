<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengguna extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->form_validation->CI =& $this;
	}
	
	public function profil()
	{
		$data = array (
			'nama' => user_session('nama'),
			'email' => user_session('email'),
			'username' => user_session('username'),
			'id_pengguna_grup' => user_session('id_pengguna_grup'),
			'grup_pengguna' => user_session('grup_pengguna'),
		);
		
		$this->load->view('templates/app_tpl', array (
			'content' => 'pengguna_profil',
			'data' => $data,
		));
	}
	
	public function check_username($username)
	{
		if (is_exists_username($username, user_session('id'))) {
			$this->form_validation->set_message('check_username', '<b>%s</b> sudah digunakan. Mohon ganti dengan username lain.');
			return false;
		}
		else {
			return true;
		}
	}
	
	public function update_profile()
	{
		$rules = array (
			array (
				'field' => 'nama',
				'label' => 'Nama Lengkap',
				'rules' => 'required',
			),
			array (
				'field' => 'username',
				'label' => 'Username',
				'rules' => 'required|callback_check_username',
			),
		);
		
		$this->form_validation->set_rules($rules);
		
		if ($this->form_validation->run()) {
			
			$pengguna = array (
				'nama' => $this->input->post('nama'),
				'username' => $this->input->post('username'),
			);
			
			$this->db->update('pengguna', $pengguna, array('id' => user_session('id')));
			
			set_user_session('nama', $pengguna['nama']);
			set_user_session('username', $pengguna['username']);
			
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
		
		redirect(site_url('pengguna/profil'));
	}
	
	public function ubah_password()
	{
		$this->load->view('templates/app_tpl', array (
			'content' => 'pengguna_ubah_password',
		));
	}
}
