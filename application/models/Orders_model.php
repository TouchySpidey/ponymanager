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

		return $order;
	}

	public function saveDelivery($post) {
		if (isset($post['rows'])) {
			$rows = $post['rows'] ?: [];
		} else {
			$rows = [];
		}
		$CI = & get_instance();
		$menu = $CI->pizzeria->menu_components();
		foreach ($menu as $k => $v) {
			$$k = $v;
		}
		$sub_total = 1.5; # da tabella apposta
		$order_pizzas = [];
		$order_pizza_ingredients = [];
		foreach ($rows as $i => $row) {
			$pizza = $pizzas[$row['id_piatto']];
			$price = floatval($pizza['price']);
			if ($row['omaggio'] == 'false') {
				if (isset($row['ingredients']) && is_array($row['ingredients'])) {
					$j = 0;
					foreach ($row['ingredients'] as $id_ingredient) {
						if (isset($ingredients[$id_ingredient])) {
							$ingredient = $ingredients[$id_ingredient];
						} else {
							$ingredient = [
								'category' => '',
								'name' => '',
								'price' => 0,
							];
						}
						if (!in_array($id_ingredient, $pizza['ingredients'])) {
							# ingrediente extra aggiunto
							$price += $ingredient['price'];
							$order_pizza_ingredients[] = [
								'order_serial' => $i,
								'nth_ingredient' => $j++,
								'is_extra' => 1,
								'cod_ingredient' => $id_ingredient,
								'ingredient_category' => $ingredient['category'],
								'ingredient_name' => $ingredient['name'],
								'ingredient_price' => $ingredient['price'],
							];
						} else {
							# ingrediente di base
							$order_pizza_ingredients[] = [
								'order_serial' => $i,
								'nth_ingredient' => $j++,
								'is_extra' => 0,
								'cod_ingredient' => $id_ingredient,
								'ingredient_category' => $ingredient['category'],
								'ingredient_name' => $ingredient['name'],
								'ingredient_price' => $ingredient['price'],
							];
						}
					}
					foreach ($pizza['ingredients'] as $id_ingredient) {
						if (isset($ingredients[$id_ingredient])) {
							$ingredient = $ingredients[$id_ingredient];
						} else {
							$ingredient = [
								'category' => '',
								'name' => '',
								'price' => 0,
							];
						}
						if (!in_array($id_ingredient, $row['ingredients'])) {
							# ingrediente base rimosso
							$order_pizza_ingredients[] = [
								'order_serial' => $i,
								'nth_ingredient' => $j++,
								'is_extra' => -1,
								'cod_ingredient' => $id_ingredient,
								'ingredient_category' => $ingredient['category'],
								'ingredient_name' => $ingredient['name'],
								'ingredient_price' => $ingredient['price'],
							];
						}
					}
				}
			} else {
				$price = 0;
			}
			$order_pizzas[] = [
				'order_serial' => $i,
				'cod_pizza' => $row['id_piatto'],
				'pizza_category' => $pizza['category'],
				'pizza_name' => $pizza['name'],
				'pizza_price' => $price,
				'pizza_quantity' => $row['n'],
				'pizza_notes' => isset($row['notes']) ? $row['notes'] : '',
			];
			$sub_total += $price * doubleval($row['n']);
		}
		$total = $sub_total; # meno sconto
		$order = [
			'guid' => generate_guid(),
			'cod_company' => _GLOBAL_COMPANY['id_company'],
			'order_time' => date('Y-m-d H:i:s'),
			'total_price' => $total,
		];
		$time = isset($post['delivery_time']) ? $post['delivery_time'] : '00:00';
		$day = date('Y-m-d');

		$order['id_delivery'] = isset($post['id_order']) ? $post['id_order'] : null;
		$order['is_delivery'] = isset($post['is_delivery']) ? $post['is_delivery'] : 0;
		$order['cod_customer'] = isset($post['id_customer']) ? $post['id_customer'] : null;
		$order['cod_payment'] = isset($post['payment_method']) ? $post['payment_method'] : null;
		$order['telephone'] = isset($post['telephone']) ? $post['telephone'] : null;
		$order['name'] = isset($post['name']) ? $post['name'] : '';
		$order['notes'] = isset($post['notes']) ? $post['notes'] : '';
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
		if ($order['id_delivery']) {
			$id_delivery = $order['id_delivery'];
			$this->db->replace('deliveries', $order);
		} else {
			$this->db->insert('deliveries', $order);
			$id_delivery = $this->db->insert_id();
		}
		foreach ($order_pizzas as & $op) {
			$op['cod_delivery'] = $id_delivery;
		}
		foreach ($order_pizza_ingredients as & $op) {
			$op['cod_delivery'] = $id_delivery;
		}
		$this->db->where('cod_delivery', $id_delivery)->delete('order_pizzas');
		$this->db->where('cod_delivery', $id_delivery)->delete('order_pizza_ingredients');
		$this->db->insert_batch('order_pizzas', $order_pizzas);
		$this->db->insert_batch('order_pizza_ingredients', $order_pizza_ingredients);
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
