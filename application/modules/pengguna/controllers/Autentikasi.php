<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Autentikasi extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->form_validation->CI =& $this;
	}
	
	public function login()
	{
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		$remember = $this->input->post('remember');
		
		$password = hash_password($password);
		$src = $this->db
			->select('id, nama, email, username, id_pengguna_grup')
			->from('pengguna')
			->where('row_status', 1)
			->where("(email = '{$email}' OR username = '{$email}')")
			->where('username IS NOT NULL')
			->where('password', $password)
			->get();
		
		if ($src->num_rows() == 0) {
			$this->session->set_flashdata('post_status', 'err');
			redirect(site_url());
		}
		else {
			$pengguna = $src->row();
			
			$grup_pengguna = $this->db
				->get('pengguna_grup', array('row_status' => 1, 'id' => $pengguna->id_pengguna_grup))
				->row('nama');
			
			$pengguna->grup_pengguna = $grup_pengguna;
			
			if ($remember) {
				$cookie_value = md5('makmurpermai-cookie-'.date('YmdHis').'-'.mt_rand(100, 300));
				$duration = 360;
				
				$cookie = array (
					'name' => 'makmurpermai_cookie',
					'value' => $cookie_value,
					'expire' => $duration * 24 * 3600,
				);
				
				$this->input->set_cookie($cookie);
				
				$cookie_data = array (
					'id_pengguna' => $pengguna->id,
					'cookie' => $cookie_value,
					'ip_address' => $this->input->ip_address(),
					'tgl_kadaluarsa' => date('Y-m-d', strtotime(date('Y-m-d').' +'.$duration.' days')),
				);
				
				$this->db->insert('pengguna_cookie', $cookie_data);
			}
			
			$nama_usaha = $this->db->get_where('profil', array('id' => 1))->row('nama');
			
			$this->session->set_userdata('pengguna', $pengguna);
			$this->session->set_userdata('nama_usaha', $nama_usaha);
			
			redirect(site_url('dasbor'));
		}
	}
	
	public function current_password($password)
	{
		$password = hash_password($password);
		
		$src = $this->db
			->from('pengguna')
			->where('row_status', 1)
			->where('id', user_session('id'))
			->where('password', $password)
			->get();
		
		if ($src->num_rows() == 0) {
			$this->form_validation->set_message('current_password', '<b>%s</b> salah.');
			return false;
		}
		else {
			return true;
		}
	}
	
	public function new_password()
	{
		$rules = array (
			array (
				'field' => 'current_password',
				'label' => 'Password Sekarang',
				'rules' => 'required|callback_current_password',
			),
			array (
				'field' => 'new_password',
				'label' => 'Password Baru',
				'rules' => 'required',
			),
		);
		
		$this->form_validation->set_rules($rules);
		
		if ($this->form_validation->run()) {
			
			$new_password = hash_password($this->input->post('new_password'));
			
			$this->db->update('pengguna', array('password' => $new_password), array('id' => user_session('id')));
			
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
		
		redirect(site_url('pengguna/ubah-password'));
	}
	
	public function logout()
	{
		$this->session->unset_userdata('pengguna');
		$this->session->sess_destroy();
		
		delete_cookie('makmurpermai_cookie');
		
		redirect(site_url());
	}
}
