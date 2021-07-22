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

	public function get_orders_between($from_date = false, $to_date = false) {
		if ($from_date || $to_date) {
			$this->db->group_start();
			if ($from_date) {
				$this->db->where('delivery_time >=', $from_date);
			}
			if ($to_date) {
				$this->db->where('delivery_time <=', $to_date);
			}
			$this->db->or_where('delivery_time', null);
			$this->db->group_end();
		}
		$db_deliveries = $this->db
		->select('*')
		->select('COALESCE(delivery_time, order_time) AS delivery_time')
		->join('order_pizzas', 'order_pizzas.cod_delivery = deliveries.id_delivery', 'LEFT')
		->join('order_pizza_ingredients', 'deliveries.id_delivery = order_pizza_ingredients.x_cod_delivery AND order_pizzas.order_serial = order_pizza_ingredients.x_order_serial', 'LEFT')
		->where('cod_company', _GLOBAL_COMPANY['id_company'])
		->where('active', 1)
		->order_by('delivery_time', 'desc')
		->get('deliveries')->result_array();
		// debug($this->db->last_query());exit;
		$_rows = $deliveries = [];
		$orders_pizzas_ingredients = [];
		foreach ($db_deliveries as $info) {
			if (!isset($deliveries[$info['id_delivery']])) {
				$deliveries[$info['id_delivery']] = [
					'address' => $info['address'],
					'city' => '',
					'guid' => $info['guid'],
					'dismissed' => boolval($info['dismissed']),
					'cod_pony' => $info['cod_pony'],
					'delivery_time' => $info['delivery_time'] ? date('H:i', strtotime($info['delivery_time'])) : false,
					'delivery_date' => $info['delivery_time'] ? date('d/m/Y', strtotime($info['delivery_time'])) : false,
					'delivery_datetime' => $info['delivery_time'],
					'doorbell' => $info['doorbell'],
					'id_customer' => $info['cod_customer'],
					'id_order' => $info['id_delivery'],
					'is_delivery' => intval($info['is_delivery']),
					'name' => $info['name'],
					'notes' => $info['notes'],
					'north' => $info['north'],
					'east' => $info['east'],
					'travel_duration' => $info['travel_duration'],
					'total_price' => $info['total_price'],
					'telephone' => $info['telephone'],
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
				$_rows[$info['id_delivery']][$info['order_serial']] = [
					'id_piatto' => $info['cod_pizza'],
					'n' => $info['pizza_quantity'],
					'omaggio' => $info['uncharged'] ? true : false,
					'notes' => $info['pizza_notes'],
					'ingredients' => isset($orders_pizzas_ingredients[$info['id_delivery']][$info['order_serial']]) ? $orders_pizzas_ingredients[$info['id_delivery']][$info['order_serial']] : [],
				];
				$deliveries[$info['id_delivery']]['rows'] = array_values($_rows[$info['id_delivery']]);
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
		];

		if (isset($post['id_order'])) {
			$old_order = $this->db
			->where('cod_company', _GLOBAL_COMPANY['id_company'])
			->where('id_delivery', $post['id_order'])
			->get('deliveries')->result_array();
			if (!empty($old_order)) {
				$order = $old_order = $old_order[0];
			}
		}

		$time = isset($post['delivery_time']) ? $post['delivery_time'] : false;
		$day = date('Y-m-d');

		$order['total_price'] = $total;
		$order['id_delivery'] = isset($post['id_order']) ? $post['id_order'] : null;
		$order['is_delivery'] = isset($post['is_delivery']) ? $post['is_delivery'] : 0;
		$order['cod_customer'] = isset($post['id_customer']) ? $post['id_customer'] : null;
		$order['cod_payment'] = isset($post['payment_method']) ? $post['payment_method'] : null;
		$order['cod_pony'] = isset($post['cod_pony']) ? $post['cod_pony'] : null;
		$order['telephone'] = isset($post['telephone']) ? $post['telephone'] : null;
		$order['name'] = isset($post['name']) ? $post['name'] : '';
		$order['notes'] = isset($post['notes']) ? $post['notes'] : '';
		if ($time) {
			$order['delivery_time'] = $day.' '.$time.':00';
		}
		if ($order['is_delivery']) {
			$order['north'] = false;
			$order['east'] = false;
			$order['travel_duration'] = null;
			$order['doorbell'] = $post['doorbell'];
			$order['address'] = $post['address'];
			if ($order['id_delivery']) {
				if ($old_order['north'] || $old_order['east']) {
					# geocode già fatto
					if ($order['address'] == $old_order['address']) {
						# indirizzo non cambiato
						$order['north'] = $old_order['north'];
						$order['east'] = $old_order['east'];
						$order['travel_duration'] = $old_order['travel_duration'];
					}
				}
			}
			if (!$order['north'] && !$order['east']) {
				# non era già stato calcolato o è cambiato
				if ($order['cod_customer']) {
					$customer = $this->db
					->where('cod_company', _GLOBAL_COMPANY['id_company'])
					->where('id_customer', $order['cod_customer'])
					->get('customers')->result_array();
					if (!empty($customer)) {
						$customer = $customer[0];
						if ($customer['address'] == $order['address']) {
							if ($customer['north'] || $customer['east']) {
								$order['north'] = $customer['north'];
								$order['east'] = $customer['east'];
								$order['travel_duration'] = $customer['travel_duration'];
							}
						}
					}
				}
			}
			if (!$order['north'] && !$order['east']) {
				$geo = geocode($order['address']);
				if ($geo) {
					$order['travel_duration'] = distancematrix(_GLOBAL_COMPANY, $geo);
					$order['north'] = $geo['north'];
					$order['east'] = $geo['east'];
				}
			}
		} else {
			$order['doorbell'] = $order['address'] = $order['north'] = $order['east'] = $order['travel_duration'] = null;
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

	public function get_all_orders() {
		$deliveries = [];
		$orders_pizzas_ingredients = $this->db
		->join('order_pizzas', 'id_delivery = cod_delivery', 'LEFT')
		->join('order_pizza_ingredients', 'cod_delivery = x_cod_delivery', 'LEFT')
		->get('deliveries')->result_array();
		foreach ($order_pizzas_ingredients as $i => $dump) {
			if (!isset($deliveries[$dump['id_delivery']])) {
				$deliveries[$dump['id_delivery']] = [

				];
			}
		}
	}

	public function disableDelivery($id) {
		$delivery = $this->db
		->where('cod_company', _GLOBAL_COMPANY['id_company'])
		->where('id_delivery', $id)
		->where('active', 1)
		->get('deliveries')->result_array();
		if (!empty($delivery)) {
			$delivery = $delivery[0];
			$delivery['active'] = 0;
			$delivery['date_of_deactivation'] = date('Y-m-d H:i:s');
			$this->db->replace('deliveries', $delivery);
		}
	}

	public function dismissDeliveries($ids) {
		foreach ((array) $ids as $id) {
			$delivery = $this->db
			->where('cod_company', _GLOBAL_COMPANY['id_company'])
			->where('id_delivery', $id)
			->where('active', 1)
			->get('deliveries')->result_array();
			if (!empty($delivery)) {
				$delivery = $delivery[0];
				$delivery['dismissed'] = 1;
				$this->db->replace('deliveries', $delivery);
			}
		}
	}

	public function assignDeliveries($cod_pony, $ids) {
		foreach ((array) $ids as $id) {
			$delivery = $this->db
			->where('cod_company', _GLOBAL_COMPANY['id_company'])
			->where('id_delivery', $id)
			->where('active', 1)
			->get('deliveries')->result_array();
			if (!empty($delivery)) {
				$delivery = $delivery[0];
				$delivery['cod_pony'] = $cod_pony;
				$this->db->replace('deliveries', $delivery);
			}
		}
	}

	public function get_analytics() {
		$ordini = $this->get_orders_between();
		$_chart_weekdays = [];
		$_chart_months = [];
		$_chart_delivery_type = [];
		$_chart_payment_type = [];
		$ordini_per_data = [];
		foreach ($ordini as $ordine) {
			$ordini_per_data[date('Y-m-d', strtotime($ordine['delivery_datetime']))]['list'][] = $ordine;
			if (!isset($_chart_weekdays[date('l', strtotime($ordine['delivery_datetime']))])) {
				$_chart_weekdays[date('l', strtotime($ordine['delivery_datetime']))] = 0;
			}
			$_chart_weekdays[date('l', strtotime($ordine['delivery_datetime']))] += $ordine['total_price'];

			if (!isset($_chart_months[date('F', strtotime($ordine['delivery_datetime']))])) {
				$_chart_months[date('F', strtotime($ordine['delivery_datetime']))] = 0;
			}
			$_chart_months[date('F', strtotime($ordine['delivery_datetime']))] += $ordine['total_price'];

			if (!isset($_chart_delivery_type[$ordine['is_delivery']])) {
				$_chart_delivery_type[$ordine['is_delivery']] = 0;
			}
			$_chart_delivery_type[$ordine['is_delivery']] += $ordine['total_price'];

			if (!isset($_chart_payment_type[$ordine['payment_method']])) {
				$_chart_payment_type[$ordine['payment_method']] = 0;
			}
			$_chart_payment_type[$ordine['payment_method']] += $ordine['total_price'];

			if (!isset($ordini_per_data[date('Y-m-d', strtotime($ordine['delivery_datetime']))]['total'])) {
				$ordini_per_data[date('Y-m-d', strtotime($ordine['delivery_datetime']))]['total'] = 0;
			}
			$ordini_per_data[date('Y-m-d', strtotime($ordine['delivery_datetime']))]['total'] += $ordine['total_price'];
		}
		$chart_weekdays = [
			['Monday', isset($_chart_weekdays['Monday']) ? $_chart_weekdays['Monday'] : 0],
			['Tuesday', isset($_chart_weekdays['Tuesday']) ? $_chart_weekdays['Tuesday'] : 0],
			['Wednesday', isset($_chart_weekdays['Wednesday']) ? $_chart_weekdays['Wednesday'] : 0],
			['Thursday', isset($_chart_weekdays['Thursday']) ? $_chart_weekdays['Thursday'] : 0],
			['Friday', isset($_chart_weekdays['Friday']) ? $_chart_weekdays['Friday'] : 0],
			['Saturday', isset($_chart_weekdays['Saturday']) ? $_chart_weekdays['Saturday'] : 0],
			['Sunday', isset($_chart_weekdays['Sunday']) ? $_chart_weekdays['Sunday'] : 0],
		];

		$chart_months = [
			['January', isset($_chart_months['January']) ? $_chart_months['January'] : 0],
			['February', isset($_chart_months['February']) ? $_chart_months['February'] : 0],
			['March', isset($_chart_months['March']) ? $_chart_months['March'] : 0],
			['April', isset($_chart_months['April']) ? $_chart_months['April'] : 0],
			['May', isset($_chart_months['May']) ? $_chart_months['May'] : 0],
			['June', isset($_chart_months['June']) ? $_chart_months['June'] : 0],
			['July', isset($_chart_months['July']) ? $_chart_months['July'] : 0],
			['August', isset($_chart_months['August']) ? $_chart_months['August'] : 0],
			['September', isset($_chart_months['September']) ? $_chart_months['September'] : 0],
			['October', isset($_chart_months['October']) ? $_chart_months['October'] : 0],
			['November', isset($_chart_months['November']) ? $_chart_months['November'] : 0],
			['December', isset($_chart_months['December']) ? $_chart_months['December'] : 0],
		];
		$chart_delivery_type = [
			['Delivery', $_chart_delivery_type[1]],
			['Takeaway', $_chart_delivery_type[0]],
		];
		$chart_payment_type = [];
		$_p_methods = $this->orders_model->payment_methods();
		foreach ($_p_methods as $method) {
			if (isset($_chart_payment_type[$method['id_payment']])) {
				$dump = [$method['description']];
				foreach ((array) $_chart_payment_type[$method['id_payment']] as $_t) {
					$dump[] = $_t;
				}
				$chart_payment_type[] = $dump;
			}
		}

		return compact('ordini', 'ordini_per_data', 'chart_weekdays', 'chart_months', 'chart_delivery_type', 'chart_payment_type');
	}

}
