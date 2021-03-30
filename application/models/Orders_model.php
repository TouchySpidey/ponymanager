<?php
class Orders_model extends CI_Model {

	public function payment_methods() {

		$db_payment_methods = $this->db
		->where('active', 1)
		->where('cod_company', _GLOBAL_COMPANY['id_company'])
		->get('payment_methods')
		->result_array();
		return $db_payment_methods;

	}

	public function get_all_orders_after($datetime) {
		$db_deliveries = $this->db
		->where('cod_company', _GLOBAL_COMPANY['id_company'])
		->where('delivery_time >=', $datetime)
		->get('deliveries')->result_array();
		$deliveries = [];
		foreach ($db_deliveries as $db_delivery) {
			$deliveries[$db_delivery['id_delivery']] = $db_delivery;
		}

		return $deliveries;
	}

	protected function parseOrderInput($rows) {
		$CI = & get_instance();
		$menu = $CI->pizzeria->menu_components();
		foreach ($menu as $k => $v) {
			$$k = $v;
		}

		$sub_total = 1.5; # da tabella apposta
		foreach ($rows as $row) {
			$price = 0;
			if ($row['omaggio'] == 'false') {
				$price += floatval($pizzas[$row['id_piatto']]['price']);
				if (isset($row['ingredients']) && is_array($row['ingredients'])) {
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
			'guid' => generate_guid(),
			'cod_company' => _GLOBAL_COMPANY['id_company'],
			'order_time' => date('Y-m-d H:i:s'),
			'total_price' => $total,
		];
		return $order;
	}

	public function saveDelivery($post) {
		if (isset($post['rows'])) {
			$rows = $post['rows'] ?: [];
		} else {
			$rows = [];
		}
		$order = $this->parseOrderInput($rows);
		$time = isset($post['delivery_time']) ? $post['delivery_time'] : '00:00';
		$day = date('Y-m-d');

		$order['id_delivery'] = isset($post['id_order']) ? $post['id_order'] : null;
		$order['is_delivery'] = isset($post['is_delivery']) ? $post['is_delivery'] : 0;
		$order['cod_customer'] = isset($post['id_customer']) ? $post['id_customer'] : null;
		$order['name'] = $post['name'];
		if ($order['is_delivery']) {
			$order['doorbell'] = $post['doorbell'];
			$order['address'] = $post['address'];
			$order['city'] = $post['city'];
			$order['north'] = null;
			$order['east'] = null;
			$order['delivery_time'] = $day.' '.$time.':00';
		} else {
			$order['doorbell'] = $order['address'] = $order['city'] = $order['north'] = $order['east'] = $order['delivery_time'] = null;
		}
		$order['telephone'] = $post['telephone'];
		$order['cod_payment'] = isset($post['payment_method']) ? $post['payment_method'] : null;
		$order['order_data'] = JSON_encode($post);

		$this->db->replace('deliveries', $order);
	}

	public function saveTakeaway($post) {
		if (isset($post['rows'])) {
			$rows = $post['rows'] ?: [];
		} else {
			$rows = [];
		}
		$order = $this->parseOrderInput($rows);
		$time = isset($post['delivery_time']) ? $post['delivery_time'] : '00:00';
		$day = date('Y-m-d');

		$order['id_takeaway'] = isset($post['id_order']) ? $post['id_order'] : null;
		$order['cod_customer'] = isset($post['id_customer']) ? $post['id_customer'] : null;
		$order['name'] = $post['name'];
		$order['telephone'] = $post['telephone'];
		$order['takeaway_time'] = $day.' '.$time.':00';
		$order['cod_payment'] = isset($post['payment_method']) ? $post['payment_method'] : null;
		$order['order_data'] = JSON_encode($post);

		$this->db->replace('takeaways', $order);
	}

}
