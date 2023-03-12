<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Site extends MX_Controller {

	public function index()
	{
		$this->load->view('templates/site_tpl', array (
			'content' => 'site_index',
			'bg_class' => 'bg-login',
		));
	}
}
