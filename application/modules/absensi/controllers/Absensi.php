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
		$cal = [];
		$sql = "SELECT x.id_pengguna, x.nama";

		foreach ($period as $dt) {
			$headerhari[] = days_indo($dt->format('D'));
			$header[] = $dt->format('Y-m-d');
			$cal[] = array(
				'tgl' => $dt->format('Y-m-d')
			);

			$sql .= ", MAX(CASE WHEN x.tgl = '".$dt->format('Y-m-d')."' THEN IFNULL(a.`status`, '-') END) AS '".$dt->format('Y-m-d')."'";
		}

		$sql .= "FROM (
					SELECT k.tgl, p.id AS id_pengguna, p.nama
					FROM kalender k 
					JOIN pengguna p 
					WHERE p.row_status = 1
				) x 
				LEFT JOIN absensi a ON a.tgl = x.tgl AND a.id_pengguna = x.id_pengguna
				GROUP BY x.id_pengguna, x.nama
				ORDER BY x.nama";

		$this->db->truncate('kalender');
		$this->db->insert_batch('kalender', $cal);

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

	
}
