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
		$this->load->view('orders_manager', $this->pizzeria->menu_components());
	}

	public function add_or_edit_order() {
		$menu = $this->pizzeria->menu_components();
		foreach ($menu as $k => $v) {
			$$k = $v;
		}
		$rows = $this->input->post('rows') ?: [];
		$sub_total = 0;
		foreach ($rows as $row) {
			$price = 0;
			if ($row['omaggio'] == 'false') {
				$price += floatval($pizzas[$row['id_piatto']]['price']);
				if (is_array($row['ingredients'])) {
					foreach ($row['ingredients'] as $id_ingredient) {
						if (!in_array($id_ingredient, $pizzas[$row['id_piatto']]['ingredients'])) {
							if (isset($ingredients[$id_ingredient])) {
								$price += $ingredients[$id_ingredient]['price'];
							}
						}
					}
				}
				$price *= intval($row['n']);
			}
			$sub_total += $price;
		}
		$total = $sub_total; # meno sconto
		$order = [
			'id_delivery' => $this->input->post('id_order') ?: null,
			'guid' => generate_guid(),
			'cod_company' => _GLOBAL_COMPANY['id_company'],
			'cod_customer' => $this->input->post('id_customer') ?: null,
			'order_time' => date('Y-m-d H:i:s'),
			'name' => $this->input->post('name'),
			'doorbell' => $this->input->post('doorbell'),
			'telephone' => $this->input->post('telephone'),
			'city' => $this->input->post('city'),
			'address' => $this->input->post('address'),
			'north' => null,
			'east' => null,
			'delivery_time' => $this->input->post('delivery_time'),
			'total_price' => $total,
			'order_data' => JSON_encode($this->input->post()),
		];
	}

}
