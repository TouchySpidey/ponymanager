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
