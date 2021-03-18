<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	public function index()
	{
		$this->ordini();
	}

	public function ordini() {
		$this->load->view('customer_finder');
	}

	public function aiuda() {
		debug(FCPATH);
		debug(site_url());
		debug($_SERVER['SERVER_NAME']);
	}

}
