<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends CB_Controller {

	public function __construct() {

		parent::__construct();

	}

	public function index() {
		$this->ordini();
	}

	public function ordini() {
		$this->load->view('orders_manager', $this->pizzeria->menu_components());
	}

}
