<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	public function __construct() {
		parent::__construct();

		if ($this->session->user) {
			redirect('/orders');
		}
	}
	
	public function index() {
		$this->login();
	}

	public function login() {
		if (($email = $this->input->post('email')) && ($password = $this->input->post('password'))) {
			$email = trim($email);
			$salted = $password . LOGIN_SALT;
			$hash = hash('sha256', $salted);
			$user = $this->db
			->where('email', $email)
			->where('password', $hash)
			->get('users')->result_array();
			if (!empty($user)) {
				$this->session->user = $user[0];
				$company = $this->db
				->where('id_company', $user['cod_company'])
				->get('companies')->result_array();
				if (empty($company)) {

				} else {
					$this->session->company = $company[0];
				}
				$this->db->insert('heavy_logger', [
					'user_if_any' => $this->session->user ? $this->session->user['email'] : null,
					'url' => current_url(),
					'querystring' => $this->input->get() ? serialize($this->input->get()) : null,
					'ipaddress' => $this->input->ip_address(),
					'useragent' => $this->input->user_agent(),
				]);
				if ($redirect = $this->session->redirect) {
					$this->session->unset_userdata('redirect');
					redirect($redirect);
				}
				redirect('/orders');
			}
		}
		$this->load->view('login', ['email' => $email]);
	}

	public function reset_password($guid = false) {
		if ($guid) {
			$error = false;
			if (
				($email = $this->input->post('email'))
				&&
				($password = $this->input->post('password'))
				&&
				($c_password = $this->input->post('c_password'))
			) {
				$email = trim($email);
				if ($password != $c_password) {
					$error = 'Le password devono coincidere';
				} else {
					$salted = $guid . LOGIN_SALT;
					$hash = hash('sha256', $salted);
					$user = $this->db
					->where('email', $email)
					->where('reset_token', $hash)
					->get('users')->result_array();
					if (empty($user)) {
						$error = 'Il link è scaduto o l\'email non è corretta.';
					} else {
						$user = $user[0];
						if (time() < strtotime($user['forgot_time'].' +2 hours')) {
							$user['reset_token'] = null;
							$user['forgot_time'] = null;
							$salted = $password . LOGIN_SALT;
							$hash = hash('sha256', $salted);
							$user['password'] = $hash;
							$this->db->replace('users', $user);
							$this->session->user = $user;
							$company = $this->db
							->where('id_company', $user['cod_company'])
							->get('companies')->result_array();
							if (empty($company)) {

							} else {
								$this->session->company = $company[0];
							}
							redirect('/orders');
						} else {
							$error = 'Il link è scaduto o l\'email non è corretta';
						}
					}
				}
			}
			$this->load->view('password_reset', compact('guid', 'email', 'error'));
		} else {
			redirect('/');
		}
	}

	public function forgot() {
		if ($email = $this->input->post('email')) {
			$user = $this->db
			->where('email', $email)
			->get('users')->result_array();
			if (!empty($user)) {
				$user = $user[0];
				$reset_token = generate_guid();
				$user['reset_token'] = hash('sha256', $reset_token . LOGIN_SALT);
				$user['forgot_time'] = date('Y-m-d H:i:s');
				$this->db->replace('users', $user);
				$this->load->library('email');
				$this->email->initialize([
					'mailtype' => 'html'
				]);
				$this->email->from('no_reply@ponymanager.com', 'PonyManager');
				$this->email->to($email);
				$this->email->subject('Recupero Password');

				$vars = [
					'name' => $user['first_name'],
					'reset_token' => $reset_token,
				];

				$this->email->message($this->load->view('_mail_password_reset', $vars, TRUE));
				$this->email->send();
			}
		}
		$this->load->view('forgot', ['email' => $email]);
	}

}
