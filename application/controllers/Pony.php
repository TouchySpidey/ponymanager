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
		if (empty($delivery)) {
			echo 'Per motivi di sicurezza questo QR è scaduto';
		} else {
			echo '<script>window.open(\'https://www.google.com/maps/search/?api=1&query='.$delivery[0]['north'].','.$delivery[0]['east'].'\')</script>';
		}
	}

}
