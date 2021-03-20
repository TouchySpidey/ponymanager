<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CB_Controller extends CI_Controller {

	public function __construct() {

		parent::__construct();

		if (!$this->session->user) {
			$this->session->redirect = $_SERVER['REDIRECT_QUERY_STRING'];
			redirect('/');
		} elseif (!$this->session->company) {
			$company = $this->db
			->where('id_company', $this->session->user['cod_company'])
			->get('companies')->result_array();
			if (empty($company)) {
				die('Utente non abilitato!');
			} else {
				# tutto ok
				$this->session->company = $company[0];
			}
		}
		defined('_GLOBAL_USER') OR define('_GLOBAL_USER', $this->session->user);
		defined('_GLOBAL_COMPANY') OR define('_GLOBAL_COMPANY', $this->session->company);

	}
}
