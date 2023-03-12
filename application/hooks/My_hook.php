<?php

function session_check()
{
	$ci =& get_instance();
	
	if ( ! user_session('id')) {
		$cookie = $ci->input->cookie('makmurpermai_cookie');
		
		$src = $ci->db
			->select('b.id, b.nama, b.email, b.username, b.id_pengguna_grup, c.nama as grup_pengguna')
			->from("(
				select *
				from pengguna_cookie
				where
					row_status = 1
					and cookie = '{$cookie}'
					and now() <= tgl_kadaluarsa
			) as a")
			->join('pengguna as b', 'a.id_pengguna = b.id')
			->join('pengguna_grup as c', 'b.id_pengguna_grup = c.id')
			->get();
		
		if ($src->num_rows() == 1) {
			$pengguna = $src->row();
			$nama_usaha = $ci->db->get_where('profil', array('id' => 1))->row('nama');
			
			$ci->session->set_userdata('pengguna', $pengguna);
			$ci->session->set_userdata('nama_usaha', $nama_usaha);
		}
	}
	
	$controller = strtolower($ci->uri->segment(1));
	$method = strtolower($ci->uri->segment(2));
	
	$uri = strtolower($ci->uri->uri_string());
	
	$excluded_uri = array (
		'pengguna/autentikasi',
	);
	
	$is_excluded = false;
	$is_session_check = false;
	
	foreach ($excluded_uri as $exc) {
		if (strpos($uri, $exc) !== false) {
			$is_excluded = true;
		}
	}
	
	if ($controller == '' or $controller == 'site') {
		if (user_session('id')) {
			redirect(site_url('dasbor'));
		}
	}
	else if ($is_excluded) {
		$is_session_check = false;
	}
	else {
		$is_session_check = true;
	}
	
	if ($is_session_check) {
		if ( ! user_session('id')) {
			redirect(site_url());
		}
	}
}
