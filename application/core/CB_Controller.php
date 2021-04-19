<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CB_Controller extends CI_Controller {

	protected $request = false;

	public function __construct() {

		parent::__construct();

		$request = $this->uri->uri_to_assoc(3);

		$this->db->insert('heavy_logger', [
			'user_if_any' => $this->session->user ? $this->session->user['email'] : null,
			'url' => current_url(),
			'querystring' => $this->input->get() ? serialize($this->input->get()) : null,
			'ipaddress' => $this->input->ip_address(),
			'useragent' => $this->input->user_agent(),
		]);

		if (!$this->session->user) {
			$this->session->redirect = $_SERVER['REDIRECT_QUERY_STRING'];
			redirect('/');
		} elseif (!isset($request['company'])) {
			die('Richiesta non valida!');
		} else {
			$company = $this->db
			->where('uri_name', $request['company'])
			->get('companies')->result_array();
			if ($company) {
				defined('_GLOBAL_COMPANY') OR define('_GLOBAL_COMPANY', $company[0]);
				defined('_COMPANY_URI') OR define('_COMPANY_URI', $company[0]['uri_name']);
				$this->request = $request;
			} else {
				die('URL non valido!');
			}
		}
		defined('_GLOBAL_USER') OR define('_GLOBAL_USER', $this->session->user);

	}
}
