<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Absensi extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->form_validation->CI =& $this;
	}
	
	public function index($date = '')
	{
		if($date == '') $date = date('Y-m');

		$this->load->view('templates/app_tpl', array (
			'content' => 'absensi_index',
			'periode' => str_replace('_', '-', $date),
			'data' => $this->_data(str_replace('_', '-', $date)),
		));
	}
	
	private function _data($date)
	{
		$date = str_replace('_', '-', $date);
		$start = date('Y-m-01', strtotime($date.'-01'));
		$end = date('Y-m-t', strtotime($date.'-01'));

		$startDate = new DateTime(str_replace('_', '-', $start));
		$endDate = new DateTime(str_replace('_', '-', $end));

		$interval = DateInterval::createFromDateString('1 day');
		$period = new DatePeriod($startDate, $interval, $endDate->modify('+1 day'));

		$headerhari = [''];
		$header = [];
		$sql = "SELECT x.id AS id_pengguna, x.nama";

		foreach ($period as $dt) {
			$headerhari[] = days_indo($dt->format('D'), 1);
			$header[] = $dt->format('Y-m-d');

			$sql .= ", MAX(IF(a.tgl = '".$dt->format('Y-m-d')."' AND a.`status` IS NOT NULL, a.`status`, '-')) AS '".$dt->format('Y-m-d')."'";
		}

		$sql .= "FROM pengguna x
				LEFT JOIN absensi a ON a.id_pengguna = x.id AND a.row_status = 1
				GROUP BY x.id, x.nama
				ORDER BY x.nama";

		$src = $this->db->query($sql)->result();


		#hari libur
		$src_libur = $this->db->query("SELECT tgl FROM tanggal WHERE date_format(tgl, '%Y-%m') = '". $date. "' ")->result();
		$libur = [];
		foreach ($src_libur as $key => $value) {
			$libur[] = $value->tgl;
		}

		$data = array(
			'headerhari' => $headerhari,
			'header' => $header,
			'body' => $src,
			'libur' => $libur,
		);

		return $data;
	}

	public function approve()
	{
		$input = $this->input->post();

		$data = array(
			'status' => $input['status'] != '-' ? $input['status'] : 1,
			'approved_at' => date('Y-m-d H:i:s'),
			'approved_by'=> user_session('id')
		);


		if($input['status'] == '-') {

			$data['id_pengguna'] = $input['id_pengguna'];
			$data['tgl'] = $input['tgl'];
			$data['masuk'] = '08:00:59';
			$data['created_at'] = date('Y-m-d H:i:s');
			$data['created_by'] = user_session('id');

			$this->db->insert('absensi', $data);

		} else {

			$this->db->update('absensi', $data, [
				'id_pengguna' => $input['id_pengguna'],
				'tgl' => $input['tgl'],
			]);

		}

		echo 'ok';
	}

	public function ins_libur() 
	{
		$input = $this->input->post();
		$tgl = $input['tgl'];
		$is_libur = $input['is_libur'] == 'no' ? true : false;

		if($is_libur) {
			$this->db->insert('tanggal', ['tgl' => $tgl, 'keterangan' => 'hari libur']);
		} else {
			$this->db->delete('tanggal', ['tgl' => $tgl]);
		}

		echo json_encode($is_libur);


	}

	public function test()
	{
		$id_pengguna = 1;
		$date = 2023;
		
		$src = $this->db->query("SELECT v.tanggal, IFNULL(a.`status`,0) AS absen
								FROM (
									SELECT ADDDATE('1970-01-01', t4.i*10000 + t3.i*1000 + t2.i*100 + t1.i*10 + t0.i) AS tanggal 
									FROM 
										(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0,
										(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1,
										(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2,
										(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3,
										(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4
								) v 
								LEFT JOIN absensi a ON a.tgl = v.tanggal 
									AND a.row_status = 1 
									AND a.id_pengguna = $id_pengguna
								WHERE YEAR(v.tanggal) = $date
								")->result();

		$src_libur = $this->db->query("SELECT tgl FROM tanggal WHERE year(tgl) = '". $date. "' ")->result();
		$hr_libur = [];
		foreach ($src_libur as $key => $value) {
			$hr_libur[] = $value->tgl;
		}
								
		$data = array();

		for ($i=1; $i <= 12 ; $i++) { 
			$bulan = strtolower(num_to_month($i));
			$data[$bulan]['total_hari'] = 0;
			$data[$bulan]['total_hadir'] = 0;
		}
		
		foreach ($src as $row) {
			$bulan = strtolower(num_to_month(date('n', strtotime($row->tanggal))));

			#total hari kerja dikurangi hari minggu dan hari libur
			if(date('N',strtotime($row->tanggal)) != 7) {
				$data[$bulan]['total_hari'] += ($ke = array_search($row->tanggal, $hr_libur)) !== FALSE ? 0 : 1;
			}

			#total kehadiran
			$data[$bulan]['total_hadir'] += $row->absen;

			#status kehadiran
			$status = $row->absen == 1 ? 'MASUK' : 'ALPHA';

			if($row->absen == 0) {
				if(date('N',strtotime($row->tanggal)) == 7) $status = 'LIBUR';
				if(($ke = array_search($row->tanggal, $hr_libur)) !== FALSE) $status = 'LIBUR';
			}


			$data[$bulan]['absensi'][] = array(
				'tanggal' => $row->tanggal,
				'status' => $status,
			);
		}

		print_r($data);
	}

	
}
