<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Company extends CB_Controller {

	public function __construct() {

		parent::__construct();

	}

	public function index() {
		$this->manage();
	}

	public function manage() {
		$this->load->view('manage_company');
	}

	public function payments() {
		if ($_payments = $this->input->post('newPayments')) {
			$this->company_model->set_payments($_payments);
		}
		echo JSON_encode($this->company_model->get_payments());
	}

	public function shifts() {
		if ($shifts = $this->input->post('newShifts')) {
			$_valid = true;
			$_shifts = [];
			foreach ($shifts as $shift) {
				$_shift = [
					'from' => sanifica_orario($shift['from']),
					'to' => sanifica_orario($shift['to']),
				];
				if (in_array(false, $_shift)) {
					$_valid = false;
				} else {
					$_shifts[] = $_shift;
				}
			}
			if ($_valid) {
				$this->company_model->set_shifts($_shifts);
			}
		}
		echo JSON_encode($this->company_model->get_shifts());
	}

	public function add_new() {
		// $this->load->
	}

	/*
		un utente può registrarsi, accedere, creare pizzerie
			> quando la crea ne diventa il master
		puoi dare privilegi su una pizzeria ad altri utenti
			> ma nessun utente può mai smuovere il master, né gli altri amministratori
		un utente tipicamente pony può anche non registrarsi mai
	*/

}
