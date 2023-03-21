<?php

function hash_password($password)
{
	return md5("POLLUX+{$password}+20042020");
}

function user_session($key)
{
	$ci =& get_instance();
	
	$pengguna = $ci->session->userdata('pengguna');
	
	if ( ! $pengguna) {
		return null;
	}
	else {
		return $pengguna->$key;
	}
}

function set_user_session($key, $new_value)
{
	$ci =& get_instance();
	
	$pengguna = $ci->session->userdata('pengguna');
	$pengguna->$key = $new_value;
	
	$ci->session->set_userdata('pengguna', $pengguna);
}

function db_insert($table, $data)
{
	$ci =& get_instance();
	$data['row_status'] = 1;
	$data['created_by'] = user_session('id');
	$ci->db->insert($table, $data);
	return $ci->db->insert_id();
}

function db_update($table, $data, $where)
{
	$ci =& get_instance();
	$data['updated_by'] = user_session('id');
	return $ci->db->update($table, $data, $where);
}

function db_delete($table, $where)
{
	$ci =& get_instance();
	$data['row_status'] = 0;
	$data['updated_by'] = user_session('id');
	return $ci->db->update($table, $data, $where);
}

function menu($id_pengguna_grup)
{
	$ci =& get_instance();
	
	$sql = "
		SELECT
			b.*
		FROM (
			SELECT *
			FROM pengguna_grup_menu
			WHERE
				row_status = 1
				AND id_pengguna_grup = ?
		) AS a
		JOIN menu AS b ON a.id_menu = b.id
		ORDER BY b.id_induk, b.urutan
	";
	$src = $ci->db->query($sql, array($id_pengguna_grup));
	
	$menu = array();
	
	foreach ($src->result() as $row) {
		$val = array (
			'ikon' => $row->ikon,
			'teks' => $row->teks,
			'uri' => $row->uri,
		);
		
		if ($row->id_induk == null) {
			$menu[$row->id] = $val;
		}
		else {
			$menu[$row->id_induk]['sub'][] = $val;
		}
	}
	
	return $menu;
}

function options($src, $id, $ref_val, $text_field, $data_attr = array())
{
	$options = '';
	
	foreach ($src->result() as $row) {
		
		$opt_value	= $row->$id;
		$text_value	= $row->$text_field;
		
		$data_attr_str = '';
		
		foreach ($data_attr as $class => $data_field) {
			$data_attr_str .= 'data-'.$class.'="'.$row->$data_field.'" ';
		}
		
		if ($row->$id == $ref_val) {
			$options .= '<option value="'.$opt_value.'" '.$data_attr_str.'selected>'.$text_value.'</option>';
		}
		else {
			$options .= '<option value="'.$opt_value.'" '.$data_attr_str.'>'.$text_value.'</option>';
		}
	}
	
	return $options;
}

function post_data($keys = array())
{
	$ci =& get_instance();
	
	$data = array();
	
	foreach ($keys as $key) {
		
		$key_arr = explode('|', $key);
		
		if (isset($key_arr[1])) {
			switch ($key_arr[1]) {
				case 'number': {
					$val = trim($ci->input->post($key_arr[0]));
					$val = str_replace('.', '', $val);
					break;
				}
			}
		}
		else {
			$val = trim($ci->input->post($key_arr[0]));
		}
		
		$data[$key_arr[0]] = $val;
	}
	
	return $data;
}

function status($raw_status)
{
	switch ($raw_status) {
		case 'open': $class = 'pill-open'; break;
		case 'closed': $class = 'pill-closed'; break;
		default: $class = '';
	}
	
	return '<span class="pill-status '.$class.'">'.strtoupper($raw_status).'</span>';
}

function angka($integer)
{
	if ($integer == '') return null;
	else return number_format($integer, 0, ',', '.');
}

function rupiah($angka, $show_null = false)
{
	if ((int) $angka == 0) return $show_null ? 'Rp0' : null;
	else return $angka >= 0 ? 'Rp'.angka($angka) : '( Rp'.angka(abs($angka)).' )';
}

function is_exists_email($email, $exclude_id = 0)
{
	$ci =& get_instance();
	
	$src = $ci->db
				->from('pengguna')
				->where('row_status', 1)
				->where('email', $email)
				->where('id != '.$exclude_id)
				->get();
	
	return $src->num_rows() == 0 ? 0 : 1;
}

function is_exists_username($username, $exclude_id = 0)
{
	$ci =& get_instance();
	
	if ($username == '') {
		return 0;
	}	
	
	$src = $ci->db
				->from('pengguna')
				->where('row_status', 1)
				->where('username', $username)
				->where('id != '.$exclude_id)
				->get();
	
	return $src->num_rows() == 0 ? 0 : 1;
}

#
# Fungsi ini dieksekusi sebelum update data urutan
#
function update_urutan($table, $new_order, $current_order = '', $type = 'insert')
{
	$ci =& get_instance();
	
	if ($type == 'insert') {
		$sql = "
			UPDATE {$table}
			SET urutan = urutan + 1
			WHERE
				row_status = 1
				AND urutan >= {$new_order}
		";
		$ci->db->query($sql);
	}
	
	if ($type == 'swap') {
		if ($new_order > $current_order) {
			$sql = "
				UPDATE {$table}
				SET urutan = urutan - 1
				WHERE
					row_status = 1
					AND urutan > {$current_order} AND urutan <= {$new_order}
			";
			$ci->db->query($sql);
		}
		
		if ($new_order < $current_order) {
			$sql = "
				UPDATE {$table}
				SET urutan = urutan + 1
				WHERE
					row_status = 1
					AND urutan >= {$new_order} AND urutan < {$current_order}
			";
			$ci->db->query($sql);
		}
	}
}

function new_number($kode)
{
	$ci =& get_instance();
	
	$nomor = $ci->db
		->from('no_transaksi')
		->where('kode', $kode)
		->get()
		->row();
	
	if ($nomor->tahun_sekarang == date('Y')) {
		if ($nomor->bulan_sekarang == date('m')) {
			$serial = $nomor->serial_berikutnya;
			$update = array('serial_berikutnya' => $serial + 1);
		}
		else {
			$update = array (
				'bulan_sekarang' => date('m'),
			);
			
			if ($nomor->reset_serial == 'bulanan') {
				$serial = 1;
				$update['serial_berikutnya'] = 2;
			}
			else {
				$serial = $nomor->serial_berikutnya;
				$update['serial_berikutnya'] = $serial + 1;
			}
		}
	}
	else {
		$serial = 1;
		$update = array (
			'tahun_sekarang' => date('Y'),
			'bulan_sekarang' => date('m'),
			'serial_berikutnya' => 2,
		);
	}
	
	$where = array('kode' => $kode);
	$ci->db->update('no_transaksi', $update, $where);
	
	$serial_str = str_pad($serial, $nomor->digit_serial, '0', STR_PAD_LEFT);
	
	$wildcard = array('#Y4#', '#Y2#', '#M#', '#SERIAL#');
	$replace = array(date('Y'), date('y'), date('m'), $serial_str);
	
	return str_replace($wildcard, $replace, $nomor->format);
}

function new_number_pengiriman($id_faktur)
{
	$ci =& get_instance();

	$faktur = $ci->db->where("id",$id_faktur)->get("faktur")->row();
	$count_pengiriman = $ci->db->where("id_faktur",$id_faktur)->select("count(*) x")->get("pengiriman")->row()->x;

	$counter = substr("000".($count_pengiriman + 1), -3);

	return  "P-".$faktur->no_transaksi."-".$counter;

}

function showRestResponse($data = null, $code = 200, $message = "Data berhasil Disimpan."){

	$response = array(
		"code" => $code,
		"message" => $message,
		"data" => $data,
	);

	return $response;
}

function option_periode($selected = ''){   
	$options =
		'<option value="MINGGU" '. ($selected == 'MINGGU' ? 'selected' : '') . '>MINGGU</option>'.
		'<option value="BULAN" '. ($selected == 'BULAN' ? 'selected' : '') . '>BULAN</option>';
	
	return $options;
}

function umur_bulan($tgl)
{
	$d1 = new DateTime(date('Y-m-d')); 
	$d2 = new DateTime($tgl);                                  
	$Months = $d2->diff($d1); 
	$diffMonth = (($Months->y) * 12) + ($Months->m);
	$txt = $diffMonth .' bulan';

	return $txt;
}

function buttonDelete($url)
{
	return '<a href="'.$url.'"><img src="'. base_url('assets/img/del.png') .'"></a>';
}

function penyebut($nilai) {
	$nilai = abs($nilai);
	$huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
	$temp = "";
	if ($nilai < 12) {
		$temp = " ". $huruf[$nilai];
	} else if ($nilai <20) {
		$temp = penyebut($nilai - 10). " belas";
	} else if ($nilai < 100) {
		$temp = penyebut($nilai/10)." puluh". penyebut($nilai % 10);
	} else if ($nilai < 200) {
		$temp = " seratus" . penyebut($nilai - 100);
	} else if ($nilai < 1000) {
		$temp = penyebut($nilai/100) . " ratus" . penyebut($nilai % 100);
	} else if ($nilai < 2000) {
		$temp = " seribu" . penyebut($nilai - 1000);
	} else if ($nilai < 1000000) {
		$temp = penyebut($nilai/1000) . " ribu" . penyebut($nilai % 1000);
	} else if ($nilai < 1000000000) {
		$temp = penyebut($nilai/1000000) . " juta" . penyebut($nilai % 1000000);
	} else if ($nilai < 1000000000000) {
		$temp = penyebut($nilai/1000000000) . " milyar" . penyebut(fmod($nilai,1000000000));
	} else if ($nilai < 1000000000000000) {
		$temp = penyebut($nilai/1000000000000) . " trilyun" . penyebut(fmod($nilai,1000000000000));
	}     
	return $temp;
}

function terbilang($nilai) {
	if($nilai<0) {
		$hasil = "minus ". trim($this->penyebut($nilai));
	} else {
		$hasil = trim(penyebut($nilai));
	}     		
	return $hasil." rupiah";
}
