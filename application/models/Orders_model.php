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
		->join('order_pizzas', 'order_pizzas.cod_delivery = deliveries.id_delivery', 'LEFT')
		->join('order_pizza_ingredients', 'deliveries.id_delivery = order_pizza_ingredients.x_cod_delivery AND order_pizzas.order_serial = order_pizza_ingredients.x_order_serial', 'LEFT')
		->where('cod_company', _GLOBAL_COMPANY['id_company'])
		->where('delivery_time >=', $datetime)
		->get('deliveries')->result_array();
		$deliveries = [];
		$orders_pizzas_ingredients = [];
		foreach ($db_deliveries as $info) {
			if (!isset($deliveries[$info['id_delivery']])) {
				$deliveries[$info['id_delivery']] = [
					'address' => $info['address'],
					'city' => $info['city'],
					'cod_pony' => $info['cod_pony'],
					'delivery_time' => date('H:i', strtotime($info['delivery_time'])),
					'doorbell' => $info['doorbell'],
					'id_customer' => $info['cod_customer'],
					'id_order' => $info['id_delivery'],
					'is_delivery' => intval($info['is_delivery']),
					'name' => $info['name'],
					'notes' => $info['notes'],
					'payment_method' => $info['cod_payment'],
					'rows' => [],
					'sconto' => null,
					'telephone' => $info['telephone'],
				];
			}
			if ($info['order_serial'] !== null) {
				# ordine con almeno un piatto
				if ($info['nth_ingredient'] !== null) {
					# piatto con almeno un ingrediente
					if (intval($info['is_extra']) >= 0) {
						# escludo gli ingredienti esclusi
						$orders_pizzas_ingredients[$info['id_delivery']][$info['order_serial']][] = $info['cod_ingredient'];
					}
				}
				$deliveries[$info['id_delivery']]['rows'][$info['order_serial']] = [
					'id_piatto' => $info['cod_pizza'],
					'n' => $info['pizza_quantity'],
					'ingredients' => isset($orders_pizzas_ingredients[$info['id_delivery']][$info['order_serial']]) ? $orders_pizzas_ingredients[$info['id_delivery']][$info['order_serial']] : [],
				];
			}
		}

		return $deliveries;
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
							'x_order_serial' => $i,
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
							'x_order_serial' => $i,
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
							'x_order_serial' => $i,
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
			$order_pizzas[] = [
				'order_serial' => $i,
				'cod_pizza' => $row['id_piatto'],
				'pizza_category' => $pizza['category'],
				'pizza_name' => $pizza['name'],
				'uncharged' => boolval($row['omaggio'] == 'true'),
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
		$order['cod_pony'] = isset($post['cod_pony']) ? $post['cod_pony'] : null;
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
			$op['x_cod_delivery'] = $id_delivery;
		}
		$this->db->where('cod_delivery', $id_delivery)->delete('order_pizzas');
		$this->db->where('x_cod_delivery', $id_delivery)->delete('order_pizza_ingredients');
		$this->db->insert_batch('order_pizzas', $order_pizzas);
		$this->db->insert_batch('order_pizza_ingredients', $order_pizza_ingredients);
		return $id_delivery;
	}

}
