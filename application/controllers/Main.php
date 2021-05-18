<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	public function __construct() {
		parent::__construct();
		if ($this->session->user) {
			defined('_GLOBAL_USER') OR define('_GLOBAL_USER', $this->session->user);
		}
		$this->db->insert('heavy_logger', [
			'user_if_any' => $this->session->user ? $this->session->user['email'] : null,
			'url' => current_url(),
			'querystring' => $this->input->get() ? serialize($this->input->get()) : null,
			'ipaddress' => $this->input->ip_address(),
			'useragent' => $this->input->user_agent(),
		]);
	}

	public function index() {
		if ($this->session->user) {
			$this->home();
		} else {
			$this->landing();
		}
	}

	public function confirm_email($serial, $token) {
		$this->db # garbage collector, removes all tokens older than 1 hour
		->where('issue_dt <', date('Y-m-d H:i:s', strtotime('-1 hour')))
		->delete('pending_users');
		$pending_user = $this->db
		->where('serial', $serial)
		->where('token', hash('sha256', $token . LOGIN_SALT))
		->where('issue_dt >=', date('Y-m-d H:i:s', strtotime('-1 hour')))
		->get('pending_users')->result_array();
		$_error = true;
		if (!empty($pending_user)) {
			$pending_user = $pending_user[0];
			$email = $pending_user['email'];
			$existing_user = $this->db
			->where('email', $email)
			->get('users')->result_array();
			if (empty($existing_user)) {
				$this->db->insert('users', [
					'email' => $email,
					'password' => $pending_user['password'],
					'first_name' => '',
					'last_name' => '',
					'created' => date('Y-m-d H:i:s'),
					'forgot_time' => null,
					'reset_token' => null,
				]);
				$this->db
				->where('email', $email)
				->delete('pending_users');
				protomail($email, 'Account registrato', '_mail_registration_confirmation');

				$_error = false;
			}
		}
		if ($_error) {
			$this->load->view('expired_link');
		} else {
			$this->load->view('successful_registration');
		}
	}

	public function signup() {
		if ($this->session->user) {
			redirect('/main/home');
		} else {
			$_email_existing = false;
			$_form_incomplete = false;
			$_password_short = false;
			$_password_must_match = false;
			$_accept_terms = false;
			if ($this->input->post()) {
				if (!$this->input->post('accept_terms')) {
					$_accept_terms = true;
				}
				$email = $this->input->post('email');
				$password = $this->input->post('password');
				$cpassword = $this->input->post('cpassword');
				if (!$email || !$password || !$cpassword) {
					$_form_incomplete = true;
				}
				$_possible_user = $this->db
				->where('email', $email)
				->get('users')->result_array();
				if (!empty($_possible_user)) {
					$_email_existing = true;
				}
				if ($password) {
					if (strlen($password) < 6) {
						$_password_short = true;
					}
					if ($password != $cpassword) {
						$_password_must_match = true;
					}
				}
				if (
					   !$_email_existing
					&& !$_form_incomplete
					&& !$_password_short
					&& !$_password_must_match
					&& !$_accept_terms
				) {
					$hash_password = hash('sha256', $password . LOGIN_SALT);
					$token = generate_guid();
					$hash_token = hash('sha256', $token . LOGIN_SALT);
					$this->db->insert('pending_users', [
						'email' => $email,
						'password' => $hash_password,
						'token' => $hash_token,
						'issue_dt' => date('Y-m-d H:i:s'),
					]);
					$serial = $this->db->insert_id();

					protomail($email, 'Conferma indirizzo email', '_mail_confirm_email', [
						'token' => $token,
						'serial' => $serial,
					]);

					$this->load->view('signup_mail_sent', compact('email'));
					return;
				}
			}
			$this->load->view('signup', compact('_email_existing', '_form_incomplete', '_password_short', '_password_must_match', '_accept_terms'));
		}
	}

	public function g_geo() {
		$geos = [];
		if ($this->input->post('q') && $this->input->post('y') == 'x x') {
			$geos[] = geocode($this->input->post('q'));
		}
		$this->load->view('g_geo', compact('geos'));
	}

	public function landing() {
		$this->load->view('landing');
	}

	public function home() {
		$privileges = $this->db
		->join('privileges', 'cod_company = id_company', 'LEFT')
		->where('owner', $this->session->user['email'])
		->or_where('user', $this->session->user['email'])
		->get('companies')->result_array();
		$this->load->view('home', compact('privileges'));
	}

	public function login() {
		if ($this->session->user) {
			redirect('/');
		} else {
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
					redirect('/');
				}
			}
			$this->load->view('login', ['email' => $email]);
		}
	}

	public function logout() {
		$this->session->sess_destroy();
		// echo 'logg out';
		redirect('/');
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
				if (strlen($password) < 6) {
					$error = 'La password deve contenere almeno 6 caratteri';
				} elseif ($password != $c_password) {
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
							$this->load->view('password_changed');
							return;
						} else {
							$error = 'Il link è scaduto o l\'email non è corretta';
						}
					}
				}
			} elseif (
				($email = $this->input->post('email'))
				||
				($password = $this->input->post('password'))
				||
				($c_password = $this->input->post('c_password'))
			) {
				$error = 'Email, password e conferma password sono campi obbligatori';
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

				protomail($email, 'Recupero Password', '_mail_password_reset', [
					'name' => $user['first_name'],
					'reset_token' => $reset_token,
				]);
			}
		}
	}

}
