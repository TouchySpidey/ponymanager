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
			'shifts' => $this->orders_model->company_shifts(),
		];
		$_menu = $this->pizzeria->menu_components();
		$this->load->view('orders_manager', array_merge($_delivery, $_menu));
	}

	public function analytics() {
		$this->load->view('orders_list');
	}

	public function get_todays_orders() {
		echo JSON_encode($this->orders_model->get_all_orders(date('Y-m-d H:i:s', strtotime('-1 day')), date('Y-m-d H:i:s', strtotime('+1 day'))));
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

	public function print_demo() {
		$orders = $this->orders_model->get_all_orders(date('Y-m-d H:i:s', strtotime('-1 day')));
		$this->load->view('print_demo', compact('orders'));
	}

}
