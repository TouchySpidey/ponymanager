<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pony extends CI_Controller {

	public function __construct() {

		parent::__construct();

	}

	public function index() {
		echo 'ciao!';
	}

	public function qr($cod_delivery, $guid) {
		$delivery = $this->db
		->where('id_delivery', $cod_delivery)
		->where('guid', $guid)
		->get('deliveries')->result_array();
		debug($delivery);
		$this->load->view('pony');
	}

}
