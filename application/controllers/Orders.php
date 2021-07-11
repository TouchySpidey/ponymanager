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
		$_delivery = [
			'payment_methods' => $this->orders_model->payment_methods(),
			'ponies' => $this->pony_model->ponies(),
			'shifts' => $this->company_model->get_shifts(),
		];
		$_menu = $this->pizzeria->menu_components();
		$this->load->view('orders_manager', array_merge($_delivery, $_menu));
	}

	public function analytics() {
		$_menu = $this->pizzeria->menu_components();
		$_analytics = $this->orders_model->get_analytics();
		$this->load->view('orders_list', array_merge($_menu, $_analytics));
	}

	public function get_todays_orders() {
		$today = date('Y-m-d');
		$shifts = $this->company_model->get_shifts();
		$open_time = '23:59';
		$close_time = '00:00';
		$_close = '00:00';
		foreach ($shifts as $shift) {
			if ($shift['from'] <= $open_time) {
				$open_time = $shift['from'];
				$from = $today . ' ' . $open_time;
			}
			if ($shift['from'] >= $_close) {
				$_close = $shift['from'];
				$close_time = $shift['to'];
				$to = $today . ' ' . $close_time;
				if ($close_time < $_close) {
					$to = date('Y-m-d H:i', strtotime($to.' +1 day'));
				}
			}
		}
		if ($from && $to) {
			echo JSON_encode($this->orders_model->get_orders_between($from, $to));
		} else {
			$this->db->insert('heavy_logger', [
				'user_if_any' => $this->session->user ? $this->session->user['email'] : null,
				'url' => current_url(),
				'querystring' => 'watch out! company:'._GLOBAL_COMPANY['id_company'],
				'ipaddress' => $this->input->ip_address(),
				'useragent' => $this->input->user_agent(),
			]);
		}
	}

	public function cost() {
		$costs = $this->expenses_model->get_expenses();
		$this->load->view('notaspese', compact('costs'));
	}

	public function preset() {
		$shifts = $this->company_model->get_shifts();
		$this->load->view('orders_preset', compact('shifts'));
	}

	public function add_or_edit_order() {
		$replace_id = $this->orders_model->saveDelivery($this->input->post());

		echo $replace_id;
	}

	public function dismiss_orders() {
		$this->orders_model->dismissDeliveries($this->input->post('ids'));
	}

	public function assign_orders() {
		$this->orders_model->assignDeliveries($this->input->post('cod_pony'), $this->input->post('ids'));
	}

	public function delete_order() {
		$this->orders_model->disableDelivery($this->input->post('id_order'));
	}

}
