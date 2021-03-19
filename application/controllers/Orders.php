<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends CI_Controller {

	public function index()
	{
		$this->ordini();
	}

	public function ordini() {
		$this->load->view('orders_manager');
	}

}
