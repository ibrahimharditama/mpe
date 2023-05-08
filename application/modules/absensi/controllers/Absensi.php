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

		$header = ['Pegawai'];
		$cal = [];
		$sql = "SELECT x.id_pengguna, x.nama";

		foreach ($period as $dt) {
			$header[] = $dt->format('j');
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

		$data = array(
			'header' =>  $header,
			'body' => $src,
		);

		return $data;
	}

	public function approve()
	{
		$input = $this->input->post();

		$data = array(
			'status' => $input['status'],
			'approved_at' => date('Y-m-d H:i:s'),
			'approved_by'=> user_session('id')
		);

		$this->db->update('absensi', $data, [
			'id_pengguna' => $input['id_pengguna'],
			'tgl' => $input['tgl'],
		]);

		echo 'ok';
	}

	
}
